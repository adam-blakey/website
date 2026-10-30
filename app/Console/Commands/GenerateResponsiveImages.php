<?php

namespace App\Console\Commands;

use App\Models\BlogMedia;
use App\Models\BlogPost;
use App\Models\BlogPostImage;
use Illuminate\Console\Command;
use DOMDocument;
use DOMXPath;
use Nette\Utils\Image;

class GenerateResponsiveImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:generate-responsive-images {--batch=10 : Number of images to process per batch}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate responsive webp images for blog media and update blog posts with srcset attributes';

    /**
     * The image sizes to generate.
     *
     * @var array
     */
    protected $sizes = [
        'small' => 320,
        'medium' => 768,
        'large' => 1280,
        'xlarge' => 1920,
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $batchSize = (int) $this->option('batch');

        $this->info("Generating responsive images in batches of {$batchSize}");

        // Get all media that don't have responsive images yet
        $mediaQuery = BlogMedia::whereNull('path_small_webp')
            ->orWhereNull('path_medium_webp')
            ->orWhereNull('path_large_webp')
            ->orWhereNull('path_xlarge_webp');

        $totalMedia = $mediaQuery->count();

        if ($totalMedia === 0) {
            $this->info('All media already have responsive images.');
            return;
        }

        $this->info("Found {$totalMedia} media items to process");

        // Process media in batches
        $mediaQuery->chunk($batchSize, function ($mediaItems) {
            $this->processMediaBatch($mediaItems);
        });

        // Update blog posts with srcset attributes
        $this->updateBlogPosts();

        $this->info('Responsive image generation completed successfully.');
    }

    /**
     * Process a batch of media items.
     */
    private function processMediaBatch($mediaItems)
    {
        $this->info("Processing batch of " . count($mediaItems) . " media items");

        foreach ($mediaItems as $media) {
            $this->processMedia($media);
        }
    }

    /**
     * Process a single media item.
     */
    private function processMedia(BlogMedia $media)
    {
        $this->info("Processing media: {$media->filename}");

        // Skip if not an image
        if (!$this->isImage($media->mime_type)) {
            $this->warn("Skipping non-image file: {$media->filename}");
            return;
        }

        // Get the original file path
        $originalPath = public_path($media->path);

        // Check if the original file exists
        if (!file_exists($originalPath)) {
            $this->error("Original file not found: {$originalPath}");
            return;
        }

        try {
            // Load the image
            $image = Image::fromFile($originalPath);

            // Generate responsive images
            foreach ($this->sizes as $size => $width) {
                $this->generateResponsiveImage($media, $image, $size, $width);
            }

            // Save the media record
            $media->save();

            $this->info("Successfully processed media: {$media->filename}");
        } catch (\Exception $e) {
            $this->error("Failed to process media {$media->filename}: {$e->getMessage()}");
            report($e);
        }
    }

    /**
     * Generate a responsive image of the specified size.
     */
    private function generateResponsiveImage(BlogMedia $media, $image, $size, $width)
    {
        // Create a clone of the image to avoid modifying the original
        $resizedImage = clone $image;

        // Resize the image, maintaining aspect ratio
        $resizedImage->resize($width, null, Image::ShrinkOnly);

        // Generate filename for the responsive image
        $filename = pathinfo($media->filename, PATHINFO_FILENAME) . "_{$size}.webp";
        $path = 'blog-media/' . $filename;
        $fullPath = public_path('storage/' . $path);

        // Ensure the directory exists
        $directory = dirname($fullPath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Save as webp
        $resizedImage->save($fullPath, 80);

        // Update the media record with the new path
        $columnName = "path_{$size}_webp";
        $media->$columnName = 'storage/' . $path;

        $this->info("Generated {$size} webp image: {$filename}");
    }

    /**
     * Update blog posts with srcset attributes.
     */
    private function updateBlogPosts()
    {
        $this->info('Updating blog posts with srcset attributes');

        // Get all blog posts
        BlogPost::chunk(50, function ($posts) { // TODO: wtf?
            foreach ($posts as $post) {
                $this->updatePostContent($post);
            }
        });
    }

    /**
     * Update a single blog post's content with srcset attributes.
     */
    private function updatePostContent(BlogPost $post)
    {
        $this->info("Updating post: {$post->title}");

        // Parse HTML content
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($post->content, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $images = $xpath->query('//img');
        $contentUpdated = false;

        foreach ($images as $img) {
            $src = $img->getAttribute('src');
            if (!$src) {
                continue;
            }

            // Find the media record for this image
            $postImage = BlogPostImage::where('post_id', $post->id)
                ->whereHas('media', function ($query) use ($src) {
                    $query->where('path', 'like', '%' . basename($src));
                })
                ->first();

            if (!$postImage) {
                continue;
            }

            $media = $postImage->media;

            // Skip if the media doesn't have responsive images
            if (!$media->has_responsive_images) {
                continue;
            }

            // Add srcset attribute
            $img->setAttribute('srcset', $media->srcset);

            // Add sizes attribute if not present
            if (!$img->hasAttribute('sizes')) {
                $img->setAttribute('sizes', '(max-width: 320px) 320px, (max-width: 768px) 768px, (max-width: 1280px) 1280px, 1920px');
            }

            $contentUpdated = true;
        }

        // Update the post content if changes were made
        if ($contentUpdated) {
            $updatedContent = $dom->saveHTML();
            if ($updatedContent) {
                $post->content = $updatedContent;
                $post->save();
                $this->info("Updated content for post: {$post->title}");
            }
        }
    }

    /**
     * Check if the mime type is an image.
     */
    private function isImage(?string $mimeType): bool
    {
        if (empty($mimeType)) {
            return false;
        }

        return str_starts_with($mimeType, 'image/');
    }
}
