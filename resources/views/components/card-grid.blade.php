@props([
    'rows' => 1,
    'columns' => 3
])

<div class="grid gap-y-4 gap-x-1 grid-cols-1 {{ 'lg:grid-rows-' . $rows }} {{
    match($columns) {
        1 => 'lg:grid-cols-1',
        2 => 'lg:grid-cols-2',
        3 => 'lg:grid-cols-3',
        4 => 'lg:grid-cols-4',
        5 => 'lg:grid-cols-5',
        default => 'lg:grid-cols-3'
    }
}}">
    {{ $slot }}
</div>
