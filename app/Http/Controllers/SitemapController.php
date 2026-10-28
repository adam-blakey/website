<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

class SitemapController extends Controller
{
    public function index()
    {
        $routes = collect(Route::getRoutes())->filter(function ($route) {
            // Only include GET routes with names that are publicly accessible
            return $route->methods()[0] === 'GET' &&
                   $route->getName() &&
                   !str_starts_with($route->getName(), 'editor.') &&
                   $route->getName() !== 'contact.submit';
        });

        $urls = $routes->map(function ($route) {
            // Get the URL for the route
            $url = URL::to($route->uri());

            // Skip routes with parameters (like blog/{slug})
            if (str_contains($route->uri(), '{')) {
                return null;
            }

            // Calculate priority based on URL depth (more slashes = lower priority)
            $path = parse_url($url, PHP_URL_PATH);
            $slashCount = substr_count($path, '/');
            // Base priority is 0.8, reduce by 0.1 for each level of depth, but not below 0.3
            $priority = max(0.3, 0.8 - (($slashCount - 1) * 0.1));

            return [
                'loc' => $url,
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => (string) $priority,
            ];
        })->filter();

        // Add blog posts URLs
        $blogPosts = BlogPost::all();
        foreach ($blogPosts as $post) {
            $urls->push([
                'loc' => route('blog.show', $post->slug),
                'lastmod' => $post->updated_at->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.2',
            ]);
        }

        // Increase priority and update changefreq for welcome view.
        $urls = $urls->map(function ($url) {
            if ($url['loc'] === URL::to('/') || $url['loc'] === URL::to('/welcome')) {
                $url['changefreq'] = 'always';
                $url['priority'] = '1.0';
            }
            return $url;
        });

        // Set up route to change always with minimum priority.
        $urls = $urls->map(function ($url) {
            if ($url['loc'] === URL::to('/up')) {
                $url['changefreq'] = 'always';
                $url['priority'] = '0.1';
            }
            return $url;
        });

        $content = view('sitemap', ['urls' => $urls]);
        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }
}
