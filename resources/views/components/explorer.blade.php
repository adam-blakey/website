@props(['tabEntries', 'active'])

@php
    $folders = [
        'about' => ['awards' => 'trophy', 'talks' => 'presentation'],
        'interests' => ['music' => 'music-note', 'scouts' => 'campfire', 'software' => 'code-tags'],
    ];
    $folderColors = ['about' => 'text-violet-400/80', 'interests' => 'text-rose-400/80'];
    $inFolder = request()->routeIs(...array_map(fn ($folder) => $folder . '.*', array_keys($folders)));
    $row = 'flex items-center gap-1.5 h-7 rounded-md pr-2 transition-colors';
    $rowState = fn ($on) => $on ? 'bg-blue-600/25 text-white' : 'text-gray-300 hover:bg-gray-700';
@endphp

<aside class="hidden lg:flex flex-col w-60 shrink-0 rounded-xl bg-gray-850 text-[13px] select-none overflow-hidden">
    <div class="flex items-center justify-between h-10 px-3 shrink-0">
        <span class="flex items-center gap-1 font-semibold text-gray-200">Project {!! $mdi('chevron-down', 'text-gray-500', 14) !!}</span>
        <span class="flex items-center gap-2 text-gray-500">{!! $mdi('crosshairs-gps', '', 14) !!} {!! $mdi('dots-vertical', '', 14) !!}</span>
    </div>
    <div class="flex-1 overflow-y-auto px-1.5 pb-2">
        <div class="{{ $row }} pl-1 text-gray-200">
            {!! $mdi('chevron-down', 'text-gray-500', 14) !!} {!! $mdi('folder', 'text-blue-400', 16) !!}
            <span class="font-semibold">adam-blakey</span> <span class="text-gray-400 truncate">~/website</span>
        </div>
        @foreach ($folders as $folder => $files)
            <div class="{{ $row }} pl-5 text-gray-300">
                {!! $mdi('chevron-down', 'text-gray-500', 14) !!} {!! $mdi('folder-outline', 'text-gray-400', 16) !!} {{ $folder }}
            </div>
            @foreach ($files as $file => $icon)
                <a class="{{ $row }} {{ $rowState(request()->routeIs($folder . '.' . $file)) }} pl-[3.25rem]" href="{{ route($folder . '.' . $file) }}">
                    {!! $mdi($icon, $folderColors[$folder], 16) !!} {{ $file }}.md
                </a>
            @endforeach
        @endforeach
        @foreach ($tabEntries as $key => $link)
            <a class="{{ $row }} {{ $rowState($key === $active && !$inFolder) }} pl-9" href="{{ route($link->path) }}">
                {!! $mdi($link->icon, $link->color, 16) !!} {{ explode('.', $link->path)[0] }}.md
            </a>
        @endforeach
    </div>
</aside>
