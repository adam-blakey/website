@props(['concerts'])

<x-layout>
    <h3><span class="text-gray-500">###</span> Music</h3>
    <div class="mb-2">
        <x-card-grid :columns="3">
            <x-card-grid-item :colSpan="2" title="My musical background">
                <div class="mb-4 space-y-2">
                    <p>I started the "clarinet" at aged 8. Or at least, that's the one that goes off to the side, right? After some confusion in my first music lesson, I discovered that I really wanted to play the "flute", but I was two scared to admit I'd mixed up these two instruments. From this fortunate mix-up, I have had a very enjoyable time playing the clarinet as a hobby.</p>
                    <p>During high school, I also took up piano and electric guitar — drawn to what I thought were the “cooler” instruments. However, after switching schools for A-levels, I met a new clarinet teacher who encouraged me to work towards the Grade 8 exam and helped me discover just how incredible (and I guess pretty cool) the clarinet can be. Alongside school studies, I attended the North Tyneside Concert Band, was a member of the Klarinos Clarinet Quintet, and regularly performed in pit orchestras for amateur theatre productions across the North East.</p>
                    <p>After finishing A-levels in 2016, I moved to Nottingham to start a degree in mathematics. I was very excited that the University of Nottingham had a very active music department I could get involved with, despite not studying music as a degree. During a semester abroad at the National University of Singapore, I continued playing by joining two orchestras. Back in Nottingham, I was heavily involved in the student-run society ‘Blowsoc’, which consumed most Wednesdays with non-stop rehearsals from 13:00 to 21:30! As life grew busier during my second degree, I decided to bookend my university musical journey by performing a clarinet concerto with the University of Nottingham Wind Orchestra in 2022. You can watch my performance of the Martin Ellerby Clarinet Concerto <x-a href="https://youtu.be/T1_VGlT4Mq0">here</x-a>.</p>
                    <p>Outside the university, I have previously been a member of Nottingham Symphonic Winds between 2019 and 2025, and I'm currently a member of both Blackwood Clarinet Choir and Cirrus Clarinet Quartet since 2021. I achieved my ARSM diploma in clarinet performance in 2023.</p>
                </div>
            </x-card-grid-item>
            <x-card-grid-item :colSpan="1" title="Music in Nottingham">
                <div class="mb-4">
                    <p class="mb-2">As a clarinettist in Nottingham, I'm a regular member of :</p>
                    <ul class="list-disc pl-6 mb-4">
                        <li><x-a href="https://blackwoodclarinets.co.uk/" class="italic">Blackwood Clarinet Choir</x-a> (since 2021)</li>
                        <li><x-a href="https://cirrusquartet.uk/" class="italic">Cirrus Clarinet Quartet</x-a> (since 2021)</li>
                    </ul>
                </div>
            </x-card-grid-item>
            <x-card-grid-item :colSpan="2" title="The Online-aganzas">
                <div class="mb-4 space-y-2">
                    <p>In March 2020, Blowsoc's big end-of-year concert was cancelled because of COVID-19. I was the Publicity and Marketing Officer at the time and decided to turn it into something we could do from our bedrooms: the <x-a href="https://youtu.be/sPWU0MB4zMg">Online-aganza</x-a>.</p>
                    <p>The idea was simple, even if the editing wasn't 🫠 Every player recorded their own part at home, playing along to the same reference track so that everything would line up afterwards. Over 50 separate videos were then synced up, mixed together, and arranged into one big grid to make a virtual performance of selections from Pixar's <span class="italic">Up</span>. It was released on 4th April 2020 and raised over £1,800 (including Gift Aid) for Teenage Cancer Trust, in support of Matthew, our Brass Band manager, who was being treated for cancer at the time. It was one of the most successful things we ever did as a society, with around 24,000 views on Facebook alone. We even put together a <x-a href="https://youtu.be/Ksta3Wr3lfs">tutorial</x-a> so that other groups could make their own.</p>
                    <p>After a few smaller ensemble-specific Online-aganzas, we made Online-aganza 2 in the summer, this time getting our alumni involved too. We played ‘Robin Hood: Prince of Thieves’ by Michael Kamen (arr. Paul Lavender) — a staple of every Blowsoc tour, which felt fitting given we couldn't go on our tour to Belgium that year. The performers voted to raise money for Nottinghamshire Independent Domestic Abuse Services (NIDAS).</p>
                    <p>Finally, we finished 2020 with a virtual advent calendar, where a new video every day in December, from solos to small ensembles, with members and alumni rehearsing, recording and editing everything themselves. It ended on Christmas Day with the <x-a href="https://youtu.be/U5qIzYH3yH0">Christmas Online-aganza</x-a>, a full-band performance of Leroy Anderson's ‘Sleigh Ride’, raising money for Centrepoint.</p>
                </div>
            </x-card-grid-item>
            <x-card-grid-item :colSpan="1" title="Online-aganza 2">
                <iframe class="w-full aspect-video rounded" src="https://www.youtube-nocookie.com/embed/arN2Efue_LI" title="Blowsoc's Online-aganza 2 — Robin Hood: Prince of Thieves" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen loading="lazy"></iframe>
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
