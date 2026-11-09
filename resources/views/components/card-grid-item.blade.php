@props([
    'title' => "",
    'view' => null,
    'icon' => null,
    'rowSpan' => 1,
    'colSpan' => 1,
    'color' => 'bg-gray-800'
])

<div class="relative row-span-1 col-span-1 {{ $rowSpan > 1 ? 'lg:row-span-' . $rowSpan : '' }} {{
    match($colSpan) {
        1 => 'lg:col-span-1',
        2 => 'lg:col-span-2',
        3 => 'lg:col-span-3',
        4 => 'lg:col-span-4',
        5 => 'lg:col-span-5',
        default => 'lg:col-span-3'
    }
}}">
    <x-card :title="$title" :view="$view" :icon="$icon" :hFull="true" :color="$color">
        <div class="flex h-full flex-col">
            <div class="mt-2 text-sm/6">{{ $slot }}</div>
        </div>
    </x-card>
</div>
