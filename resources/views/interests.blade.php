@props([])

<x-layout>
    <div class="mb-2">
        <x-card-grid :columns="3">
            <x-card-grid-item :colSpan="3" title="My Interests">
                <p class="mb-4">My three main interests are: music, Scouts, and software development. Each of these interests have played a significant role in shaping who I am today.</p>
            </x-card-grid-item>

            <x-card-grid-item :colSpan="1" title="Music" icon="music-note-outline">
                <x-a view="interests.music" :noUnderline="true" class="block">
                    <x-img src="resources/img/woodwind-concert.jpg" sizes="(min-width: 1024px) 33vw, 100vw" caption="Adam and fellow woodwind players holding their instruments after a concert in a church with ESO" class="rounded-lg mb-3 w-full aspect-video object-cover" />
                </x-a>
                <div class="mb-2">I'm a keen clarinettist, playing in multiple ensembles in Nottingham. Music has been a significant part of my life since I picked up the clarinet at age 8.
                </div>
                <x-a view="interests.music" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-100 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700" :nounderline="true">
                    Learn more about me and my clarinet
                    {!! $mdi('arrow-right', 'pl-1') !!}
                </x-a>
            </x-card-grid-item>

            <x-card-grid-item :colSpan="1" title="Scouts" icon="campfire">
                <x-a view="interests.scouts" :noUnderline="true" class="block">
                    <x-img src="resources/img/scouts-group.jpg" sizes="(min-width: 1024px) 33vw, 100vw" caption="Photo of Adam smiling at a Scout awards ceremony" class="rounded-lg mb-3 w-full aspect-video object-cover" />
                </x-a>
                <div class="mb-2">I've been volunteering with Scouts since 2016 through various different roles. Scouting has given me so many amazing experiences throughout my life.
                </div>
                <x-a view="interests.scouts" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-100 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700" :nounderline="true">
                    Overview of my time in Scouting
                    {!! $mdi('arrow-right', 'pl-1') !!}
                </x-a>
            </x-card-grid-item>

            <x-card-grid-item :colSpan="1" title="Software Development" icon="code-tags">
                <x-a view="interests.software" :noUnderline="true" class="block">
                    <x-img src="resources/img/interests-software.jpg" sizes="(min-width: 1024px) 33vw, 100vw" caption="Adam giving a software talk on Blazor in January 2026" class="rounded-lg mb-3 w-full aspect-video object-cover" />
                </x-a>
                <div class="mb-2">Software development is both my profession and hobby. I work in a variety of programming languages, and I enjoy creating websites and bespoke software in my spare time.
                </div>
                <x-a view="interests.software" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-100 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700" :nounderline="true">
                    Browse my software projects
                    {!! $mdi('arrow-right', 'pl-1') !!}
                </x-a>
            </x-card-grid-item>
        </x-card-grid>
    </div>
</x-layout>
