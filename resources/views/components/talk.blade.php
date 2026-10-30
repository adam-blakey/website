@props(['talk'])

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
