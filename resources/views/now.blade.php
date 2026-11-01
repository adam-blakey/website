@props([
    'latestTrack',
    'programmingTime',
])

<x-layout>
    <x-card-grid :columns="3">
        <x-card-grid-item.listening :track="$latestTrack" />
        <x-card-grid-item title="Adam has programmed today...">
            <p class="flex items-center gap-2 text-2xl font-semibold text-gray-200">{!! $mdi('clock-outline', $programmingTime?->total_seconds > 0 ? 'text-blue-400' : 'text-gray-400', 28) !!} {{ $programmingTime?->time_text ?? '0 hrs' }}</p>
            <div class="mt-4">
                @if ($programmingTime)
                    <p class="text-sm text-gray-400">{!! $mdi('refresh', 'inline mr-1', 16) !!} Updated {{ $programmingTime->fetched_at->diffForHumans() }}</p>
                @endif
                @if ($layoutProgrammingTimeUrl)
                    <p class="text-sm text-gray-400">{!! $mdi('trophy-outline', 'inline mr-1', 16) !!} View the <x-a href="{{ $layoutProgrammingTimeUrl }}" class="text-blue-400">Wakapi leaderboard</x-a></p>
                @endif
            </div>
        </x-card-grid-item>
    </x-card-grid>
</x-layout>
