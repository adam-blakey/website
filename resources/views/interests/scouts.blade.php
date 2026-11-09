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
                        <x-img src="resources/img/scouts-group.jpg" sizes="(min-width: 1024px) 33vw, (min-width: 768px) 50vw, 100vw" alt="Photo of Adam with Scouts" class="rounded-lg shadow-lg w-full" />
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
        </x-card-grid>
    </div>
</x-layout>
