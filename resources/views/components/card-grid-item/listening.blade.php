@php
    use Illuminate\Support\Carbon;
    use Carbon\CarbonInterface;
@endphp

@props([
    'track'
])

@if (is_null($track))
    <x-card-grid-item title="Adam hasn't listened to anything recently ☹️">
        <div class="mt-4">
            <p class="text-sm text-gray-500">
                @if(config('music-services.lastfm.enabled'))
                    {!! $si('lastdotfm', 'inline mr-1', 16) !!} View Adam's <x-a href="https://www.last.fm/user/{{ config('music-services.lastfm.username') }}" class="text-blue-500">Last.fm Profile</x-a>
                @elseif(config('music-services.listenbrainz.enabled'))
                    {!! $si('musicbrainz', 'inline mr-1', 16) !!} View Adam's <x-a href="https://listenbrainz.org/user/{{ config('music-services.listenbrainz.username') }}" class="text-blue-500">ListenBrainz Profile</x-a>
                @endif
            </p>
        </div>
    </x-card-grid-item>
@else
    <x-card-grid-item title="Adam {{ $track->played_at->diffInMinutes(Carbon::now()) > 5 ? 'was' : 'is' }} listening to...">
        <div class="flex items-center space-x-4">
            <div class="h-20 w-20 flex-shrink-0 rounded-lg bg-gray-700">
                <x-a href="{{ $track->album_url  }}" :noUnderline="true" :noExternalIcon="true">
                    <img class="h-full w-full rounded-lg object-cover"
                         src="{{ $track->artwork_url ?? Vite::asset('resources/img/ListenBrainz_logo_square.png') }}"
                         alt="Album cover for {{ $track->track_name }} by {{ $track->artist_name }}" />
                </x-a>
            </div>
            <div class="flex-1">
                <h4 class="font-semibold text-gray-200"><x-a href="{{ $track->track_url }}">{{ $track->track_name }}</x-a></h4>
                <p class="text-gray-300"><x-a href="{{ $track->artist_url }}">{{ $track->artist_name }}</x-a></p>
                <p class="text-sm text-gray-400"><x-a href="{{ $track->album_url }}">{{ $track->album_name }}</x-a></p>
            </div>
        </div>

        <div class="mt-4">
            <p class="text-sm text-gray-400">{!! $mdi('music-box', 'inline mr-1', 16) !!} Played {{ ($track->played_at->diffForHumans(Carbon::now(), CarbonInterface::DIFF_RELATIVE_TO_NOW)) }}</p>
                @if(config('music-services.lastfm.enabled'))
                    <p class="text-sm text-gray-400">
                        {!! $si('lastdotfm', 'inline mr-1', 16) !!} View Adam's <x-a href="https://www.last.fm/user/{{ config('music-services.lastfm.username') }}" class="text-blue-500">Last.fm Profile</x-a>
                    </p>
                @endif
                @if(config('music-services.listenbrainz.enabled'))
                    <p class="text-sm text-gray-400">
                        {!! $si('musicbrainz', 'inline mr-1', 16) !!} View Adam's <x-a href="https://listenbrainz.org/user/{{ config('music-services.listenbrainz.username') }}" class="text-blue-500">ListenBrainz Profile</x-a>
                    </p>
                @endif
        </div>
    </x-card-grid-item>
@endif
