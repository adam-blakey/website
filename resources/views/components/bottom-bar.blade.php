@props(['taggedVersion', 'latestTrack', 'programmingTime', 'programmingTimeUrl'])

<div class="fixed bottom-0 left-0 right-0 bg-gray-800 text-xs text-gray-300 border-t border-gray-700">
    <!-- On medium screens and up: single row layout -->
    <div class="hidden sm:flex h-6 items-center justify-between px-4">
        <div class="flex items-center">
            <x-a href="https://gitlab.com/adam.blakey/adam-website-2024" target="_blank" class="hover:text-white flex items-center">
                {!! $si('gitlab', 'inline mr-1', 12) !!} View Source
            </x-a>
            <div class="mx-4 h-3 border-r border-gray-700"></div>
            <span>v{{ $taggedVersion ?? '1.0.0' }}</span>
        </div>
        <div class="flex items-center">
            <span>{{ $latestTrack ? 'Currently listening to: ' . $latestTrack->track_name . ' by ' . $latestTrack->artist_name : 'Not currently listening to music' }}</span>
            <div class="mx-4 h-3 border-r border-gray-700"></div>
            <span><x-a href="{{ $programmingTimeUrl }}">{{ $programmingTime ?? '0 hrs' }} programming</x-a></span>
        </div>
    </div>

    <!-- On small screens: two row layout -->
    <div class="sm:hidden">
        <!-- Top row -->
        <div class="flex items-center justify-between px-4 h-6">
            <div class="flex items-center">
                <x-a href="https://gitlab.com/adam.blakey/adam-website-2024" target="_blank" class="hover:text-white flex items-center">
                    {!! $si('gitlab', 'inline mr-1', 12) !!} View Source
                </x-a>
            </div>
            <div>
                <span>v{{ $taggedVersion ?? '1.0.0' }}</span>
            </div>
        </div>
        <!-- Bottom row -->
        <div class="flex items-center justify-between px-4 h-6 border-t border-gray-700">
            <div>
                <span>{{ $latestTrack ? 'Listening to: ' . $latestTrack->track_name . ' by ' . $latestTrack->artist_name : 'No music playing' }}</span>
            </div>
            <div>
                <span><x-a href="{{ $programmingTimeUrl }}">{{ $programmingTime ?? '0 hrs' }} programming</x-a></span>
            </div>
        </div>
    </div>
</div>
