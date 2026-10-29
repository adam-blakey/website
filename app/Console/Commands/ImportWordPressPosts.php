<?php

namespace App\Console\Commands;

use App\Models\BlogCategory;
use App\Models\BlogMedia;
use App\Models\BlogPost;
use App\Models\BlogPostImage;
use App\Models\BlogTag;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DOMDocument;
use DOMXPath;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\NoPrivateNetworkHttpClient;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ImportWordPressPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:import-wordpress {maxNumberOfPosts=25} {--overwrite}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import blog posts from WordPress JSON URL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $maxNumberOfPosts = $this->argument('maxNumberOfPosts');
        $overwrite = $this->option('overwrite');

        $baseUrl = config('import-wordpress.url');

        $this->info("Fetching JSON from URL...");

        try {
            $allPosts = [];
            $page = 1;
            $postsFound = true;

            // Loop through pages until we have enough posts or no more posts are found
            while (count($allPosts) < $maxNumberOfPosts && $postsFound) {
                // Add pagination parameters to the URL
                $url = $baseUrl;
                if (strpos($url, '?') !== false) {
                    $url .= '&page=' . $page;
                } else {
                    $url .= '?page=' . $page;
                }

                $this->info("Fetching page {$page}...");
                $response = $this->safeGet($url);

                if ($response->getStatusCode() == 400) {
                    $jsonContent = $response->getContent(false);
                    $error_message = json_decode($jsonContent, true);

                    if ($error_message['code'] == 'rest_post_invalid_page_number') {
                        break;
                    }
                    else {
                        $this->error("Unexpected error: " . $error_message['message']);
                        return 1;
                    }
                }
                elseif (!$response->getStatusCode() < 300) {
                    $this->error("Failed to fetch JSON file: HTTP " . $response->getStatusCode());
                    return 1;
                }

                $jsonContent = $response->getContent(false);
                $posts = json_decode($jsonContent, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    $this->error("Invalid JSON content: " . json_last_error_msg());
                    return 1;
                }

                // If no posts are found on this page, break the loop
                if (empty($posts)) {
                    $postsFound = false;
                    continue;
                }

                // Add new posts to our collection
                $newPosts = 0;
                foreach ($posts as $post) {
                    if (($overwrite) || (!Self::doesPostExist($post['id']) && (count($allPosts) < $maxNumberOfPosts)))
                    {
                        $allPosts[] = $post;
                        $newPosts++;
                    }
                }
                $this->info("Found " . $newPosts . " new posts on page {$page}.");

                // Move to the next page
                $page++;
            }

            $this->info("Found a total of " . count($allPosts) . " posts across all pages.");

            $number_to_import = min(count($allPosts), $maxNumberOfPosts);
            $this->info("Importing " . $number_to_import . " posts...");
            $this->info("Creating storage directory for blog media...");

            // Create storage directory if it doesn't exist
            Storage::disk('public')->makeDirectory('blog-media', 0755, true);

            $bar = $this->output->createProgressBar($number_to_import);
            $bar->start();
            $this->newLine();

            $imported = 0;
            foreach ($allPosts as $post) {
                $this->importPost($post, $overwrite);
                $imported++;
                if ($imported >= $maxNumberOfPosts) {
                    break;
                }
                $bar->advance();
                $this->newLine();
            }

            $bar->finish();
            $this->newLine();
            $this->info("Import completed successfully!");

            return 0;
        } catch (\Exception $e) {
            $this->error("Failed to fetch or process JSON file: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Check if a post with this ID already exists.
     */
    private static function doesPostExist(int $id): bool
    {
        $existingPost = BlogPost::where('wordpress_id', $id)->first();
        if ($existingPost) {
            return true;
        }

        return false;
    }

    /**
     * Import a single WordPress post.
     */
    private function importPost(array $post, bool $overwrite): void
    {
        $wordpressId = $post['id'];

        // Skip if already imported and not overwriting
        $postExists = Self::doesPostExist($wordpressId);
        if ($postExists) {
            if (!$overwrite) {
                return;
            }

            BlogPost::where('wordpress_id', $wordpressId)->delete();
        }

        // Extract the required fields
        $title = $post['title']['rendered'] ?? '';
        $title = html_entity_decode($title, ENT_QUOTES | ENT_HTML5);
        $slug = $post['slug'] ?? '';
        $content = $post['content']['rendered'] ?? '';
        $dateGmt = $post['date_gmt'] ?? null;
        $featuredMediaId = $post['featured_media'] ?? null;
        $categories = $post['categories'] ?? [];
        $tags = $post['tags'] ?? [];

        $carbonDate = Carbon::parse($dateGmt);
        $this->info("Importing post {$title}");

        // Extract links for categories and tags from _links structure
        $categoryLink = null;
        $tagLink = null;
        if (isset($post['_links']) && isset($post['_links']['wp:term']) && is_array($post['_links']['wp:term'])) {
            foreach ($post['_links']['wp:term'] as $termLink) {
                if (isset($termLink['taxonomy']) && isset($termLink['href'])) {
                    if ($termLink['taxonomy'] === 'category') {
                        $categoryLink = $termLink['href'];
                    } elseif ($termLink['taxonomy'] === 'post_tag') {
                        $tagLink = $termLink['href'];
                    }
                }
            }
        }

        // Process featured media
        $featuredMediaDbId = null;
        if ($featuredMediaId && $featuredMediaId > 0) {
            $featuredMediaDbId = $this->processMedia($featuredMediaId, $post);
        }

        // Create the blog post
        $blogPost = BlogPost::create([
            'wordpress_id' => $wordpressId,
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'featured_media_id' => $featuredMediaDbId,
            'published_at' => $carbonDate,
        ]);

        // Process categories
        if (!empty($categories) && $categoryLink) {
            $categoryData = $this->fetchTermData($categoryLink);
            foreach ($categories as $categoryId) {
                $category = $this->findOrCreateCategory($categoryId, $categoryLink, $categoryData);
                if ($category) {
                    $blogPost->categories()->attach($category->id);
                }
            }
        } else {
            foreach ($categories as $categoryId) {
                $category = $this->findOrCreateCategory($categoryId);
                if ($category) {
                    $blogPost->categories()->attach($category->id);
                }
            }
        }

        // Process tags
        if (!empty($tags) && $tagLink) {
            $tagData = $this->fetchTermData($tagLink);
            foreach ($tags as $tagId) {
                $tag = $this->findOrCreateTag($tagId, $tagLink, $tagData);
                if ($tag) {
                    $blogPost->tags()->attach($tag->id);
                }
            }
        } else {
            foreach ($tags as $tagId) {
                $tag = $this->findOrCreateTag($tagId);
                if ($tag) {
                    $blogPost->tags()->attach($tag->id);
                }
            }
        }

        // Process content images
        $this->processContent($blogPost, $content);
    }

    /**
     * Fetch a URL, refusing private/reserved IPs on every hop (SSRF guard).
     */
    private function safeGet(string $url, string $method = 'GET'): ResponseInterface
    {
        return (new NoPrivateNetworkHttpClient(HttpClient::create(['timeout' => 30])))->request($method, $url);
    }

    /**
     * Fetch term data (categories or tags) from WordPress API.
     */
    private function fetchTermData(string $url): array
    {
        try {
            $response = $this->safeGet($url);
            if ($response->getStatusCode() < 300) {
                $data = json_decode($response->getContent(false), true);
                if (is_array($data)) {
                    return $data;
                }
            }
        } catch (\Exception $e) {
            $this->error("Failed to fetch term data: {$e->getMessage()}");
        }

        return [];
    }

    /**
     * Find or create a category by WordPress ID.
     */
    private function findOrCreateCategory(int $wordpressCategoryId, ?string $categoryLink = null, array $categoryData = []): ?BlogCategory
    {
        // If we have category data, try to find the category by ID
        if (!empty($categoryData)) {
            foreach ($categoryData as $category) {
                if (isset($category['id']) && $category['id'] == $wordpressCategoryId &&
                    isset($category['name']) && isset($category['slug'])) {
                    $categoryName = $category['name'];
                    $categorySlug = $category['slug'];

                    return BlogCategory::firstOrCreate(
                        ['slug' => $categorySlug],
                        ['name' => $categoryName]
                    );
                }
            }
        }

        // If we have a link but no matching category in the data, fetch it directly
        if ($categoryLink && empty($categoryData)) {
            try {
                $response = $this->safeGet($categoryLink);
                if ($response->getStatusCode() < 300) {
                    $categories = json_decode($response->getContent(false), true);
                    if (is_array($categories) && !empty($categories)) {
                        foreach ($categories as $category) {
                            if (isset($category['id']) && $category['id'] == $wordpressCategoryId &&
                                isset($category['name']) && isset($category['slug'])) {
                                $categoryName = $category['name'];
                                $categorySlug = $category['slug'];

                                return BlogCategory::firstOrCreate(
                                    ['slug' => $categorySlug],
                                    ['name' => $categoryName]
                                );
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                $this->error("Failed to fetch category details: {$e->getMessage()}");
            }
        }

        // Fallback to placeholder if API call fails or no matching category found
        $categoryName = "Category {$wordpressCategoryId}";
        $categorySlug = Str::slug($categoryName);

        return BlogCategory::firstOrCreate(
            ['slug' => $categorySlug],
            ['name' => $categoryName]
        );
    }

    /**
     * Find or create a tag by WordPress ID.
     */
    private function findOrCreateTag(int $wordpressTagId, ?string $tagLink = null, array $tagData = []): ?BlogTag
    {
        // If we have tag data, try to find the tag by ID
        if (!empty($tagData)) {
            foreach ($tagData as $tag) {
                if (isset($tag['id']) && $tag['id'] == $wordpressTagId &&
                    isset($tag['name']) && isset($tag['slug'])) {
                    $tagName = $tag['name'];
                    $tagSlug = $tag['slug'];

                    return BlogTag::firstOrCreate(
                        ['slug' => $tagSlug],
                        ['name' => $tagName]
                    );
                }
            }
        }

        // If we have a link but no matching tag in the data, fetch it directly
        if ($tagLink && empty($tagData)) {
            try {
                $response = $this->safeGet($tagLink);
                if ($response->getStatusCode() < 300) {
                    $tags = json_decode($response->getContent(false), true);
                    if (is_array($tags) && !empty($tags)) {
                        foreach ($tags as $tag) {
                            if (isset($tag['id']) && $tag['id'] == $wordpressTagId &&
                                isset($tag['name']) && isset($tag['slug'])) {
                                $tagName = $tag['name'];
                                $tagSlug = $tag['slug'];

                                return BlogTag::firstOrCreate(
                                    ['slug' => $tagSlug],
                                    ['name' => $tagName]
                                );
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                $this->error("Failed to fetch tag details: {$e->getMessage()}");
            }
        }

        // Fallback to placeholder if API call fails or no matching tag found
        $tagName = "Tag {$wordpressTagId}";
        $tagSlug = Str::slug($tagName);

        return BlogTag::firstOrCreate(
            ['slug' => $tagSlug],
            ['name' => $tagName]
        );
    }

    /**
     * Process and download media.
     */
    private function processMedia(int $mediaId, array $post): ?int
    {
        // In a real implementation, you would fetch media details from WordPress API
        // For this example, we'll use the media URL from the post if available

        $mediaUrl = null;

        // Try to find media URL in the post data
        if (isset($post['jetpack_featured_media_url'])) {
            $mediaUrl = $post['jetpack_featured_media_url'];
        } elseif (isset($post['yoast_head_json']['og_image'][0]['url'])) {
            $mediaUrl = $post['yoast_head_json']['og_image'][0]['url'];
        }

        if (!$mediaUrl) {
            return null;
        }

        // Check if we've already downloaded this image
        $existingMedia = BlogMedia::where('original_url', $mediaUrl)->first();
        if ($existingMedia) {
            return $existingMedia->id;
        }

        return $this->downloadAndSaveMedia($mediaUrl, 'featured_' . $mediaId);
    }

    /**
     * Download and save a media file.
     */
    private function downloadAndSaveMedia(string $url, string $filenamePrefix): ?int
    {
        // Determine file extension from URL or content type
        $extension = pathinfo($url, PATHINFO_EXTENSION);

        // If no extension in URL, try to get it from the content type
        if (empty($extension)) {
            try {
                $response = $this->safeGet($url, 'HEAD');
                $contentType = ($response->getHeaders(false)['content-type'][0] ?? '');
                $extension = $this->getExtensionFromMimeType($contentType);
            } catch (\Exception $e) {
                // Default to jpg if we can't determine the extension
                $extension = 'jpg';
            }
        }

        // Generate a unique filename
        $filename = $filenamePrefix . '_' . time() . '.' . $extension;
        $path = 'blog-media/' . $filename;

        try {
            // Download the file with a timeout
            $response = $this->safeGet($url);
            if ($response->getStatusCode() < 300) {
                $contentType = ($response->getHeaders(false)['content-type'][0] ?? '');

                // Verify it's an image
                if (!$this->isImageContentType($contentType)) {
                    $this->warn("Skipping non-image file: {$url} (Content-Type: {$contentType})");
                    return null;
                }

                // Save the file
                Storage::disk('public')->put($path, $response->getContent(false));

                // Create media record
                $media = BlogMedia::create([
                    'filename' => $filename,
                    'original_url' => $url,
                    'path' => 'storage/' . $path,
                    'mime_type' => $contentType,
                ]);
                $this->info("Downloaded media: {$url} -> {$filename}");
                return $media->id;
            } else {
                $this->warn("Failed to download media (HTTP " . $response->getStatusCode() . "): {$url}");
            }
        } catch (\Exception $e) {
            // Log error but continue with import
            $this->error("Failed to download media: {$url} - {$e->getMessage()}");
        }

        return null;
    }

    /**
     * Check if the content type is an image.
     */
    private function isImageContentType(?string $contentType): bool
    {
        if (empty($contentType)) {
            return false;
        }

        return str_starts_with($contentType, 'image/');
    }

    /**
     * Get file extension from MIME type.
     */
    private function getExtensionFromMimeType(?string $mimeType): string
    {
        if (empty($mimeType)) {
            return 'jpg';
        }

        $map = [
            'image/jpeg' => 'jpg',
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'image/svg+xml' => 'svg',
            'image/bmp' => 'bmp',
            'image/tiff' => 'tiff',
        ];

        return $map[$mimeType] ?? 'jpg';
    }

    /**
     * Process images in the content.
     */
    private function processContent(BlogPost $post, string $content): void
    {
        // Parse HTML content to find images
        $dom = new DOMDocument();
        libxml_use_internal_errors(true); // Suppress HTML5 parsing errors
        $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $images = $xpath->query('//img');
        $contentUpdated = false;

        foreach ($images as $img) {
            $src = $img->getAttribute('src');
            if (!$src) {
                continue;
            }

            // Skip if the URL is already a local URL
            if (str_starts_with($src, asset('storage/'))) {
                continue;
            }

            // Check if we've already downloaded this image
            $existingMedia = BlogMedia::where('original_url', $src)->first();

            if ($existingMedia) {
                // Create post image record if it doesn't exist
                $postImage = BlogPostImage::firstOrCreate(
                    [
                        'post_id' => $post->id,
                        'media_id' => $existingMedia->id,
                        'original_url' => $src,
                    ]
                );

                // Update the content with the existing image URL
                $img->setAttribute('src', $existingMedia->url);
                $contentUpdated = true;
                continue;
            }

            // Download and store the image
            $mediaId = $this->downloadAndSaveMedia($src, 'content_' . $post->id . '_' . uniqid());

            if ($mediaId) {
                // Create post image record
                BlogPostImage::create([
                    'post_id' => $post->id,
                    'media_id' => $mediaId,
                    'original_url' => $src,
                ]);

                // Get the media record
                $media = BlogMedia::find($mediaId);

                // Update the content with the new image URL
                $img->setAttribute('src', $media->url);
                $contentUpdated = true;
            }
        }

        // Strip unnecessary attributes from all HTML tags
        $allElements = $xpath->query('//*');
        foreach ($allElements as $element) {
            // Remove class attributes
            if ($element->hasAttribute('class')) {
                $element->removeAttribute('class');
                $contentUpdated = true;
            }

            // Remove srcset attributes
            if ($element->hasAttribute('srcset')) {
                $element->removeAttribute('srcset');
                $contentUpdated = true;
            }

            // Remove loading attributes
            if ($element->hasAttribute('loading')) {
                $element->removeAttribute('loading');
                $contentUpdated = true;
            }

            // Remove decoding attributes
            if ($element->hasAttribute('decoding')) {
                $element->removeAttribute('decoding');
                $contentUpdated = true;
            }
        }

        // Update the post content with the new image URLs and stripped attributes if any changes were made
        if ($contentUpdated) {
            $updatedContent = $dom->saveHTML();
            if ($updatedContent) {
                $post->update(['content' => $updatedContent]);
                $this->info("Updated content for post: {$post->title}");
            }
        }
    }
}
