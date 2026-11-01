<?php

return [
    'spotify' => [
        'enabled' => env('SPOTIFY_ENABLED', false),
        'client_id' => env('SPOTIFY_CLIENT_ID'),
        'client_secret' => env('SPOTIFY_CLIENT_SECRET'),
        'refresh_token' => env('SPOTIFY_REFRESH_TOKEN'),
        'user_id' => env('SPOTIFY_USER_ID'),
    ],

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
