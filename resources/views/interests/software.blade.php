@php
    $skills = [
        ['icon' => 'dotnet', 'name' => 'C#', 'use' => 'Backend server and Blazor applications'],
        ['icon' => 'delphi', 'name' => 'Delphi', 'use' => 'Backend server code and legacy UI desktop applications'],
        ['icon' => 'fortran', 'name' => 'Fortran', 'use' => 'High-performance scientific computation applications'],
        ['icon' => 'javascript', 'name' => 'HTML, JavaScript, and CSS', 'use' => 'Front-end web development'],
        ['icon' => 'openjdk', 'name' => 'Java', 'use' => 'Backend microservices'],
        ['icon' => 'laravel', 'name' => 'PHP (and Laravel)', 'use' => 'Web development and server-side applications'],
        ['icon' => 'python', 'name' => 'Python', 'use' => 'Data analysis, scientific computing, and teaching'],
        ['mdi' => 'database', 'name' => 'SQL', 'use' => 'Database design and management'],
    ];
    $projects = [
        ['name' => 'This website', 'image' => 'project-website.jpg', 'description' => 'Is that a text editor? Is that an IDE? Nope, it\'s just this website', 'stack' => ['Laravel', 'Tailwind CSS'], 'url' => null, 'source' => 'https://github.com/adam-blakey/website'],
        ['name' => 'Readererer', 'image' => 'project-readererer.jpg', 'description' => 'Software for manging attendance and sheet music for amateur music ensembles', 'stack' => ['Laravel', 'Tabler'], 'url' => 'https://readererer.blakey.family', 'source' => 'https://github.com/adam-blakey/readererer'],
        ['name' => '2nd Beeston Sea Scouts', 'image' => 'project-2nd-beeston.jpg', 'description' => 'WordPress website which hosts important information as well as some of our volunteering vacancies', 'stack' => ['WordPress'], 'url' => 'https://2ndbeeston.org.uk', 'source' => null],
        ['name' => 'SW Notts Scouts', 'image' => 'project-swnotts.jpg', 'description' => 'WordPress website with various Scouting plugins for showing our groups and getting in touch', 'stack' => ['WordPress'], 'url' => 'https://swnotts-scouts.org.uk', 'source' => null],
        ['name' => 'Keiron Anderson', 'image' => 'project-keiron-anderson.jpg', 'description' => 'A website that allows Keiron to post blog posts and advertise published music', 'stack' => ['WordPress'], 'url' => 'https://keironanderson.co.uk', 'source' => null],
        ['name' => 'Nottingham Symphonic Winds', 'image' => 'project-nsw.jpg', 'description' => 'WordPress website that shows upcoming concerts', 'stack' => ['WordPress'], 'url' => 'https://nsw.org.uk', 'source' => null],
        ['name' => 'Nottingham Digital', 'image' => 'project-nottingham-digital.jpg', 'description' => 'A site that lists upcoming tech meetups in Nottingham', 'stack' => ['WordPress'], 'url' => 'https://nottingham.digital', 'source' => null],
    ];
    $buttonClass = 'inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-100 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700';
@endphp

