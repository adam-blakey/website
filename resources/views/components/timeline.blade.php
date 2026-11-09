@props(['entries'])

<ol class="relative border-s border-gray-200 dark:border-gray-700 mt-5">
    @foreach($entries as $timelineEntry)
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
