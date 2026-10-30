@props(['award'])

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
