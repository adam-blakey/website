<?php

namespace App\Services;

use App\Contracts\MusicServiceInterface;
use App\Models\MusicNowPlaying;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class MusicServiceManager
{
    private Collection $services;

    public function __construct()
    {
        $this->services = collect();
    }

    public function addService(MusicServiceInterface $service): self
    {
        $this->services->push($service);
        return $this;
    }

    public function fetchFromAllSources(): Collection
    {
        return $this->services->map(function (MusicServiceInterface $service) {
            try {
                $track = $service->getRecentTrack();
                if ($track) {
                    $track->artwork_url = $this->cacheArtwork($track->artwork_url);
                    $track->save();
                    return $track;
                }
            } catch (\Exception $e) {
                report($e); // Log the error
            }
            return null;
        })->filter();
    }

    private function cacheArtwork(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        $ext = pathinfo(parse_url($url, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION) ?: 'jpg';
        $path = 'album-art/' . sha1($url) . '.' . $ext;
        $disk = Storage::disk('public');

        try {
            if (!$disk->exists($path)) {
                $response = Http::timeout(10)->get($url);
                if (!$response->successful() || !str_starts_with($response->header('Content-Type'), 'image/')) {
                    return $url;
                }
                $disk->put($path, $response->body());
            }
        } catch (\Exception $e) {
            report($e);
            return $url;
        }

        return $disk->url($path);
    }
}