<x-layout>
    <h3><a class="hover:underline" href="#"><span class="text-gray-500">###</span> Software</a></h3>
    <div class="mb-2">
        <x-card-grid :columns="3">
            <x-card-grid-item :colSpan="1" title="My software development experience">
                <div class="mb-4">
                    <p class="mb-2">I'm currently a Software Engineer at Capital One, where I'm working in one of the Site Reliability Engineer teams (SRE) as of October 2026. We look after the services that support new customer applications.</p>
                    <p class="mb-2">Before starting in this team, I worked in another team working on our Java microservices that manage Balance Transfers and Credit Limit Increases, as well as updates to our mobile apps (predominantly Swift on iOS).</p>
                </div>
                <x-a view="about" :class="$buttonClass" :noUnderline="true">
                    See my full timeline
                    {!! $mdi('arrow-right', 'pl-1') !!}
                </x-a>
            </x-card-grid-item>
            <x-card-grid-item :colSpan="2" title="Technical skills">
                <p class="mb-4">Throughout my professional and academic career, I've developed expertise in the following cocktail of programming languages and technologies:</p>
                <ul class="grid gap-4 sm:grid-cols-2 mb-4">
                    @foreach ($skills as $skill)
                        <li class="flex items-start gap-3">
                            <span class="shrink-0 grid place-items-center size-10 rounded-lg bg-gray-700 text-gray-200">
                                {!! isset($skill['icon']) ? $si($skill['icon'], '', 20) : $mdi($skill['mdi'], '', 20) !!}
                            </span>
                            <span>
                                <span class="block font-semibold text-gray-200">{{ $skill['name'] }}</span>
                                <span class="block text-gray-400">{{ $skill['use'] }}</span>
                            </span>
                        </li>
                    @endforeach
                </ul>
            </x-card-grid-item>
            <x-card-grid-item :colSpan="2" title="Academic software development">
                <div class="flex flex-col md:flex-row gap-6 mb-4">
                    <div class="md:w-1/2">
                        <p class="mb-2">During my PhD at the University of Nottingham, I developed scientific software to simulate blood flow and nutrient transport within the human placenta. This work involved:</p>
                        <ul class="list-disc pl-6 mb-4">
                            <li>Creating mathematical models of complex biological systems</li>
                            <li>Implementing numerical methods for solving partial differential equations</li>
                            <li>Optimising code for high-performance computing environments</li>
                            <li>Visualising and analysing simulation results</li>
                            <li>Collaborating with interdisciplinary teams of mathematicians, computer scientists, and medical researchers</li>
                        </ul>
                    </div>
                    <div class="md:w-1/2">
                        <x-img src="resources/img/academic-simulation.jpg" sizes="(min-width: 1024px) 33vw, (min-width: 768px) 50vw, 100vw" caption="Simulation of blood flow and nutrient transport in the placenta" class="rounded-lg shadow-lg w-full" />
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <x-a href="https://r.blakey.family/phd" :class="$buttonClass" :noUnderline="true">Read my thesis</x-a>
                    <x-a href="https://doi.org/10.1002/mrm.30379" :class="$buttonClass" :noUnderline="true">Read the synthetic MRI paper</x-a>
                    <x-a view="about.talks" :class="$buttonClass" :noUnderline="true">
                        See my talks
                        {!! $mdi('arrow-right', 'pl-1') !!}
                    </x-a>
                </div>
            </x-card-grid-item>
            <x-card-grid-item :colSpan="1" title="Teaching experience">
                <div class="mb-4">
                    <p class="mb-2">I have experience independently teaching a first-year undergraduate Python course at the University of Nottingham.</p>
                    <p class="mb-2">This role allowed me to:</p>
                    <ul class="list-disc pl-6 mb-4">
                        <li>Guide students through the fundamentals of programming logic and syntax</li>
                        <li>Enhance the pre-made teaching materials and programming exercises to suit student abilities</li>
                        <li>Provide one-on-one support for students with varying levels of prior experience in computing workshops</li>
                        <li>Assess student work and provide constructive feedback</li>
                    </ul>
                </div>
            </x-card-grid-item>
            <x-card-grid-item :colSpan="3" title="Personal projects">
                <p class="mb-4">In my spare time, I enjoy developing websites and bespoke software. Some of my personal projects include:</p>
                <ul class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($projects as $project)
                        @php($projectLink = $project['url'] ?? $project['source'])
                        <li class="flex flex-col rounded-lg bg-gray-700 p-4">
                            <x-a href="{{ $projectLink }}" class="block" :noUnderline="true" :noExternalIcon="true">
                                <x-img src="resources/img/{{ $project['image'] }}" sizes="(min-width: 1024px) 33vw, (min-width: 768px) 50vw, 100vw" caption="Screenshot of {{ $project['name'] }}" class="rounded-lg mb-3 w-full aspect-video object-cover object-top" />
                            </x-a>
                            <h4 class="font-semibold text-gray-200">{{ $project['name'] }}</h4>
                            <p class="mb-3 text-gray-400">{{ $project['description'] }}</p>
                            <div class="flex flex-wrap gap-1 mb-3">
                                @foreach ($project['stack'] as $technology)
                                    <x-a href="{{ $projectLink }}" class="rounded-full bg-gray-800 px-2 py-0.5 text-xs text-gray-300 hover:bg-gray-600" :noUnderline="true" :noExternalIcon="true">{{ $technology }}</x-a>
                                @endforeach
                            </div>
                            <div class="mt-auto flex flex-wrap gap-2">
                                @if ($project['url'])
                                    <x-a href="{{ $project['url'] }}" :class="$buttonClass" :noUnderline="true">Visit</x-a>
                                @endif
                                @if ($project['source'])
                                    <x-a href="{{ $project['source'] }}" :class="$buttonClass" :noUnderline="true">{!! $si('github', 'mr-1', 16) !!} Source</x-a>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </x-card-grid-item>
        </x-card-grid>
    </div>
</x-layout>
