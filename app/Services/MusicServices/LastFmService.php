<?php

namespace App\Services\MusicServices;

use App\Contracts\MusicServiceInterface;
use App\Models\MusicNowPlaying;
use Illuminate\Support\Facades\Http;

class LastFmService implements MusicServiceInterface
{
    private string $apiKey;
    private string $username;
    private string $baseUrl = 'https://ws.audioscrobbler.com/2.0/';

    public function __construct()
    {
        $this->apiKey = config('music-services.lastfm.key');
        $this->username = config('music-services.lastfm.username');
    }

    public function getName(): string
    {
        return 'lastfm';
    }

    public function getRecentTrack(): ?MusicNowPlaying
    {
        $response = Http::get($this->baseUrl, [
            'method' => 'user.getrecenttracks',
            'user' => $this->username,
            'api_key' => $this->apiKey,
            'format' => 'json',
            'limit' => 1
        ]);

        if (!$response->successful()) {
            return null;
        }

        $track = $response->json()['recenttracks']['track'][0] ?? null;

        if (!$track) {
            return null;
        }

        // Get the best available image URL
        $artworkUrl = null;
        if (!empty($track['image'])) {
            // Last.fm provides images in different sizes, with index 3 being the largest (extralarge)
            // Try to get the largest image first, then fall back to smaller ones
            foreach ([3, 2, 1, 0] as $index) {
                if (!empty($track['image'][$index]['#text'])) {
                    $artworkUrl = $track['image'][$index]['#text'];
                    break;
                }
            }
        }

        // Get mbids
        $track_mbid = $track['mbid'] ?? null;
        $artist_mbid = null;
        $album_mbid = null;
        $base_url = 'https://api.listenbrainz.org/1/metadata/lookup/';

        $mb_response = Http::get($base_url, [
            'recording_name' => $track['name'],
            'artist_name' => $track['artist']['#text'],
            'release_name' => $track['album']['#text'] ?? null,
        ]);

        if ($mb_response->successful()) {
            $mb_data = $mb_response->json();

            $track_mbid = $mb_data['recording_mbid'] ?? null;
            $artist_mbid = $mb_data['artist_mbids'][0] ?? null;
            $album_mbid = $mb_data['release_mbid'] ?? null;
        }

        return new MusicNowPlaying([
            'track_name' => $track['name'],
            'artist_name' => $track['artist']['#text'],
            'album_name' => $track['album']['#text'] ?? null,
            'track_url' => $track['url'],
            'artwork_url' => $artworkUrl,
            'played_at' => now(),
            'source' => $this->getName(),
            'metadata' => [
                'mbid' => $track_mbid,
                'image_url' => $track['image'][2]['#text'] ?? null,
            ],
            'track_mbid' => $track_mbid,
            'artist_mbid' => $artist_mbid,
            'album_mbid' => $album_mbid,
        ]);
    }
}
