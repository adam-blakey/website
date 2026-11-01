<x-layout>
    <h3><a class="hover:underline" href="#"><span class="text-gray-500">###</span> Software</a></h3>
    <div class="mb-2">
        <x-card-grid :columns="3">
            <x-card-grid-item :colSpan="1" title="My software development experience">
                <div class="mb-4">
                    <p class="mb-2">I'm currently a Software Engineer at Capital One, where I'm working in one of the Site Reliability Engineer teams (SRE). We looks after the services that support new customer applications.</p>
                </div>
            </x-card-grid-item>
            <x-card-grid-item :colSpan="1" title="Technical skills">
                <div class="mb-4">
                    <p class="mb-2">Throughout my professional and academic career, I've developed expertise in the following cocktail of programming languages and technologies:</p>
                    <ul class="list-disc pl-6 mb-4">
                        <li>C# for desktop and Blazor web server applications</li>
                        <li>Delphi for backend server code and legacy UI desktop applications</li>
                        <li>Fortran for high-performance scientific computation applications</li>
                        <li>HTML, JavaScript, and CSS for front-end web development</li>
                        <li>Java for backend microservices</li>
                        <li>PHP (and Laravel) for web development and server-side applications</li>
                        <li>Python for data analysis, scientific computing, and teaching</li>
                        <li>SQL for database design and management</li>
                    </ul>
                </div>
            </x-card-grid-item>
            <x-card-grid-item :colSpan="1" title="Academic software development">
                <div class="mb-4">
                    <p class="mb-2">During my PhD at the University of Nottingham, I developed scientific software to simulate blood flow and nutrient transport within the human placenta. This work involved:</p>
                    <ul class="list-disc pl-6 mb-4">
                        <li>Creating mathematical models of complex biological systems</li>
                        <li>Implementing numerical methods for solving partial differential equations</li>
                        <li>Optimising code for high-performance computing environments</li>
                        <li>Visualising and analysing simulation results</li>
                        <li>Collaborating with interdisciplinary teams of mathematicians, computer scientists, and medical researchers</li>
                    </ul>
                    <p class="mb-2">You can read my thesis <x-a href="https://r.blakey.family/phd">here</x-a>, and you can read a paper on synthetic MRI <x-a href="https://doi.org/10.1002/mrm.30379">here</x-a>.</p>
                </div>
            </x-card-grid-item>
            <x-card-grid-item :colSpan="1" title="Teaching experience">
                <div class="mb-4">
                    <p class="mb-2">I have experience independently teaching a first-year undergraduate Python course at the University of Nottingham. This role allowed me to:</p>
                    <ul class="list-disc pl-6 mb-4">
                        <li>Guide students through the fundamentals of programming logic and syntax</li>
                        <li>Enhance the pre-made teaching materials and programming exercises to suit student abilities</li>
                        <li>Provide one-on-one support for students with varying levels of prior experience in computing workshops</li>
                        <li>Assess student work and provide constructive feedback</li>
                    </ul>
                </div>
            </x-card-grid-item>
            <x-card-grid-item :colSpan="1" title="Personal projects">
                <div class="mb-4">
                    <p class="mb-2">In my spare time, I enjoy developing websites and bespoke software. Some of my personal projects include:</p>
                    <ul class="list-disc pl-6 mb-4">
                        <li><em>This</em> website: github.com/adam-blakey/website</li>
                        <li>Readererer: github.com/adam-blakey/readererer</li>
                        <li>2nd Beeston Sea Scouts: 2ndbeeston.org.uk</li>
                        <li>SW Notts Scouts: swnotts-scouts.org.uk</li>
                        <li>Keiron Anderson: keironanderson.co.uk</li>
                        <li>Nottingham Symphonic Winds: nsw.org.uk</li>
                    </ul>
                </div>
            </x-card-grid-item>
        </x-card-grid>
    </div>
</x-layout>
