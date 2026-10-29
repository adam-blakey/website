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
    {!! RecaptchaV3::initJs() !!}
    <script>
        // Default saveFile function for menu items when not in editor
        window.saveFile = function() {
            if (window.location.pathname !== '/editor') {
                window.location.href = '{{ route("editor.show") }}';
            }
        };
    </script>
</head>

<body class="font-sans text-white bg-gray-950 antialiased">
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

    <div class="flex overflow-x-hidden max-w-full w-full min-h-[calc(100vh-2.5rem)]">
        <x-explorer :tabEntries="$layoutTabEntries" :active="$layoutActiveTab" />

        <div class="flex flex-col flex-1 min-w-0">
            <x-main-nav :tabEntries="$layoutTabEntries" :active="$layoutActiveTab" />

            <main class="relative flex-1 px-4 pt-6 pb-18 sm:px-8 sm:pt-8 sm:pb-14 overflow-auto text-gray-300 bg-gray-900">
                <div class="pointer-events-none absolute inset-x-0 top-0 h-80 bg-[radial-gradient(ellipse_at_top,rgba(59,130,246,0.14),transparent_70%)]" aria-hidden="true"></div>
                <div class="relative">
                    <h1><a class="hover:text-blue-300 transition-colors" href="/"><span class="font-mono font-medium text-blue-400/50">#</span> Adam Blakey</a></h1>
                    <h2 class="mt-1 mb-6"><a class="text-gray-400 hover:text-blue-300 transition-colors" href="/{{ Helpers::getPageLink($layoutTabEntries, $layoutActiveTab) }}"><span class="font-mono font-medium text-blue-400/40">##</span> {{ Helpers::getPageName($layoutTabEntries, $layoutActiveTab) }}</a></h2>
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <x-bottom-bar :taggedVersion="$layoutTaggedVersion ?? '1.0.0'" :latestTrack="$layoutLatestTrack" :programmingTime="$layoutProgrammingTime" :programmingTimeUrl="$layoutProgrammingTimeUrl" />
</body>

</html>
