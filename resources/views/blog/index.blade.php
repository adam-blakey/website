@props([
    'title',
    'posts',
    'categories',
    'tags',
    'filtered_categories' => [],
    'filtered_tags' => [],
])

<x-layout>
    @if ($title != "Blog")
        <h3><span class="text-gray-500">###</span> {{ $title }}</h3>
    @endif

    <div class="container px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Main content -->
            <div class="md:col-span-3">
                @if($posts->count() > 0)
                    <div class="grid grid-cols-1 gap-8">
                        @foreach($posts as $post)
                            <x-card>
                                <div class="flex flex-col md:flex-row">
                                    @if($post->featuredMedia)
                                        <div class="md:w-1/3 flex-shrink-0">
                                            <x-a href="{{ $post->url }}">
                                                <img src="{{ $post->featuredMedia->url }}"
                                                     @if($post->featuredMedia->has_responsive_images)
                                                     srcset="{{ $post->featuredMedia->srcset }}"
                                                     sizes="(max-width: 320px) 320px, (max-width: 768px) 768px, (max-width: 1280px) 1280px, 1920px"
                                                     @endif
                                                     alt="{{ $post->title }}"
                                                     class="w-full h-48 object-cover rounded-lg">
                                            </x-a>
                                        </div>
                                    @endif
                                    <div class="p-4 md:p-6 @if($post->featuredMedia) md:w-2/3 md:pl-6 @endif">
                                        <h2 class="text-2xl font-bold mb-2">
                                            <x-a href="{{ $post->url }}" class="text-white hover:text-blue-400">
                                                {{ $post->title }}
                                            </x-a>
                                        </h2>
                                        <div class="text-sm text-gray-300 mb-4">
                                            {{ $post->formatted_date }}
                                            @if($post->categories->count() > 0)
                                                <span class="mx-2">|</span>
                                                @foreach($post->categories as $category)
                                                    <x-a href="{{ $category->url }}" class="text-blue-400 hover:underline">
                                                        {{ $category->name }}
                                                    </x-a>
                                                    @if(!$loop->last), @endif
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="prose dark:prose-invert max-w-none mb-4">
                                            {!! Str::limit(strip_tags($post->content), 200) !!}
                                        </div>
                                        <x-a href="{{ $post->url }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-100 bg-gray-700 border border-gray-600 rounded-lg hover:bg-gray-600 hover:text-white focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-700 focus:text-white" :noUnderline="true">
                                            Read more<span class="sr-only"> about {{ $post->title }}</span>
                                        </x-a>
                                    </div>
                                </div>
                            </x-card>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $posts->links() }}
                    </div>
                @else
                    <x-card>
                        <p class="text-xl text-gray-300 text-center">No blog posts found.</p>
                    </x-card>
                @endif
            </div>

            <x-blog-sidebar
                :categories="$categories"
                :tags="$tags"
                :selected_categories="$filtered_categories"
                :selected_tags="$filtered_tags" />
        </div>
    </div>
</x-layout>
