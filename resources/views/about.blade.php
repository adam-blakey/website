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
        <x-card-grid-item :colSpan="1" title="Awards & Honours">
            <ul class="space-y-6 mt-5">
                @foreach($layoutAwards as $award)
                    <li class="border-b border-gray-200 dark:border-gray-700 pb-6 last:border-b-0">
                        @if($award->image)
                            <div class="relative rounded-lg overflow-hidden mb-3">
                                <!-- Image as background -->
                                <div class="absolute inset-0">
                                    <x-img :src="$award->image" sizes="(min-width: 1024px) 33vw, 100vw" alt="{{ $award->title }}"
                                         class="w-full h-full object-cover opacity-40" />
                                </div>
                                <!-- Dark overlay to improve text visibility -->
                                <div class="absolute inset-0 bg-black bg-opacity-30"></div>

                                <!-- Only title and date on top of the image -->
                                <div class="relative z-10 p-4">
                                    <div class="flex items-center mb-2">
                                        <h3 class="text-xl font-semibold text-white">{{ $award->title }}</h3>
                                    </div>
                                    <time class="block mb-2 text-sm font-normal leading-none text-gray-200">
                                        {{ $award->getDateString() }}
                                    </time>
                                </div>
                            </div>

                            <!-- Description and link outside the image -->
                            <div class="px-1">
                                <p class="mb-3 text-base font-normal text-gray-500 dark:text-gray-400">
                                    {{ $award->description }}
                                </p>
                                @if($award->link)
                                    <x-a href="{{ $award->link }}" class="inline-flex items-center px-4 py-2 mb-1 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-100 focus:text-blue-700" :noUnderline="true">
                                        Learn more
                                    </x-a>
                                @endif
                            </div>
                        @else
                            <!-- Fallback for awards without images -->
                            <div class="mb-2">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $award->title }}</h3>
                            </div>
                            <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">
                                {{ $award->getDateString() }}
                            </time>
                            <p class="mb-3 text-base font-normal text-gray-500 dark:text-gray-400">
                                {{ $award->description }}
                            </p>
                            @if($award->link)
                                <x-a href="{{ $award->link }}" class="inline-flex items-center px-4 py-2 mb-1 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-100 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700" :noUnderline="true">
                                    Learn more
                                </x-a>
                            @endif
                        @endif
                    </li>
                @endforeach
            </ul>
        </x-card-grid-item>
        <x-card-grid-item :colSpan="1" title="Talks & Presentations">
            <ul class="space-y-6 mt-5">
                @foreach($layoutTalks as $talk)
                    <li class="border-b border-gray-200 dark:border-gray-700 pb-6 last:border-b-0">
                        <div class="flex items-center mb-2">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $talk->title }}</h3>
                        </div>
                        <x-img :src="$talk->image" sizes="(min-width: 1024px) 33vw, 100vw" alt="{{ $talk->title }}" class="mb-3 rounded-lg max-w-full h-auto" />
                        <p class="mb-3 text-base font-normal text-gray-500 dark:text-gray-400">
                            {{ $talk->description }}
                        </p>
                        @if ($talk->rsvp_link or ($talk->rsvp_expiry and $talk->rsvp_expiry->isFuture()))
                            <x-a href="{{ $talk->rsvp_link }}" class="inline-flex items-center px-4 py-2 mb-1 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-100 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700" :noUnderline="true">
                                RSVP
                            </x-a>
                        @endif
                        @if ($talk->recording_link)
                            <x-a href="{{ $talk->recording_link }}" class="inline-flex items-center px-4 py-2 mb-1 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-100 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700" :noUnderline="true">
                                View recording
                            </x-a>
                        @endif
                        @if ($talk->pdf_link)
                            <x-a href="{{ $talk->pdf_link }}" class="inline-flex items-center px-4 py-2 mb-1 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-100 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700" :noUnderline="true">
                                View presentation (pdf)
                            </x-a>
                        @endif
                        @if ($talk->pptx_link)
                            <x-a href="{{ $talk->pptx_link }}" class="inline-flex items-center px-4 py-2 mb-1 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-100 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700" :noUnderline="true">
                                View presentation (pptx)
                            </x-a>
                        @endif
                        @if ($talk->demo_link)
                            <x-a href="{{ $talk->demo_link }}" class="inline-flex items-center px-4 py-2 mb-1 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-100 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700" :noUnderline="true">
                                View demo
                            </x-a>
                        @endif
                        @if ($talk->code_link)
                            <x-a href="{{ $talk->code_link }}" class="inline-flex items-center px-4 py-2 mb-1 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-100 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700" :noUnderline="true">
                                View code
                            </x-a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </x-card-grid-item>
    </x-card-grid>
</x-layout>
