@props(['qualification'])

<li class="border-b border-gray-200 dark:border-gray-700 pb-6 last:border-b-0">
    <div class="flex items-center gap-3 mb-1">
        <span class="flex shrink-0 items-center justify-center w-8 h-8 bg-blue-100 rounded-full dark:bg-blue-900 p-1">
            {!! \Mdi\Mdi::mdi($qualification->icon) !!}
        </span>
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $qualification->title }}</h3>
    </div>
    <div class="mb-2 text-sm font-medium text-gray-500 dark:text-gray-400">{{ $qualification->issuer }}</div>
    <time class="block mb-2 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">
        Completed {{ $qualification->date->format('F Y') }}@if($qualification->expires) · Expires {{ $qualification->expires->format('F Y') }}@endif
    </time>
    @if($qualification->result)
        <p class="text-base font-normal text-gray-500 dark:text-gray-400">Result: {{ $qualification->result }}</p>
    @endif
    @if($qualification->credentialId)
        <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Credential ID: {{ $qualification->credentialId }}</p>
    @endif
    @if($qualification->link)
        <x-a href="{{ $qualification->link }}" class="inline-flex items-center px-4 py-2 mt-2 mb-1 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:outline-none focus:ring-gray-100 focus:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700" :noUnderline="true">
            Verify credential
        </x-a>
    @endif
</li>
