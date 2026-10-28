@props([
    'categories',
    'tags',
    'selected_categories' => [],
    'selected_tags' => []
])

<div class="md:col-span-1">
    <!-- Categories -->
    <x-card title="Categories">
        <ul class="space-y-2 mt-4">
            @foreach($categories as $category)
                <li>
                    <x-a href="{{ $category->url }}" class="flex justify-between items-center text-gray-300 hover:text-blue-400 {{ (isset($currentCategory) && $currentCategory->id === $category->id) || in_array($category->id, $selected_categories) ? 'font-bold text-blue-400' : '' }}">
                        <span>{{ $category->name }}</span>
                        <span class="bg-gray-700 text-gray-300 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $category->posts_count }}</span>
                    </x-a>
                </li>
            @endforeach
        </ul>
    </x-card>

    <!-- Tags -->
    <x-card title="Tags" class="mt-8">
        <div class="flex flex-wrap gap-2 mt-4">
            @foreach($tags as $tag)
                @php
                    if (request()->is(substr($tag->url, 1)))
                    {
                        $url = route('blog.index');
                    }
                    else
                    {
                        $url = $tag->url;
                    }
                @endphp

                <x-a href="{{ $url }}" class="inline-block px-3 py-1 text-sm {{ (isset($currentTag) && $currentTag->id === $tag->id) || in_array($tag->id, $selected_tags) ? 'bg-blue-600 text-white' : 'bg-gray-700 text-gray-300' }} rounded-full hover:bg-blue-600 hover:text-white transition-colors">
                    {{ $tag->name }} ({{ $tag->posts_count }})
                </x-a>
            @endforeach
        </div>
    </x-card>
</div>
