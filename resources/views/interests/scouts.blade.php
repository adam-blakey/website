@props([])

<x-layout>
    <h3><a class="hover:underline" href="#"><span class="text-gray-500">###</span> Scouts</a></h3>
    <div class="mb-2">
        <x-card-grid :columns="3">
            <x-card-grid-item :colSpan="1" title="My Scouting experience">
                <div class="mb-4">
                    <p class="mb-2">I've been volunteering with Scouts since 2016, and it has been an incredibly rewarding experience. Scouting provides young people with skills for life and creates a positive impact on local communities.</p>
                    <p class="mb-2">Throughout my time in Scouts, I've undertaken a myriad of roles including:</p>
                    <ul class="list-disc pl-6 mb-4">
                        <li>Group Lead Volunteer at 2nd Beeston</li>
                        <li>Section Team Leader for Cubs and Explorers at 2nd Beeston</li>
                        <li>County Webmaster at Nottinghamshire Scouts</li>
                        <li>Trustee and Board Member at 2nd Beeston and South West Notts Scouts</li>
                        <li>Communications and Media Lead at Nottinghamshire Scouts</li>
                    </ul>
                </div>
            </x-card-grid-item>
        </x-card-grid>
    </div>
</x-layout>
