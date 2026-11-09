@props([])

<x-layout>
    <h3><a class="hover:underline" href="#"><span class="text-gray-500">###</span> Scouts</a></h3>
    <div class="mb-2">
        <x-card-grid :columns="3">
            <x-card-grid-item :colSpan="2" title="My Scouting experience">
                <div class="flex flex-col md:flex-row gap-6 mb-4">
                    <div class="md:w-1/2">
                        <p class="mb-2">I've been volunteering with Scouts since 2015, and it's been an incredibly rewarding experience. Scouting provides young people with skills for life and creates a positive impact on local communities.</p>
                        <p class="mb-2">Throughout my time in Scouts, I've undertaken a myriad of roles including:</p>
                        <ul class="list-disc pl-6 mb-4">
                            <li>Group Lead Volunteer at 2nd Beeston (2024–)</li>
                            <li>Section Team Leader for Cubs and Explorers at 2nd Beeston (2019–2021, 2023–2025)</li>
                            <li>County Webmaster at Nottinghamshire Scouts (2020–)</li>
                            <li>Trustee and Board Member at 2nd Beeston and South West Notts Scouts (2021–)</li>
                            <li>Communications and Media Lead at Nottinghamshire Scouts (2022–2023)</li>
                        </ul>
                    </div>
                    <div class="md:w-1/2">
                        <x-img src="resources/img/scouts-group.jpg" sizes="(min-width: 1024px) 33vw, (min-width: 768px) 50vw, 100vw" caption="Photo of Adam smiling at a Scout awards ceremony" class="rounded-lg shadow-lg w-full" />
                    </div>
                </div>
            </x-card-grid-item>
            <x-card-grid-item :colSpan="1" title="Where I volunteer">
                <ul class="space-y-2 mb-4">
                    <li><x-a href="https://2ndbeeston.org.uk">2nd Beeston Sea Scouts</x-a></li>
                    <li><x-a href="https://swnotts-scouts.org.uk">South West Notts Scouts</x-a></li>
                    <li><x-a href="https://notts-scouts.org.uk">Notts Scouts</x-a></li>
                </ul>
                <p class="mb-2">For each of the above, I've had some involvement in the IT and website side. For 2nd Beeston and SW Notts Scouts, I manage the Google Workspace provision and maintain both websites. For Notts Scouts, I've historically revamped the website and now help with day-to-day content updates.</p>
            </x-card-grid-item>
            <x-card-grid-item :colSpan="3" title="2nd Beeston Sea Scouts">
                <div class="mb-4 space-y-2">
                    <p>2nd Beeston Sea Scouts has been part of Beeston since 1928. Today we support around 85 young people in Beeston, with a particular focus on water activities. We're a Royal Navy Recognised Sea Scout Group (one of only 105 in the UK) and we hold The Queen's Award for Voluntary Service as part of Nottinghamshire Scouts.</p>
                    <p>I joined as a volunteer in 2016 and have held a number of positions. Since 2024, I started as a Group Lead Volunteer, as well as looking after the group's website and Google Workspace.</p>
                    <h4 class="font-semibold pt-2">Barton Island</h4>
                    <p>Our river base is <x-a href="https://www.2ndbeeston.org.uk/about-us/barton-island/">Barton Island</x-a>, on the River Trent on the edge of Attenborough Nature Reserve. We've used it for over 40 years and own and maintain the downstream half. It has four sleeping huts, a storm shelter, a leader base and a floating pontoon, but no running water or mains electricity (so it's a little remote!). Everything is looked after by volunteers, and it's available to hire for other groups too.</p>
                    <h4 class="font-semibold pt-2">The Big Build</h4>
                    <p>Our headquarters on Lilac Grove has served us for decades, and we've recently signed an extended lease with Broxtowe Borough Council. We're now working on a £1m project to build a new HQ that's much better suited to our young people and the wider community. Support has come from former members, including a generous £50,000 donation in 2025, as well as fundraising events and our legacy tile scheme. You can read more on the <x-a href="https://www.2ndbeeston.org.uk/fundraising/the-big-build/">Big Build page</x-a>.</p>
                    <p>We're always looking for more volunteers, whether that's running sections, helping on the island or lending a hand with the build. Find out more at <x-a href="https://2ndbeeston.org.uk">2ndbeeston.org.uk</x-a>.</p>
                </div>
            </x-card-grid-item>
        </x-card-grid>
    </div>
</x-layout>
