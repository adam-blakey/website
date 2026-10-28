<?php

namespace App\Services\MusicServices;

use App\Contracts\MusicServiceInterface;
use App\Models\MusicNowPlaying;
use Illuminate\Support\Facades\Http;

class ListenBrainzService implements MusicServiceInterface
{
    private string $token;
    private string $username;
    private string $baseUrl = 'https://api.listenbrainz.org/1/user/';

    public function __construct()
    {
        $this->token = config('music-services.listenbrainz.token');
        $this->username = config('music-services.listenbrainz.username');
    }

    public function getName(): string
    {
        return 'listenbrainz';
    }

    /**
     * Get the most recent listen from ListenBrainz
     *
     * @return array|null
     */
    private function getRecentListen(): ?array
    {
        // TODO: I don't think the authorisation is necessary.
        $response = Http::withHeaders([
            'Authorization' => "Token {$this->token}"
        ])->get("{$this->baseUrl}{$this->username}/playing-now", [
            'limit' => 1
        ]);

        if (!$response->successful()) {
            return null;
        }

        $payload = $response->json()['payload'] ?? null;

        if (empty($payload) || empty($payload['listens'])) {
            return null;
        }

        return $payload['listens'][0] ?? null;
    }

    /**
     * Get track name using a separate API call
     *
     * @param array $listen
     * @return array
     */
    private function getTrackAndArtist(array $listen): array
    {
        // First try to get from the listen data
        $trackName = $listen['track_metadata']['track_name'] ?? null;
        $recordingMbid = null;
        $artistName = $listen['track_metadata']['artist_name'] ?? null;
        $artistMbid = null;

        if (!$trackName || !$artistName) {
            return [$trackName, null, $artistName, null];
        }

        // Make a separate API call to get track details
        $artistName = $listen['track_metadata']['artist_name'] ?? null;
        if ($artistName && $trackName) {
            $response = Http::withHeaders([
            'Authorization' => "Token {$this->token}"
        ])->get("https://api.listenbrainz.org/1/metadata/lookup", [
                'recording_name' => $trackName,
                'artist_name' => $artistName
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['recording_name'])) {
                    $trackName = $data['recording_name'];
                }
                if (!empty($data['recording_mbid'])) {
                    $recordingMbid = $data['recording_mbid'];
                }
                if (!empty($data['artist_name'])) {
                    $artistName = $data['artist_name'];
                }
                if (!empty($data['artist_mbids'][0])) {
                    $artistMbid = $data['artist_mbids'][0];
                }
            }
        }

        return [$trackName, $recordingMbid, $artistName, $artistMbid];
    }

    /**
     * Get album name using a separate API call
     *
     * @param array $listen
     * @return array
     */
    private function getAlbum(array $listen): array
    {
        // First try to get from the listen data
        $albumName = $listen['track_metadata']['release_name'] ?? null;
        $artistName = $listen['track_metadata']['artist_name'] ?? null;
        $trackName = $listen['track_metadata']['track_name'] ?? null;
        $albumMbid = null;

        if (!$albumName) {
            return [null, null];
        }

        // Make a separate API call to get album details
        if ($artistName && $trackName) {
            $response = Http::withHeaders([
            'Authorization' => "Token {$this->token}"
        ])->get("https://api.listenbrainz.org/1/metadata/lookup", [
                'recording_name' => $trackName,
                'artist_name' => $artistName,
                'release_name' => $albumName
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['release_name'])) {
                    $albumName = $data['release_name'];
                }
                if (!empty($data['release_mbid'])) {
                    $albumMbid = $data['release_mbid'];
                }
            }
        }

        return [$albumName, $albumMbid];
    }

    /**
     * Get additional info from a listen
     *
     * @param array $listen
     * @return array
     */
    private function getAdditionalInfo(array $listen): array
    {
        return $listen['track_metadata']['additional_info'] ?? [];
    }

    /**
     * Get artwork URL for a listen
     *
     * @param ?string $trackMbid
     * @param ?string $artistMbid
     * @param ?string $albumMbid
     * @return (string|null)
     *
     */
    private function getArtworkUrl(?string $trackMbid, ?string $artistMbid, ?string $albumMbid): ?string
    {
        if (!empty($albumMbid)) {
            return $this->getArtworkUrlFromMusicBrainz($albumMbid);
        }
        elseif (!empty($trackMbid)) {
            return $this->getArtworkUrlFromMusicBrainz($trackMbid, 'recording');
        }
        elseif (!empty($artistMbid)) {
            return $this->getArtworkUrlFromMusicBrainz($artistMbid, 'artist');
        }

        return null;
    }

    /**
     * Get played at timestamp from a listen
     *
     * @param array $listen
     * @return \Carbon\Carbon
     */
    private function getPlayedAt(array $listen): \Carbon\Carbon
    {
        return isset($listen['listened_at'])
            ? \Carbon\Carbon::createFromTimestamp($listen['listened_at'])
            : now();
    }

    public function getRecentTrack(): ?MusicNowPlaying
    {
        $listen = $this->getRecentListen();

        if (!$listen) {
            return null;
        }

        $playedAt = $this->getPlayedAt($listen);
        if ($playedAt->diffInMinutes(now()) > 5) {
            return null;
        }

        // Make separate API calls for each piece of information
        [$trackName, $trackMbid, $artistName, $artistMbid] = $this->getTrackAndArtist($listen);
        [$albumName, $albumMbid] = $this->getAlbum($listen);
        $additionalInfo = $this->getAdditionalInfo($listen);
        $artworkUrl = $this->getArtworkUrl($trackMbid, $artistMbid, $albumMbid);

        if (!$trackName || !$artistName) {
            return null;
        }

        if ($playedAt < now()->subMinutes(60)) {
            return null;
        }

        return new MusicNowPlaying([
            'track_name' => $trackName,
            'artist_name' => $artistName,
            'album_name' => $albumName,
            'track_url' => null,
            'artwork_url' => $artworkUrl,
            'played_at' => $playedAt,
            'source' => $this->getName(),
            'metadata' => [
                'additional_info' => $additionalInfo,
            ],
            'track_mbid' => $trackMbid,
            'artist_mbid' => $artistMbid,
            'album_mbid' => $albumMbid,
        ]);
    }

    /**
     * Get artwork URL from MusicBrainz using MBID
     *
     * @param string $mbid MusicBrainz ID
     * @param string $type Type of ID (release or recording)
     * @return string|null
     */
    private function getArtworkUrlFromMusicBrainz(string $mbid, string $type = 'release'): ?string
    {
        // Cover Art Archive API for MusicBrainz
        if ($type === 'release') {
            $response = Http::get("https://coverartarchive.org/release/{$mbid}");

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['images'][0]['thumbnails']['small'])) {
                    return $data['images'][0]['thumbnails']['small'];
                }
            }
        }
        // For recordings, we need to get the release first
        elseif ($type === 'recording') {
            $response = Http::get("https://musicbrainz.org/ws/2/recording/{$mbid}?fmt=json&inc=releases");

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['releases'][0]['id'])) {
                    return $this->getArtworkUrlFromMusicBrainz($data['releases'][0]['id']);
                }
            }
        }
        // For artists, we need to get the release group first
        elseif ($type === 'artist') {
            $response = Http::get("https://musicbrainz.org/ws/2/artist/{$mbid}?fmt=json&inc=release-groups");

            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['release-groups'][0]['id'])) {
                    return $this->getArtworkUrlFromMusicBrainz($data['release-groups'][0]['id'], 'release');
                }
            }
        }

        return null;
    }
}
