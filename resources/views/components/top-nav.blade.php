@props(['topMenuEntries'])

@php
    use App\Helpers\Helpers;
@endphp

<header class="flex h-10 shrink-0 items-center justify-between gap-2 px-2 text-[13px] text-gray-300">
    <div class="flex items-center gap-1 min-w-0">
        <a class="flex items-center gap-2 rounded-md px-1.5 py-1 font-semibold text-gray-100 hover:bg-gray-600/60 transition-colors" href="/" aria-label="AB adam-blakey home">
            <span class="grid place-items-center size-5 rounded bg-gradient-to-br from-blue-500 to-violet-500 text-[10px] font-bold text-white">AB</span>
            <span class="hidden sm:inline">adam-blakey</span>
            {!! $mdi('chevron-down', 'hidden sm:inline text-gray-500', 14) !!}
        </a>

        <div class="flex items-center p-0">
            @foreach ($topMenuEntries as $entry)
                <div class="group relative cursor-pointer">
                    <button class="select-none rounded-md px-2 py-1 group-hover:bg-gray-600/60 group-hover:text-white transition-colors">{{ $entry->name }}</button>
                    <div class="absolute left-0 top-full hidden pt-1 w-52 group-hover:block z-20">
                        <ul class="p-1 rounded-lg border border-gray-600 bg-gray-700 shadow-2xl shadow-black/50">
                            @foreach ($entry->subEntries as $item)
                                <li>
                                    @if ($item->enabled)
                                        <a class="flex items-center gap-2 rounded-md px-3 py-1.5 text-gray-300 hover:bg-blue-600 hover:text-white select-none" href="{{ url($item->url) }}" {{ Helpers::isExternalUrl($item->url) ? "target='_blank'" : '' }}>
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

        <x-a href="https://github.com/adam-blakey/adam-website-2024" class="hidden md:flex items-center gap-1.5 rounded-md px-2 py-1 hover:bg-gray-600/60 hover:text-white transition-colors" :noUnderline="true" :noExternalIcon="true" title="View source on GitHub" aria-label="View source on GitHub">
            {!! $mdi('source-branch', 'text-gray-400', 15) !!} main
        </x-a>
    </div>

    <div class="flex items-center gap-1.5 shrink-0">
        <a class="flex items-center gap-1.5 rounded-md px-2 py-1 hover:bg-gray-600/60 hover:text-white transition-colors" href="{{ route('contact') }}" title="Run: Say hello">
            <span class="hidden sm:inline text-gray-400">Say hello</span>
            {!! $mdi('play', 'text-emerald-400', 18) !!}
        </a>
        <a class="rounded-full ring-1 ring-gray-600 hover:ring-blue-400 transition" href="{{ route('about') }}" aria-label="About Adam">
            <x-img class="size-6 rounded-full object-cover" src="resources/img/adam.jpg" sizes="24px" alt="" />
        </a>
    </div>
</header>
