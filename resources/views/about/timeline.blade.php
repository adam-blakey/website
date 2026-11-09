@props([])

<x-layout>
    <h3><a class="hover:underline" href="#"><span class="text-gray-500">###</span> Timeline</a></h3>
    <x-card-grid :columns="1">
        <x-card-grid-item :colSpan="1" title="Timeline">
            <x-timeline :entries="$layoutTimelineEntries" />
        </x-card-grid-item>
    </x-card-grid>
</x-layout>
