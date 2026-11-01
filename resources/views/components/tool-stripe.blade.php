@php
    $top = [
        ['icon' => 'folder-outline', 'label' => 'Project', 'route' => 'index', 'active' => true],
        ['icon' => 'post-outline', 'label' => 'Blog', 'route' => 'blog.index', 'active' => false],
        ['icon' => 'music-note-outline', 'label' => 'Music', 'route' => 'interests.music', 'active' => false],
    ];
    $bottom = [
        ['icon' => 'console', 'label' => 'Editor', 'route' => 'editor.show'],
        ['icon' => 'email-outline', 'label' => 'Contact', 'route' => 'contact'],
    ];
@endphp

<nav class="hidden lg:flex flex-col items-center justify-between w-9 shrink-0 py-1 text-gray-400" aria-label="Tool windows">
    <div class="flex flex-col gap-1">
        @foreach ($top as $item)
            <a class="{{ $item['active'] ? 'bg-gray-600 text-white' : 'hover:bg-gray-600/60 hover:text-white' }} grid place-items-center size-8 rounded-md transition-colors" href="{{ route($item['route']) }}" title="{{ $item['label'] }}" aria-label="{{ $item['label'] }}">
                {!! $mdi($item['icon'], '', 18) !!}
            </a>
        @endforeach
    </div>
    <div class="flex flex-col gap-1">
        @foreach ($bottom as $item)
            <a class="grid place-items-center size-8 rounded-md hover:bg-gray-600/60 hover:text-white transition-colors" href="{{ route($item['route']) }}" title="{{ $item['label'] }}" aria-label="{{ $item['label'] }}">
                {!! $mdi($item['icon'], '', 18) !!}
            </a>
        @endforeach
    </div>
</nav>
