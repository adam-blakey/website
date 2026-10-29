@props(['taggedVersion', 'latestTrack', 'programmingTime', 'programmingTimeUrl'])

@php
    $segments = request()->segments() ?: ['welcome'];
@endphp

<footer class="fixed lg:static bottom-0 left-0 right-0 z-30 shrink-0 bg-gray-700 text-xs text-gray-400">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between sm:h-7 px-3 py-1 sm:py-0 gap-1 sm:gap-4">
        <!-- Navigation bar (breadcrumbs), as in the JetBrains status bar -->
        <div class="flex items-center gap-0.5 min-w-0 overflow-x-auto whitespace-nowrap select-none">
            <a class="rounded px-1 hover:bg-gray-600 hover:text-white" href="/">adam-blakey</a>
            @foreach ($segments as $segment)
                {!! $mdi('chevron-right', 'shrink-0 text-gray-500', 14) !!}
                <span class="px-1 {{ $loop->last ? 'text-gray-200' : '' }}">{{ $segment }}{{ $loop->last ? '.md' : '' }}</span>
            @endforeach
        </div>

        <div class="flex items-center justify-between sm:justify-end gap-3 min-w-0 whitespace-nowrap">
            <span class="flex items-center gap-1 min-w-0" title="{{ $latestTrack ? 'Currently listening to ' . $latestTrack->track_name . ' by ' . $latestTrack->artist_name : 'Not currently listening to music' }}">
                {!! $mdi('music-note', 'shrink-0 ' . ($latestTrack ? 'text-emerald-400' : ''), 13) !!}
                <span class="truncate">{{ $latestTrack ? $latestTrack->track_name . ' – ' . $latestTrack->artist_name : 'Nothing playing' }}</span>
            </span>
            <x-a href="{{ $programmingTimeUrl }}" class="flex items-center gap-1 shrink-0 rounded px-1 hover:bg-gray-600 hover:text-white" :noUnderline="true" :noExternalIcon="true">{!! $mdi('clock-outline', '', 13) !!} {{ $programmingTime ?? '0 hrs' }} programming</x-a>
            <span class="hidden md:inline">UTF-8</span>
            <span class="hidden md:flex items-center gap-1">{!! $mdi('tag-outline', '', 13) !!} v{{ $taggedVersion ?? '1.0.0' }}</span>
        </div>
    </div>
</footer>
