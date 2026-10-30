@props(['concerts'])

<x-layout>
    <h3><span class="text-gray-500">###</span> Music</h3>
    <div class="mb-2">
        <x-card-grid :columns="3">
            <x-card-grid-item :colSpan="2" title="My musical background">
                <div class="mb-4 space-y-2">
                    <p>I started the "clarinet" at age 8. Or at least, that's the one that goes off to the side, right? After some confusion in my first music lesson, I discovered that I really wanted to play the "flute", but I was too scared to admit I'd mixed up these two instruments. From this fortunate mix-up, I have had a very enjoyable time playing the clarinet as a hobby.</p>
                    <p>During high school, I also took up piano and electric guitar — drawn to what I thought were the “cooler” instruments. However, after switching schools for A-levels, I met a new clarinet teacher who encouraged me to work towards the Grade 8 exam and helped me discover just how incredible (and I guess pretty cool) the clarinet can be.</p>
                    <p>Alongside school studies, I attended the North Tyneside Concert Band, was a member of the Klarinos Clarinet Quintet, and regularly performed in pit orchestras for amateur theatre productions across the North East.</p>
                    <h4 class="font-semibold pt-2">University</h4>
                    <p>After finishing A-levels in 2016, I moved to Nottingham to start a degree in mathematics. I was very excited that the University of Nottingham had a very active music department I could get involved with, despite not studying music as a degree.</p>
                    <p>During a semester abroad at the National University of Singapore, I continued playing by joining two orchestras. Back in Nottingham, I was heavily involved in the student-run society ‘Blowsoc’, which consumed most Wednesdays with non-stop rehearsals from 13:00 to 21:30!</p>
                    <p>As life grew busier during my second degree, I decided to bookend my university musical journey by performing a clarinet concerto with the University of Nottingham Wind Orchestra in 2022. You can watch my performance of the Martin Ellerby Clarinet Concerto below, or <x-a href="https://youtu.be/T1_VGlT4Mq0">on YouTube</x-a>.</p>
                    <iframe class="w-full aspect-video rounded-lg" src="https://www.youtube-nocookie.com/embed/T1_VGlT4Mq0" title="Martin Ellerby Clarinet Concerto performed by Adam Blakey with the University of Nottingham Wind Orchestra" loading="lazy" allow="encrypted-media; picture-in-picture" allowfullscreen></iframe>
                    <h4 class="font-semibold pt-2">Beyond university</h4>
                    <p>Outside the university, I joined Nottingham Symphonic Winds in 2019 and played with them until 2024. Blackwood Clarinet Choir and Cirrus Clarinet Quartet have been regulars for me since 2021, and I achieved my ARSM diploma in clarinet performance in 2023.</p>
                    <p>In 2025, I started playing Bb/A clarinet with Equilibrium Symphony Orchestra, and in 2026 I decided to swap to bass clarinet in Blackwood.</p>
                </div>
            </x-card-grid-item>
            <x-card-grid-item :colSpan="1" title="Music in Nottingham">
                <div class="mb-4">
                    <p class="mb-2">As a clarinettist in Nottingham, I'm a regular member of :</p>
                    <ul class="mb-4 space-y-1">
                        <li class="flex items-center gap-2">
                            <img src="{{ \App\Models\Concert::ENSEMBLES['Blackwood Clarinets']['icon'] }}" alt="" class="w-4 h-4 rounded-sm" />
                            <span><x-a href="https://blackwoodclarinets.co.uk/" class="italic">Blackwood Clarinet Choir</x-a> — bass clarinet (since 2021)</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <img src="{{ \App\Models\Concert::ENSEMBLES['Cirrus Clarinet Quartet']['icon'] }}" alt="" class="w-4 h-4 rounded-sm" />
                            <span><x-a href="https://cirrusquartet.uk/" class="italic">Cirrus Clarinet Quartet</x-a> — Bb clarinet (since 2021)</span>
                        </li>
                        <li class="flex items-center gap-2">
                            {!! $si('instagram', 'w-4 h-4', 16) !!}
                            <span><x-a href="https://www.instagram.com/equilibrium_orchestra/" class="italic">Equilibrium Symphony Orchestra</x-a> — Bb/A clarinet (since 2025)</span>
                        </li>
                    </ul>
                    <x-img src="resources/img/woodwind-concert.jpg" sizes="(min-width: 1024px) 33vw, 100vw" alt="Adam and fellow woodwind players holding their instruments after a concert in a church" class="rounded-lg shadow-lg w-full" />
                </div>
            </x-card-grid-item>
            <x-card-grid-item :colSpan="3" title="Upcoming concerts ({{ $concerts->count() }})">
                @if($concerts->isEmpty())
                    <p class="text-sm text-gray-500 mt-4">No upcoming concerts at the moment. Check back later!</p>
                @else
                    <x-card-grid :columns="3">
                        @foreach($concerts as $concert)
                            <x-card-grid-item.concert :concert="$concert" color="bg-gray-700" />
                        @endforeach
                    </x-card-grid>
                @endif
            </x-card-grid-item>
        </x-card-grid>
    </div>
</x-layout>
