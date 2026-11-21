<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function index()
    {
        return view('dashboard.posts.index', ['posts' => BlogPost::orderByDesc('published_at')->paginate(20)]);
    }

    public function create()
    {
        return view('dashboard.posts.form', ['post' => new BlogPost(['published_at' => now()])]);
    }

    public function store(Request $request)
    {
        $post = BlogPost::create($this->validated($request, new BlogPost));

        return redirect()->route('dashboard.posts.index')->with('status', "Created “{$post->title}”.");
    }

    public function edit(BlogPost $post)
    {
        return view('dashboard.posts.form', ['post' => $post]);
    }

    public function update(Request $request, BlogPost $post)
    {
        $post->update($this->validated($request, $post));

        return redirect()->route('dashboard.posts.index')->with('status', "Saved “{$post->title}”.");
    }

    public function destroy(BlogPost $post)
    {
        $post->delete();

        return redirect()->route('dashboard.posts.index')->with('status', "Deleted “{$post->title}”.");
    }

    private function validated(Request $request, BlogPost $post): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'slug' => ['required', 'alpha_dash', 'max:255', Rule::unique('blog_posts')->ignore($post)],
            'published_at' => 'required|date',
            'content' => 'required|string',
            'keep_local_edits' => 'boolean',
        ]);
    }
}
