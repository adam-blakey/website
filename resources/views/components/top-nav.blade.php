@props(['topMenuEntries'])

@php
    use App\Helpers\Helpers;
@endphp

<div class="relative flex h-10 items-center justify-between bg-gray-950 border-b border-white/5 px-2 font-mono text-[13px] text-gray-400">
    <div class="flex items-center">
        <a class="ml-1 mr-2 text-blue-400 hover:text-blue-300 transition-colors" href="/" aria-label="Adam's website logo (a capital letter 'A' in a rounded box)">{!! $mdi('alpha-a-box-outline', 'inline', 22) !!}</a>

        <div class="flex items-center p-0">
            @foreach ($topMenuEntries as $entry)
                <div class="group relative cursor-pointer">
                    <button class="select-none rounded-md px-2.5 py-1 group-hover:bg-white/5 group-hover:text-white transition-colors">{{ $entry->name }}</button>
                    <div class="absolute left-0 top-full hidden pt-1 w-52 group-hover:block z-20">
                        <ul class="p-1 rounded-lg border border-white/10 bg-gray-850/95 backdrop-blur shadow-2xl shadow-black/50">
                            @foreach ($entry->subEntries as $item)
                                <li>
                                    @if ($item->enabled)
                                        <a class="flex items-center gap-2 rounded-md px-3 py-1.5 text-gray-300 hover:bg-blue-500/20 hover:text-white select-none" href="{{ url($item->url) }}" {{ Helpers::isExternalUrl($item->url) ? "target='_blank'" : '' }}>
                                            @if ($item->use_simple_icons)
                                                {!! $si($item->icon, 'inline mr-0.5', 12) !!}
                                            @else
                                                {!! $mdi($item->icon, 'inline', 16) !!}
                                            @endif
                                            {{ $item->name }}
                                        </a>
                                    @else
                                        <span class="flex items-center gap-2 rounded-md px-3 py-1.5 text-gray-600 cursor-not-allowed select-none" aria-disabled="true">
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
    <div class="absolute left-1/2 -translate-x-1/2 hidden sm:flex items-center gap-2 rounded-md border border-white/5 bg-white/[0.03] px-24 py-0.5 text-xs text-gray-500">
        {!! $mdi('code-braces', 'inline', 14) !!} Adam's website
    </div>
    <div class="sm:hidden text-xs text-gray-500">Adam's website</div>
</div>
