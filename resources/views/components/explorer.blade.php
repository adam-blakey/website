@props(['tabEntries', 'active'])

<aside class="hidden lg:flex flex-col w-56 shrink-0 bg-gray-950 border-r border-white/5 font-mono text-[13px] select-none">
    <div class="px-4 pt-3 pb-2 text-[11px] font-medium tracking-widest text-gray-500">EXPLORER</div>
    <div class="flex items-center gap-1 px-2 py-1 text-gray-300 font-medium">
        {!! $mdi('chevron-down', 'inline text-gray-500', 16) !!} adam-blakey
    </div>
    <ul class="mt-0.5">
        @foreach ($tabEntries as $key => $link)
            <li>
                <a class="{{ $key === $active ? 'bg-blue-500/10 text-white border-blue-400' : 'text-gray-400 border-transparent hover:bg-white/5 hover:text-gray-200' }} flex items-center gap-2 py-1 pl-8 pr-3 border-l-2 transition-colors"
                    href="{{ route($link->path) }}">
                    {!! $mdi($link->icon, 'inline ' . ($key === $active ? 'text-blue-400' : 'text-gray-500'), 16) !!} {{ explode('.', $link->path)[0] }}.md
                </a>
            </li>
        @endforeach
    </ul>
</aside>
