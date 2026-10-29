@props(['tabEntries', 'active'])

<nav
    class="flex items-center gap-1 h-10 shrink-0 px-2 text-[13px] border-b border-gray-700/70
        overflow-x-auto overflow-y-hidden
        [&::-webkit-scrollbar]:h-1
        [&::-webkit-scrollbar-thumb]:bg-gray-600">
    @foreach ($tabEntries as $key => $link)
        <a class="{{ $key === $active ? 'bg-gray-700 text-white' : 'text-gray-400 hover:bg-gray-700/50 hover:text-gray-200' }} flex items-center gap-1.5 whitespace-nowrap rounded-md px-2.5 h-7 select-none transition-colors"
            href="{{ route($link->path) }}" @if ($key === $active) aria-current="page" @endif>
            {!! $mdi($link->icon, $link->color, 15) !!} {{ explode('.', $link->path)[0] }}.md
        </a>
    @endforeach
</nav>
<script>
    // Keep the open tab visible when the tab bar overflows on small screens
    document.querySelector('nav [aria-current="page"]')?.scrollIntoView({ block: 'nearest', inline: 'center' });
</script>
