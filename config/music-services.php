<?php

return [
    'lastfm' => [
        'enabled' => env('LASTFM_ENABLED', false),
        'key' => env('LASTFM_API_KEY'),
        'username' => env('LASTFM_USERNAME'),
    ],

    'listenbrainz' => [
        'enabled' => env('LISTENBRAINZ_ENABLED', false),
        'token' => env('LISTENBRAINZ_TOKEN'),
        'username' => env('LISTENBRAINZ_USERNAME'),
    ],
];
