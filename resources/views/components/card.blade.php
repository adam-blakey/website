@props([
    'hFull' => false,
    'title' => '',
    'color' => 'bg-gray-800'
])

<div {{ $attributes->merge(['class' => 'mx-2 mt-2 rounded-xl border border-white/[0.06] p-6 sm:p-8 shadow-lg shadow-black/20 transition-colors hover:border-white/10 ' . ($hFull ? 'h-full ' : '') . $color]) }}>
    @if($title != "")
        <h3 class="text-white">{{ $title }}</h3>
    @endif

    {{ $slot }}
</div>
