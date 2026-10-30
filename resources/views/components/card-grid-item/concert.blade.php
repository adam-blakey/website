@php
    use Illuminate\Support\Str;
@endphp

@props([
    'concert',
    'color' => 'bg-gray-800',
    'title' => null
])

@php
    $title_provided = true;

    if (is_null($title)) {
        if (is_null($concert)) {
            $title = 'No upcoming concerts';
            $title_provided = false;
        }
        else {
            $title = '';
            $title_provided = false;
        }
    }
@endphp

<x-card-grid-item title="{{ $title }}" :color="$color">
    @if (is_null($concert))
        <div class="mt-4">
            <p class="text-sm text-gray-400">
                Check back later for upcoming concerts.
            </p>
        </div>
    @else
        <div class="flex flex-col md:flex-row space-x-4">
            <div class="flex-1">
                @if ($title_provided)
                    <h4 class="text-2xl font-bold"><x-a :href="$concert->event_url">{{ $concert->name }}</x-a></h4>
                @else
                    <h3 class="text-white"><x-a :href="$concert->event_url">{{ $concert->name }}</x-a></h3>
                @endif
                <p class="text-sm text-gray-400">
                    <x-a :href="$concert->ensemble_website" class="inline-flex items-center gap-1">
                        @if ($concert->ensemble_icon)
                            <img src="{{ $concert->ensemble_icon }}" alt="" class="w-4 h-4 rounded-sm" />
                        @endif
                        {{ $concert->ensemble }}
                    </x-a>
                </p>
                <time class="text-sm mt-3">
                    <span class="font-medium">{{ $concert->formatted_start_date }}</span> at
                    <span class="font-medium">{{ $concert->formatted_time_range }}</span>
                </time>
                <p class="text-sm mt-1"><x-a :href="$concert->map_url">{{ $concert->location }}</x-a></p>
                <div class="prose dark:prose-invert max-w-none my-2 text-sm">
                    {{ Str::limit(html_entity_decode(strip_tags($concert->description), ENT_QUOTES | ENT_HTML5), 800) }}
                </div>
            </div>
            @if($concert->poster_url)
                <div class="flex-1">
                    <x-a :href="$concert->ticket_url" :noExternalIcon="true">
                        <img class="w-full h-auto rounded-lg object-contain"
                             src="{{ $concert->poster_url }}"
                             alt="Poster for {{ $concert->name }}" />
                    </x-a>
                </div>
            @endif
        </div>

        <div class="mt-4">
            @if($concert->ticket_url)
                <x-a :href="$concert->ticket_url" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-100 bg-gray-700 border border-gray-600 rounded-lg hover:bg-gray-600 hover:text-white focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-700 focus:text-white" :noUnderline="true">
                    Get tickets
                </x-a>
            @endif
            @unless (request()->routeIs('interests.music'))
                <p class="text-sm text-gray-500 mt-2">
                    {!! $mdi('music', 'inline mr-1', 16) !!} <x-a view="interests.music">View all concerts</x-a>
                </p>
            @endunless
        </div>
   @endif
</x-card-grid-item>
