@props([
    'hFull' => false,
    'title' => '',
    'view' => null,
    'icon' => null,
    'color' => 'bg-gray-800'
])

<div {{ $attributes->merge(['class' => 'mx-2 mt-2 rounded-xl border border-gray-700/70 p-6 sm:p-7 transition-colors hover:border-gray-600 ' . ($hFull ? 'h-full ' : '') . $color]) }}>
    @if($title != "")
        <h3 class="flex items-center gap-2 text-white">
            @if($icon)
                {!! \Mdi\Mdi::mdi($icon, 'shrink-0', 24) !!}
            @endif
            @if($view)
                <x-a :view="$view" :noUnderline="true" class="inline-flex items-center hover:underline">
                    {{ $title }}
                    {!! \Mdi\Mdi::mdi('arrow-right', 'ml-1', 20) !!}
                </x-a>
            @else
                {{ $title }}
            @endif
        </h3>
    @endif

    {{ $slot }}
</div>
