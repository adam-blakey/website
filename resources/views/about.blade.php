@props([])

<x-layout>
    <x-card-grid :columns="3">
        <x-card-grid-item :colSpan="3" title="Adam Blakey">
            <div class="flex flex-col md:flex-row gap-6">
                <x-img src="resources/img/about-portrait.jpg" sizes="(min-width: 768px) 256px, 100vw" alt="Photo of Adam" class="rounded-lg shadow-lg w-full md:w-64 md:h-64 object-cover shrink-0" />
                <div>
                    <div class="mb-2">Hi there, I'm Adam 👋</div>
                    <div class="mb-2">
                        💼 I'm a Software Engineer at Capital One, mostly working on our backend Java services for customer offers. Before that, I worked at Savoy Systems on projects ranging from data visualisation and automated testing, all the way to a larger project where we were porting a legacy application to a new language. I also introduced Git and GitHub as a way to project manage our work more effectively.
                    </div>
                    <div class="mb-2">
                        🧑‍🔬 In 2024, I completed Mathematics PhD at the University of Nottingham, where I developed scientific software to simulate blood flow and nutrient transport within the human placenta.
                    </div>
                    <div class="mb-2">
                        🧑‍💻 Most of what I do is develop software, and I predominantly create websites and bespoke software in PHP/SQL/JS/HTML/CSS in my spare time. I have the most experience with the interesting cocktail of C#, Delphi, Fortran, Java, PHP, and Python. I also have experience teaching a first-year undergraduate Python course at the University of Nottingham, as well as mentoring newbies in tech and giving tech talks.
                    </div>
                    <div class="mb-2">
                        ✅ I have spent many years as a trustee of 3 local charities, served on committees of 3 more organisations, and still volunteer every week with Scouts since 2015. I also provide website and technical support to several local organisations (such as <x-a href="https://2ndbeeston.org.uk">2nd Beeston</x-a>, <x-a href="https://swnott-scouts.org.uk">SW Notts Scouts</x-a>, and <x-a href="https://nsw.org.uk">Nottingham Symphonic Winds</x-a>).
                    </div>
                </div>
            </div>
        </x-card-grid-item>
        <x-card-grid-item :colSpan="1" title="Latest award" view="about.awards">
            <ul class="space-y-6 mt-5">
                <x-award :award="$layoutAwards->first()" />
            </ul>
            <x-a view="about.awards" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-100 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700" :noUnderline="true">
                All awards & honours
                {!! $mdi('arrow-right', 'pl-1') !!}
            </x-a>
        </x-card-grid-item>
        <x-card-grid-item :colSpan="1" title="Latest qualification" view="about.qualifications">
            <ul class="space-y-6 mt-5">
                <x-qualification :qualification="$layoutQualifications[0]" />
            </ul>
            <x-a view="about.qualifications" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-100 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700" :noUnderline="true">
                Qualifications & certifications
                {!! $mdi('arrow-right', 'pl-1') !!}
            </x-a>
        </x-card-grid-item>
        <x-card-grid-item :colSpan="1" title="Latest talk" view="about.talks">
            <ul class="space-y-6 mt-5">
                <x-talk :talk="$layoutTalks->first()" />
            </ul>
            <x-a view="about.talks" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-100 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700" :noUnderline="true">
                All talks & presentations
                {!! $mdi('arrow-right', 'pl-1') !!}
            </x-a>
        </x-card-grid-item>
        <x-card-grid-item :colSpan="3" title="Recent-ish timeline" view="about.timeline">
            <x-timeline :entries="array_slice($layoutTimelineEntries, 0, 3)" />
            <x-a view="about.timeline" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-100 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700" :noUnderline="true">
                Full timeline
                {!! $mdi('arrow-right', 'pl-1') !!}
            </x-a>
        </x-card-grid-item>
    </x-card-grid>
</x-layout>
