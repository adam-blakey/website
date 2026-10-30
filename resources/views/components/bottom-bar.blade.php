@props(['taggedVersion', 'latestTrack', 'programmingTime'])

@php
    $segments = request()->segments() ?: ['welcome'];
@endphp

<footer class="fixed lg:static bottom-0 left-0 right-0 z-30 shrink-0 bg-gray-700 text-xs text-gray-400">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between sm:h-7 px-3 py-1 sm:py-0 gap-1 sm:gap-4">
        <!-- Breadcrumbs for the current page -->
        <div class="flex items-center gap-0.5 min-w-0 overflow-x-auto whitespace-nowrap select-none">
            <a class="rounded px-1 hover:bg-gray-600 hover:text-white" href="/">adam-blakey</a>
            @foreach ($segments as $segment)
                {!! $mdi('chevron-right', 'shrink-0 text-gray-500', 14) !!}
                <a class="rounded px-1 hover:bg-gray-600 hover:text-white {{ $loop->last ? 'text-gray-200' : '' }}" href="/{{ implode('/', array_slice(request()->segments(), 0, $loop->iteration)) }}">{{ $segment }}{{ $loop->last ? '.md' : '' }}</a>
            @endforeach
        </div>

        <div class="flex items-center justify-between sm:justify-end gap-3 min-w-0 whitespace-nowrap">
            <a class="flex items-center gap-1 min-w-0 rounded px-1 hover:bg-gray-600 hover:text-white" href="{{ route('now') }}" title="{{ $latestTrack ? 'Currently listening to ' . $latestTrack->track_name . ' by ' . $latestTrack->artist_name : 'Not currently listening to music' }}">
                {!! $mdi('music-box', 'shrink-0' . ($latestTrack ? ' text-sky-400 motion-safe:animate-bounce' : ''), 13) !!}
                <span class="truncate">{{ $latestTrack ? $latestTrack->track_name . ' – ' . $latestTrack->artist_name : 'Nothing playing' }}</span>
            </a>
            <a class="flex items-center gap-1 shrink-0 rounded px-1 hover:bg-gray-600 hover:text-white" href="{{ route('now') }}">{!! $mdi('clock-outline', $programmingTime && preg_match('/[1-9]/', $programmingTime) ? 'text-blue-400' : '', 13) !!} {{ $programmingTime ?? '0 hrs' }} programming</a>
            <span class="hidden md:inline">UTF-8</span>
            <span class="flex items-center gap-1 shrink-0">{!! $mdi('tag-outline', '', 13) !!} v{{ $taggedVersion ?? '1.0.0' }}</span>
        </div>
    </div>
</footer>
