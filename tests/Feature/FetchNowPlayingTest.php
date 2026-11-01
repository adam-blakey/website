<?php

use App\Models\MusicNowPlaying;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
    DB::purge('sqlite');
    Artisan::call('migrate');
    Storage::fake('public');
    config([
        'music-services.spotify' => ['enabled' => true, 'client_id' => 'id', 'client_secret' => 'secret', 'refresh_token' => 'refresh', 'user_id' => 'adamspotify'],
        'music-services.lastfm' => ['enabled' => true, 'key' => 'key', 'username' => 'adamlastfm'],
        'music-services.listenbrainz.enabled' => false,
    ]);
});

function fakeMusicApis(array|int $spotifyNowPlaying): void
{
    Http::fake([
        'accounts.spotify.com/*' => Http::response(['access_token' => 'token', 'expires_in' => 3600]),
        'api.spotify.com/*' => is_int($spotifyNowPlaying) ? Http::response(null, $spotifyNowPlaying) : Http::response($spotifyNowPlaying),
        'ws.audioscrobbler.com/*' => Http::response(['recenttracks' => ['track' => [[
            'name' => 'Scrobbled Song',
            'artist' => ['#text' => 'Scrobbled Artist'],
            'album' => ['#text' => 'Scrobbled Album'],
            'url' => 'https://www.last.fm/music/x',
            'image' => [],
        ]]]]),
        'api.listenbrainz.org/*' => Http::response([]),
        '*' => Http::response('', 404),
    ]);
}

it('saves the spotify track and skips last.fm while spotify is playing', function () {
    fakeMusicApis([
        'is_playing' => true,
        'currently_playing_type' => 'track',
        'item' => [
            'name' => 'Spotify Song',
            'artists' => [
                ['name' => 'First Artist', 'external_urls' => ['spotify' => 'https://open.spotify.com/artist/1']],
                ['name' => 'Second Artist'],
            ],
            'album' => ['name' => 'Spotify Album', 'images' => [], 'external_urls' => ['spotify' => 'https://open.spotify.com/album/1']],
            'external_urls' => ['spotify' => 'https://open.spotify.com/track/1'],
        ],
    ]);

    $this->artisan('music:fetch')->assertSuccessful();

    $track = MusicNowPlaying::sole();
    expect($track->source)->toBe('spotify')
        ->and($track->artist_name)->toBe('First Artist, Second Artist')
        ->and($track->track_url)->toBe('https://open.spotify.com/track/1')
        ->and($track->artist_url)->toBe('https://open.spotify.com/artist/1')
        ->and($track->album_url)->toBe('https://open.spotify.com/album/1');
    Http::assertNotSent(fn ($request) => str_contains($request->url(), 'audioscrobbler'));
});

it('falls back to last.fm when spotify has nothing playing', function (array|int $spotifyNowPlaying) {
    fakeMusicApis($spotifyNowPlaying);

    $this->artisan('music:fetch')->assertSuccessful();

    expect(MusicNowPlaying::sole()->source)->toBe('lastfm');
})->with([
    'nothing playing' => 204,
    'paused' => [['is_playing' => false, 'currently_playing_type' => 'track', 'item' => ['name' => 'x']]],
    'podcast' => [['is_playing' => true, 'currently_playing_type' => 'episode', 'item' => null]],
    'spotify error' => 500,
]);

it('shows the spotify and last.fm profile links with or without a track', function () {
    $this->get('/now')
        ->assertSee('https://open.spotify.com/user/adamspotify', false)
        ->assertSee('https://www.last.fm/user/adamlastfm', false);

    MusicNowPlaying::create(['track_name' => 'Song', 'artist_name' => 'Artist', 'played_at' => now(), 'source' => 'spotify']);

    $this->get('/now')
        ->assertSee('Song')
        ->assertSee('https://open.spotify.com/user/adamspotify', false)
        ->assertSee('https://www.last.fm/user/adamlastfm', false);
});
