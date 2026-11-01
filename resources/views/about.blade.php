@props([])

<x-layout>
    <x-card-grid :columns="3">
        <x-card-grid-item :colSpan="3" title="Adam Blakey">
            <div class="mb-2">Hi there, I'm Adam 👋</div>
            <div class="mb-2">
                💼 I'm a Software Engineer at Capital One, mostly working on our backend Java services for customer offers. Before that, I worked at Savoy Systems on projects ranging from data visualisation and automated testing, all the way to a larger project where we were porting a legacy application to a new language.
            </div>
            <div class="mb-2">
                🧑‍🔬 In 2024, I completed Mathematics PhD at the University of Nottingham, where I developed scientific software to simulate blood flow and nutrient transport within the human placenta.
            </div>
            <div class="mb-2">
                🧑‍💻 Most of what I do is develop software, and I predominantly create websites and bespoke software in PHP/SQL/JS/HTML/CSS in my spare time. I have the most experience with the interesting cocktail of C#, Delphi, Fortran, Java, PHP, and Python. I also have experience teaching a first-year undergraduate Python course at the University of Nottingham, as well as mentoring newbies in tech and giving tech talks.
            </div>
            <div class="mb-2">
                ✅ I have spent many years as a trustee of 3 local charities, served on committees of 3 more organisations, and still volunteer every week with Scouts since 2015. I also provide website and technical support to several local organisations.
            </div>
        </x-card-grid-item>
        <x-card-grid-item :colSpan="1" title="Recent-ish timeline">
            <ol class="relative border-s border-gray-200 dark:border-gray-700 mt-5">
                @foreach($layoutTimelineEntries as $timelineEntry)
                    <li class="mb-10 ms-6">
                        <x-a href="{{ $timelineEntry->company_url }}" class="absolute flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full -start-4 ring-4 ring-white dark:ring-gray-900 dark:bg-blue-900 p-1" :noExternalIcon="true">
                            {!! \Mdi\Mdi::mdi($timelineEntry->icon) !!}
                        </x-a>
                        <x-a href="{{ $timelineEntry->company_url }}" class="flex items-center mb-1 text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ $timelineEntry->title }}
                        </x-a>
                        <x-a href="{{ $timelineEntry->company_url }}" class="flex items-center mb-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ $timelineEntry->company }}
                        </x-a>
                        <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">
                            {{ $timelineEntry->getDateRangeString() }}
                        </time>
                        <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">
                            {{ $timelineEntry->description }}
                        </p>
                        @foreach($timelineEntry->buttons as $button)
                            @php($url = $button["url"])
                            <x-a href="{{ $url }}" class="inline-flex items-center px-4 py-2 mb-1 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-100 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700" :noUnderline="true">
                                {{ $button["text"] }}
                            </x-a>
                        @endforeach
                    </li>
                @endforeach
            </ol>
        </x-card-grid-item>
        <x-card-grid-item :colSpan="1" title="Latest award">
            <ul class="space-y-6 mt-5">
                <x-award :award="$layoutAwards->first()" />
            </ul>
            <x-a view="about.awards" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-100 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700" :noUnderline="true">
                All awards & honours
                {!! $mdi('arrow-right', 'pl-1') !!}
            </x-a>
        </x-card-grid-item>
        <x-card-grid-item :colSpan="1" title="Latest talk">
            <ul class="space-y-6 mt-5">
                <x-talk :talk="$layoutTalks->first()" />
            </ul>
            <x-a view="about.talks" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-100 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700" :noUnderline="true">
                All talks & presentations
                {!! $mdi('arrow-right', 'pl-1') !!}
            </x-a>
        </x-card-grid-item>
    </x-card-grid>
</x-layout>
