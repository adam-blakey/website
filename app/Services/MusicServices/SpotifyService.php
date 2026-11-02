<?php

namespace App\Services\MusicServices;

use App\Contracts\MusicServiceInterface;
use App\Models\MusicNowPlaying;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class SpotifyService implements MusicServiceInterface
{
    public function getName(): string
    {
        return 'spotify';
    }

    public function getRecentTrack(): ?MusicNowPlaying
    {
        $response = Http::withToken($this->accessToken())
            ->get('https://api.spotify.com/v1/me/player/currently-playing')
            ->throw();

        $track = $response->json('item');

        if (!$response->json('is_playing') || $response->json('currently_playing_type') !== 'track' || !$track) {
            return null;
        }

        return new MusicNowPlaying([
            'track_name' => $track['name'],
            'artist_name' => collect($track['artists'])->pluck('name')->join(', '),
            'album_name' => $track['album']['name'] ?? null,
            'artwork_url' => $track['album']['images'][0]['url'] ?? null,
            'played_at' => now(),
            'source' => $this->getName(),
            'metadata' => [
                'track_url' => $track['external_urls']['spotify'] ?? null,
                'artist_url' => $track['artists'][0]['external_urls']['spotify'] ?? null,
                'album_url' => $track['album']['external_urls']['spotify'] ?? null,
            ],
        ]);
    }

    private function accessToken(): string
    {
        $cached = Cache::get('spotify.access_token');
        if ($cached) {
            return $cached;
        }

        $configuredRefreshToken = config('music-services.spotify.refresh_token');
        $refreshTokenCacheKey = 'spotify.refresh_token.' . sha1($configuredRefreshToken);

        $response = Http::asForm()
            ->withBasicAuth(config('music-services.spotify.client_id'), config('music-services.spotify.client_secret'))
            ->post('https://accounts.spotify.com/api/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => Cache::get($refreshTokenCacheKey, $configuredRefreshToken),
            ])
            ->throw();

        if ($response->json('refresh_token')) {
            Cache::forever($refreshTokenCacheKey, $response->json('refresh_token'));
        }

        $token = $response->json('access_token');
        Cache::put('spotify.access_token', $token, $response->json('expires_in', 3600) - 60);

        return $token;
    }
}
