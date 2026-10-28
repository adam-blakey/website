@props(['topMenuEntries'])

@php
    use App\Helpers\Helpers;
@endphp

<div class="flex h-10 items-center justify-between bg-gray-800 px-4 text-sm">
    <div class="flex items-center">
        <a class="ml-2 mr-3" href="/" aria-label="Adam's website logo (a capital letter 'A' in a rounded box)">{!! $mdi('alpha-a-box-outline', 'inline hover:text-gray-200', 24) !!}</a>

        <div class="ml-1 flex items-center p-0">
            @foreach ($topMenuEntries as $entry)
                <div class="group relative cursor-pointer p-2 hover:bg-gray-700">
                    <button class="select-none">{{ $entry->name }}</button>
                    <div class="absolute left-0 mt-2 hidden w-48 rounded-md border border-gray-700 bg-gray-900 shadow-lg group-hover:block z-10">
                        <ul class="py-1">
                            @foreach ($entry->subEntries as $item)
                                <li>
                                    @if ($item->enabled)
                                        <a class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white select-none" href="{{ url($item->url) }}" {{ Helpers::isExternalUrl($item->url) ? "target='_blank'" : '' }}>
                                            @if ($item->use_simple_icons)
                                                {!! $si($item->icon, 'inline mr-0.5', 12) !!}
                                            @else
                                                {!! $mdi($item->icon, 'inline', 16) !!}
                                            @endif
                                            {{ $item->name }}
                                        </a>
                                    @else
                                        <span class="block px-4 py-2 text-sm text-gray-700 cursor-not-allowed select-none" aria-disabled="true">
                                            @if ($item->use_simple_icons)
                                                {!! $si($item->icon, 'inline mr-0.5', 12) !!}
                                            @else
                                                {!! $mdi($item->icon, 'inline', 16) !!}
                                            @endif
                                            {{ $item->name }}
                                        </span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="text-gray-400">Adam's website</div>
</div>
