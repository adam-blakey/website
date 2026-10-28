@props(['tabEntries', 'active'])

<nav
    class="flex items-center px-4 text-sm bg-gray-800 w-screen
        overflow-x-auto border-b border-gray-700
        [&::-webkit-scrollbar]:w-full
        [&::-webkit-scrollbar]:h-2
        [&::-webkit-scrollbar-thumb]:bg-gray-600">
    <div class="flex items-center space-x-2 tabs-container">
        @foreach ($tabEntries as $link)
            <a class="{{ !request()->is($link->path) && !(request()->is('/') && $link->path == 'welcome') ? 'bg-gray-700' : 'bg-gray-600' }} whitespace-nowrap rounded-t px-2 pb-1 pt-1 text-white whitespace-wrap select-none"
                href="{{ route($link->path) }}">
                {!! $mdi($link->icon, 'inline mr-1', 16) !!} {{ explode('.', $link->path)[0] }}.md
            </a>
        @endforeach
    </div>
</nav>
