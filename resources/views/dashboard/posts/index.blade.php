<x-dashboard-layout title="Blog posts">
    <x-slot:actions>
        <a href="{{ route('dashboard.posts.create') }}" class="btn btn-primary">New post</a>
    </x-slot:actions>

    <div class="card">
        @if ($posts->isEmpty())
            <div class="empty">
                <div class="empty-icon">{!! $mdi('post-outline', 'icon', 48) !!}</div>
                <p class="empty-title">No posts yet</p>
                <p class="empty-subtitle text-secondary">Write one here, or wait for the next WordPress import.</p>
                <div class="empty-action">
                    <a href="{{ route('dashboard.posts.create') }}" class="btn btn-primary">New post</a>
                </div>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Published</th>
                            <th>Source</th>
                            <th class="w-1"><span class="visually-hidden">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posts as $post)
                            <tr>
                                <td><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></td>
                                <td class="text-secondary">{{ $post->published_at->format('j M Y') }}</td>
                                <td><x-dashboard.source-badge :imported="$post->wordpress_id !== null" :keepsLocalEdits="$post->keep_local_edits" /></td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <a href="{{ route('dashboard.posts.edit', $post) }}" class="btn btn-sm">Edit</a>
                                        <x-dashboard.confirm-delete :action="route('dashboard.posts.destroy', $post)" :name="$post->title" :imported="$post->wordpress_id !== null" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($posts->hasPages())
                <div class="card-footer">{{ $posts->links('pagination::bootstrap-5') }}</div>
            @endif
        @endif
    </div>
</x-dashboard-layout>
