<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\MusicNowPlaying;
use App\Models\TabNavLink;
use App\Models\TopMenuEntry;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the blog posts.
     */
    public function index(Request $request)
    {
        $posts = BlogPost::with(['featuredMedia', 'categories', 'tags'])
            ->orderBy('published_at', 'desc')
            ->paginate(5);

        $categories = BlogCategory::withCount('posts')->orderBy('name')->get();
        $tags = BlogTag::withCount('posts')->orderBy('name')->get();

        return view('blog.index', [
            'posts' => $posts,
            'categories' => $categories,
            'tags' => $tags,
            'title' => 'Blog'
        ]);
    }

    /**
     * Display the specified blog post.
     */
    public function show(string $slug)
    {
        $post = BlogPost::with(['featuredMedia', 'categories', 'tags', 'contentImages.media'])
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedPosts = BlogPost::with('featuredMedia')
            ->whereHas('categories', function ($query) use ($post) {
                $query->whereIn('blog_categories.id', $post->categories->pluck('id'));
            })
            ->where('id', '!=', $post->id)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        // Get next and previous posts
        $nextPost = BlogPost::where('published_at', '>', $post->published_at)
            ->orderBy('published_at', 'asc')
            ->first();

        $previousPost = BlogPost::where('published_at', '<', $post->published_at)
            ->orderBy('published_at', 'desc')
            ->first();

        // Get categories and tags for the sidebar
        $categories = BlogCategory::withCount('posts')->orderBy('name')->get();
        $tags = BlogTag::withCount('posts')->orderBy('name')->get();

        return view('blog.show', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
            'nextPost' => $nextPost,
            'previousPost' => $previousPost,
            'categories' => $categories,
            'tags' => $tags,
            'title' => $post->title
        ]);
    }

    /**
     * Display posts by category.
     */
    public function category(string $slug)
    {
        $category = BlogCategory::where('slug', $slug)->firstOrFail();

        $posts = BlogPost::with(['featuredMedia', 'categories', 'tags'])
            ->whereHas('categories', function ($query) use ($category) {
                $query->where('blog_categories.id', $category->id);
            })
            ->orderBy('published_at', 'desc')
            ->paginate(5);

        $categories = BlogCategory::withCount('posts')->orderBy('name')->get();
        $tags = BlogTag::withCount('posts')->orderBy('name')->get();

        return view('blog.index', [
            'posts' => $posts,
            'categories' => $categories,
            'tags' => $tags,
            'currentCategory' => $category,
            'title' => "Category: {$category->name}",
            'filtered_categories' => [$category['id']]
        ]);
    }

    /**
     * Display posts by tag.
     */
    public function tag(string $slug)
    {
        $tag = BlogTag::where('slug', $slug)->firstOrFail();

        $posts = BlogPost::with(['featuredMedia', 'categories', 'tags'])
            ->whereHas('tags', function ($query) use ($tag) {
                $query->where('blog_tags.id', $tag->id);
            })
            ->orderBy('published_at', 'desc')
            ->paginate(5);

        $categories = BlogCategory::withCount('posts')->orderBy('name')->get();
        $tags = BlogTag::withCount('posts')->orderBy('name')->get();

        return view('blog.index', [
            'posts' => $posts,
            'categories' => $categories,
            'tags' => $tags,
            'currentTag' => $tag,
            'title' => "Tag: {$tag->name}",
            'filtered_tags' => [$tag['id']]
        ]);
    }
}
