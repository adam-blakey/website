@props([])

<x-layout>
    <h3><a class="hover:underline" href="#"><span class="text-gray-500">###</span> Awards & Honours</a></h3>
    <x-card-grid :columns="1">
        <x-card-grid-item :colSpan="1" title="Awards & Honours">
            <ul class="grid gap-6 mt-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach($layoutAwards as $award)
                    <x-award :award="$award" />
                @endforeach
            </ul>
        </x-card-grid-item>
    </x-card-grid>
</x-layout>
