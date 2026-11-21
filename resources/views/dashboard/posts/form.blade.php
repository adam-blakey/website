<x-dashboard-layout :title="$post->exists ? 'Edit post' : 'New post'" pretitle="Blog posts">
    <form method="POST" action="{{ $post->exists ? route('dashboard.posts.update', $post) : route('dashboard.posts.store') }}" class="card">
        @csrf
        @if ($post->exists)
            @method('PUT')
        @endif

        <div class="card-body">
            <x-dashboard.field name="title" label="Title" :value="$post->title" required />
            <x-dashboard.field name="slug" label="Slug" :value="$post->slug" required hint="Letters, numbers, dashes and underscores. The post lives at /blog/your-slug." />
            <x-dashboard.field name="published_at" label="Published" type="datetime-local" :value="$post->published_at?->format('Y-m-d\TH:i')" required />
            <x-dashboard.field name="content" label="Content (HTML)" type="textarea" :value="$post->content" rows="16" class="font-monospace" required />

            @if ($post->wordpress_id !== null)
                <x-dashboard.keep-local-edits />
            @endif
        </div>

        <div class="card-footer text-end">
            <a href="{{ route('dashboard.posts.index') }}" class="btn">Cancel</a>
            <button type="submit" class="btn btn-primary">Save post</button>
        </div>
    </form>
</x-dashboard-layout>
