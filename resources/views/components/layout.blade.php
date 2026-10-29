@php
    use App\Helpers\Helpers;
@endphp

<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adam Blakey</title>
    <link type="image/x-icon" href="{{ asset('favicon.ico') }}" rel="icon">
    <link href="https://fonts.bunny.net" rel="preconnect">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|jetbrains-mono:400,500,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
    <script>
        // Default saveFile function for menu items when not in editor
        window.saveFile = function() {
            if (window.location.pathname !== '/editor') {
                window.location.href = '{{ route("editor.show") }}';
            }
        };
    </script>
</head>

<body class="font-sans text-white bg-gray-700 antialiased lg:flex lg:flex-col lg:h-screen lg:overflow-hidden">
    <!--
            _       _                   ____  _       _
           / \   __| | __ _ _ __ ___   | __ )| | __ _| | _____ _   _
          / _ \ / _` |/ _` | '_ ` _ \  |  _ \| |/ _` | |/ / _ \ | | |
         / ___ \ (_| | (_| | | | | | | | |_) | | (_| |   <  __/ |_| |
        /_/   \_\__,_|\__,_|_| |_| |_| |____/|_|\__,_|_|\_\___|\__, |
                                                               |___/
    -->

    <!-- Hunting for Easter eggs, are we? Why don't you give {{ env('APP_URL') }}/brew-coffee a try. -->

    <x-top-nav :topMenuEntries="$layoutTopMenuEntries" />

    <div class="flex flex-1 min-h-0 gap-1.5 px-1.5 pb-14 sm:pb-8 lg:pb-0">
        <x-tool-stripe />
        <x-explorer :tabEntries="$layoutTabEntries" :active="$layoutActiveTab" />

        <div class="flex flex-col flex-1 min-w-0 rounded-xl bg-gray-900 overflow-hidden">
            <x-main-nav :tabEntries="$layoutTabEntries" :active="$layoutActiveTab" />

            <main class="flex-1 px-4 py-6 sm:px-8 sm:py-8 overflow-y-auto text-gray-300">
                <h1><a class="hover:text-blue-400 transition-colors" href="/"><span class="font-mono font-medium text-keyword">#</span> Adam Blakey</a></h1>
                <h2 class="mt-1 mb-6"><a class="text-gray-400 hover:text-blue-400 transition-colors" href="/{{ Helpers::getPageLink($layoutTabEntries, $layoutActiveTab) }}"><span class="font-mono font-medium text-keyword">##</span> {{ Helpers::getPageName($layoutTabEntries, $layoutActiveTab) }}</a></h2>
                {{ $slot }}
            </main>
        </div>
    </div>

    <x-bottom-bar :taggedVersion="$layoutTaggedVersion ?? '1.0.0'" :latestTrack="$layoutLatestTrack" :programmingTime="$layoutProgrammingTime" />
</body>

</html>
