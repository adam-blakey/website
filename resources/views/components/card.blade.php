@props([
    'hFull' => false,
    'title' => '',
    'color' => 'bg-gray-800'
])

<div class="mx-2 mt-2 rounded-lg p-8 shadow-xl {{ $hFull ? 'h-full' : '' }} {{ $color }}">
    @if($title != "")
        <h3 class="text-2xl text-white">{{ $title }}</h3>
    @endif

    {{ $slot }}
</div>
