@props(['tabEntries', 'active'])

@php
    $segments = request()->segments() ?: ['welcome'];
@endphp

<nav
    class="flex items-end w-full bg-gray-950 font-mono text-[13px]
        overflow-x-auto border-b border-white/5
        [&::-webkit-scrollbar]:h-1.5
        [&::-webkit-scrollbar-thumb]:bg-gray-700">
    <div class="flex items-end tabs-container">
        @foreach ($tabEntries as $key => $link)
            <a class="{{ $key === $active ? 'bg-gray-900 text-white border-t-blue-400' : 'text-gray-500 border-t-transparent hover:bg-white/[0.03] hover:text-gray-300' }} flex items-center gap-1.5 whitespace-nowrap border-t-2 border-r border-r-white/5 px-4 py-2 select-none transition-colors"
                href="{{ route($link->path) }}">
                {!! $mdi($link->icon, 'inline ' . ($key === $active ? 'text-blue-400' : ''), 16) !!} {{ explode('.', $link->path)[0] }}.md
            </a>
        @endforeach
    </div>
</nav>
<div class="flex items-center gap-1 px-4 sm:px-8 h-7 bg-gray-900 border-b border-white/5 font-mono text-xs text-gray-500 overflow-x-auto whitespace-nowrap select-none">
    adam-blakey
    @foreach ($segments as $segment)
        {!! $mdi('chevron-right', 'inline', 14) !!}
        <span class="{{ $loop->last ? 'text-gray-300' : '' }}">{{ $segment }}{{ $loop->last ? '.md' : '' }}</span>
    @endforeach
</div>
