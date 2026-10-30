@props([])

<x-layout>
    <h3><a class="hover:underline" href="#"><span class="text-gray-500">###</span> Talks & Presentations</a></h3>
    <x-card-grid :columns="1">
        <x-card-grid-item :colSpan="1" title="Talks & Presentations">
            <ul class="grid gap-6 mt-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach($layoutTalks as $talk)
                    <x-talk :talk="$talk" />
                @endforeach
            </ul>
        </x-card-grid-item>
    </x-card-grid>
</x-layout>
