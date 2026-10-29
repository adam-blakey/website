@props(['taggedVersion', 'latestTrack', 'programmingTime', 'programmingTimeUrl'])

<div class="fixed bottom-0 left-0 right-0 z-30 bg-gray-950/95 backdrop-blur font-mono text-[11px] text-gray-400 border-t border-white/5">
    <!-- On medium screens and up: single row layout -->
    <div class="hidden sm:flex h-6 items-center justify-between">
        <div class="flex items-center h-full">
            <span class="flex items-center h-full px-2.5 bg-blue-600 text-white" aria-hidden="true">{!! $mdi('code-tags', 'inline', 14) !!}</span>
            <x-a href="https://github.com/adam-blakey/adam-website-2024" target="_blank" class="flex items-center gap-1 h-full px-3 hover:bg-white/5 hover:text-white" :noUnderline="true">
                {!! $si('github', 'inline', 11) !!} View Source
            </x-a>
            <span class="flex items-center gap-1 px-3">{!! $mdi('tag-outline', 'inline', 13) !!} v{{ $taggedVersion ?? '1.0.0' }}</span>
        </div>
        <div class="flex items-center h-full">
            <span class="flex items-center gap-1 px-3 truncate">{!! $mdi('music-note', 'inline ' . ($latestTrack ? 'text-green-400' : ''), 13) !!} {{ $latestTrack ? 'Currently listening to: ' . $latestTrack->track_name . ' by ' . $latestTrack->artist_name : 'Not currently listening to music' }}</span>
            <x-a href="{{ $programmingTimeUrl }}" class="flex items-center gap-1 h-full px-3 hover:bg-white/5 hover:text-white" :noUnderline="true">{!! $mdi('clock-outline', 'inline', 13) !!} {{ $programmingTime ?? '0 hrs' }} programming</x-a>
        </div>
    </div>

    <!-- On small screens: two row layout -->
    <div class="sm:hidden">
        <!-- Top row -->
        <div class="flex items-center justify-between px-3 h-6">
            <x-a href="https://github.com/adam-blakey/adam-website-2024" target="_blank" class="hover:text-white flex items-center gap-1" :noUnderline="true">
                {!! $si('github', 'inline', 11) !!} View Source
            </x-a>
            <span class="flex items-center gap-1">{!! $mdi('tag-outline', 'inline', 13) !!} v{{ $taggedVersion ?? '1.0.0' }}</span>
        </div>
        <!-- Bottom row -->
        <div class="flex items-center justify-between gap-3 px-3 h-6 border-t border-white/5">
            <span class="flex items-center gap-1 truncate">{!! $mdi('music-note', 'inline shrink-0 ' . ($latestTrack ? 'text-green-400' : ''), 13) !!} <span class="truncate">{{ $latestTrack ? 'Listening to: ' . $latestTrack->track_name . ' by ' . $latestTrack->artist_name : 'No music playing' }}</span></span>
            <x-a href="{{ $programmingTimeUrl }}" class="flex items-center gap-1 shrink-0" :noUnderline="true">{!! $mdi('clock-outline', 'inline', 13) !!} {{ $programmingTime ?? '0 hrs' }}</x-a>
        </div>
    </div>
</div>
