@php
    use App\Helpers\Helpers;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adam Blakey</title>
    <link type="image/x-icon" href="{{ asset('favicon.ico') }}" rel="icon">
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

<body class="font-sans text-white bg-gray-900">
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

    <div class="flex overflow-x-hidden max-w-full w-full sm:mb-6 mb-12">
        <div class="flex flex-col flex-1">
            <x-main-nav :tabEntries="$layoutTabEntries" :active="$layoutActiveTab" />

            <div class="flex-1 p-4 bg-gray-850">
                <main class="p-4 overflow-auto text-gray-300 bg-gray-900 rounded-lg">
                    <h1><a class="hover:underline" href="/"><span class="text-gray-500">#</span> Adam Blakey</a></h1>
                    <h2><a class="hover:underline" href="/{{ Helpers::getPageLink($layoutTabEntries, $layoutActiveTab) }}"><span class="text-gray-500">##</span> {{ Helpers::getPageName($layoutTabEntries, $layoutActiveTab) }}</a></h2>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>

    <x-bottom-bar :taggedVersion="$layoutTaggedVersion ?? '1.0.0'" :latestTrack="$layoutLatestTrack" :programmingTime="$layoutProgrammingTime" :programmingTimeUrl="$layoutProgrammingTimeUrl" />
</body>

</html>
