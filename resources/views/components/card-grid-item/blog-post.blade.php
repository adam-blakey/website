@php
    use Illuminate\Support\Str;
@endphp

@props([
    'post'
])

@if (is_null($post))
    <x-card-grid-item title="No blog posts found">
        <div class="mt-4">
            <p class="text-sm text-gray-400">
                Check back later for blog posts.
            </p>
        </div>
    </x-card-grid-item>
@else
    <x-card-grid-item title="A random blog post...">
        <div class="flex flex-col space-y-4">
            @if($post->featuredMedia)
                <div class="w-full">
                    <x-a href="{{ $post->url }}">
                        <img class="w-full h-40 rounded-lg object-cover"
                             src="{{ $post->featuredMedia->url }}"
                             @if($post->featuredMedia->has_responsive_images)
                             srcset="{{ $post->featuredMedia->srcset }}"
                             sizes="(max-width: 320px) 320px, (max-width: 768px) 768px, (max-width: 1280px) 1280px, 1920px"
                             @endif
                             alt="Featured image for {{ $post->title }}" />
                    </x-a>
                </div>
            @endif
            <div>
                <h4 class="font-semibold"><x-a href="{{ $post->url }}">{{ $post->title }}</x-a></h4>
                <p class="text-sm text-gray-400 mt-1">{{ $post->formatted_date }}</p>
                <div class="prose dark:prose-invert max-w-none mt-2 text-sm">
                    {!! Str::limit(strip_tags($post->content), 150) !!}
                </div>
            </div>
        </div>

        <div class="mt-4">
            <x-a href="{{ $post->url }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-100 bg-gray-700 border border-gray-600 rounded-lg hover:bg-gray-600 hover:text-white focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-700 focus:text-white" :noUnderline="true">
                Read more
            </x-a>
            <p class="text-sm text-gray-400 mt-2">
                {!! $mdi('post', 'inline mr-1', 16) !!} <x-a view="blog.index" class="text-blue-500">View all blog posts</x-a>
            </p>
        </div>
    </x-card-grid-item>
@endif
