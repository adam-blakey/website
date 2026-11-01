@props([
    'hFull' => false,
    'title' => '',
    'color' => 'bg-gray-800'
])

<div {{ $attributes->merge(['class' => 'mx-2 mt-2 rounded-xl border border-gray-700/70 p-6 sm:p-7 transition-colors hover:border-gray-600 ' . ($hFull ? 'h-full ' : '') . $color]) }}>
    @if($title != "")
        <h3 class="text-white">{{ $title }}</h3>
    @endif

    {{ $slot }}
</div>
