@props([
    'post',
    'categories',
    'tags',
    'relatedPosts',
    'nextPost',
    'previousPost'
])

<x-layout>
    <div class="container px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Main content -->
            <div class="md:col-span-2">
                <x-card>
                <!-- Post header -->
                <header class="mb-8">
                    <h1 class="text-4xl font-bold text-gray-100 mb-4">{{ $post->title }}</h1>
                    <div class="flex flex-wrap items-center text-sm text-gray-400 mb-6">
                        <time datetime="{{ $post->published_at->format('Y-m-d\TH:i:s') }}" class="mr-4">
                            {{ $post->published_at->format('F j, Y \a\t H:i') }}
                        </time>
                        @if($post->categories->count() > 0)
                            <div class="mr-4">
                                <span class="mr-1">Categories:</span>
                                @foreach($post->categories as $category)
                                    <x-a href="{{ $category->url }}" class="text-blue-400 hover:underline">
                                        {{ $category->name }}
                                    </x-a>
                                    @if(!$loop->last), @endif
                                @endforeach
                            </div>
                        @endif
                        @if($post->tags->count() > 0)
                            <div>
                                <span class="mr-1">Tags:</span>
                                {!! $post->tags->map(fn($tag) =>
                                    '<a href="' . e($tag->url) . '" class="text-blue-400 underline">' . e($tag->name) . '</a>'
                                )->implode(', ') !!}
                            </div>
                        @endif
                    </div>
                    @if($post->featuredMedia)
                        <div class="mb-8">
                            <img src="{{ $post->featuredMedia->url }}"
                                 @if($post->featuredMedia->has_responsive_images)
                                 srcset="{{ $post->featuredMedia->srcset }}"
                                 sizes="(max-width: 320px) 320px, (max-width: 768px) 768px, (max-width: 1280px) 1280px, 1920px"
                                 @endif
                                 alt="{{ $post->title }}"
                                 class="w-full h-auto rounded-lg shadow-md">
                        </div>
                    @endif
                </header>

                <!-- Post content -->
                <article class="prose dark:prose-invert lg:prose-lg max-w-none mb-12 space-y-4">
                    {!! $post->content !!}
                </article>

                <!-- Related posts -->
                @if($relatedPosts->count() > 0)
                    <div class="border-t border-gray-700 pt-8 mt-8">
                        <h2 class="text-2xl font-bold text-gray-100 mb-6">Related Posts</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($relatedPosts as $relatedPost)
                                <div class="bg-gray-700 rounded-lg shadow-md overflow-hidden">
                                    @if($relatedPost->featuredMedia)
                                        <a href="{{ $relatedPost->url }}">
                                            <img src="{{ $relatedPost->featuredMedia->url }}"
                                                 @if($relatedPost->featuredMedia->has_responsive_images)
                                                 srcset="{{ $relatedPost->featuredMedia->srcset }}"
                                                 sizes="(max-width: 320px) 320px, (max-width: 768px) 768px, (max-width: 1280px) 1280px, 1920px"
                                                 @endif
                                                 alt="{{ $relatedPost->title }}"
                                                 class="w-full h-40 object-cover">
                                        </a>
                                    @endif
                                    <div class="p-4">
                                        <h3 class="text-lg font-bold mb-2">
                                            <x-a href="{{ $relatedPost->url }}" class="text-white hover:text-blue-400">
                                                {{ $relatedPost->title }}
                                            </x-a>
                                        </h3>
                                        <div class="text-sm text-gray-300 mb-2">
                                            {{ $relatedPost->formatted_date }}
                                        </div>
                                        <x-a href="{{ $relatedPost->url }}" class="text-blue-400 hover:underline text-sm">
                                            Read more
                                        </x-a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Next/Previous Navigation -->
                <div class="border-t border-gray-700 pt-8 mt-8">
                    <div class="flex justify-between">
                        @if($previousPost)
                            <div>
                                <span class="text-gray-400 block mb-2">Previous Post</span>
                                <x-a href="{{ $previousPost->url }}" class="text-blue-400 hover:underline">
                                    &larr; {{ $previousPost->title }}
                                </x-a>
                            </div>
                        @else
                            <div></div>
                        @endif

                        @if($nextPost)
                            <div class="text-right">
                                <span class="text-gray-400 block mb-2">Next Post</span>
                                <x-a href="{{ $nextPost->url }}" class="text-blue-400 hover:underline">
                                    {{ $nextPost->title }} &rarr;
                                </x-a>
                            </div>
                        @else
                            <div></div>
                        @endif
                    </div>
                </div>
                </x-card>
            </div>

            <x-blog-sidebar
                :categories="$categories"
                :tags="$tags"
                :selected_categories="$post->categories->pluck('id')->toArray()"
                :selected_tags="$post->tags->pluck('id')->toArray()"
            />
        </div>
    </div>
</x-layout>
