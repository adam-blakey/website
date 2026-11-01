@props([
    'latestTrack',
    'randomPost',
    'nextConcert',
])

<x-layout>
    <x-card-grid :columns="3">
        <x-card-grid-item :colSpan="3" title="In short">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex items-center">
                    <x-img class="my-4 md:h-32 md:w-32 h-24 w-24 rounded-lg shadow-lg" src="resources/img/adam.jpg" sizes="(min-width: 768px) 128px, 96px" alt="Adam's face" />
                    <div>
                        <p class="ml-4 md:w-64">Hi there, I'm Adam. In short, I'm a software developer, clarinettist, Scout, and fan of all things maths.</p>
                        <div class="ml-4 flex gap-2 mt-2 flex-wrap">
                            @foreach (config('socials.platforms') as $platform)
                                <x-a href="{{ $platform['url'] }}" class="text-gray-500 hover:text-white transition-colors" title="{{ $platform['name'] }}" :noUnderline="true" :noExternalIcon="true" aria-label="Adam's profile on {{ $platform['name'] }}">
                                    @if ($platform['use-simple-icons'])
                                        {!! $si($platform['icon'], 'inline mr-1 mt-1', 24) !!}
                                    @else
                                        {!! $mdi($platform['icon'], 'inline', 32) !!}
                                    @endif
                                </x-a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="flex mt-4">
                    <div class="flex flex-col gap-2">
                        <x-a view="about">
                            <h3>About me</h3>
                        </x-a>
                        <x-a view="interests">
                            <h3>My Interests</h3>
                        </x-a>
                        <x-a view="now">
                            <h3>Right now</h3>
                        </x-a>
                        <x-a view="contact">
                            <h3>Contact me</h3>
                        </x-a>
                    </div>
                </div>
            </div>
        </x-card-grid-item>

        <x-card-grid-item.listening :track="$latestTrack" />
        <x-card-grid-item.blog-post :post="$randomPost" />
        <x-card-grid-item.concert :concert="$nextConcert" title="Next concert" />
    </x-card-grid>
</x-layout>
