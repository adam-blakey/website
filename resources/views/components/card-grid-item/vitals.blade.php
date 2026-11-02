@php
    $secondsToday = now()->secondsSinceMidnight();
    $minutesToday = $secondsToday / 60;
    $earthOrbitalKmPerSecond = 29.78;
@endphp

<x-card-grid-item title="Adam, right now (roughly)">
    <p class="flex items-center gap-2 text-2xl font-semibold text-gray-200">{!! $mdi('water-percent', 'text-red-400 motion-safe:animate-pulse', 28) !!} {{ rand(96, 99) }}% SpO<sub>2</sub></p>
    <div class="mt-4">
        <p class="text-sm text-gray-400">{!! $mdi('lungs', 'inline mr-1', 16) !!} {{ number_format($minutesToday * 15) }} breaths taken today</p>
        <p class="text-sm text-gray-400">{!! $mdi('heart-pulse', 'inline mr-1', 16) !!} {{ number_format($minutesToday * 70) }} heartbeats today</p>
        <p class="text-sm text-gray-400">{!! $mdi('orbit', 'inline mr-1', 16) !!} {{ number_format($secondsToday * $earthOrbitalKmPerSecond) }} km travelled around the Sun today</p>
        <p class="mt-2 text-xs text-gray-500">Estimated from typical resting rates, not a pulse oximeter.</p>
    </div>
</x-card-grid-item>
