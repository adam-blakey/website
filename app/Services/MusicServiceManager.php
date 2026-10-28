<?php

namespace App\Services;

use App\Contracts\MusicServiceInterface;
use App\Models\MusicNowPlaying;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Mime\MimeTypes;

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

        $base = 'album-art/' . sha1($url);
        $disk = Storage::disk('public');

        try {
            // shortcut: glob assumes a local disk, switch to a stored lookup if the disk goes remote.
            $cached = glob($disk->path($base) . '.*');
            if ($cached) {
                $path = $base . '.' . pathinfo($cached[0], PATHINFO_EXTENSION);
            } else {
                $response = Http::timeout(10)->get($url);
                $type = strtolower(trim(explode(';', $response->header('Content-Type'))[0]));
                if (!$response->successful() || !str_starts_with($type, 'image/')) {
                    return $url;
                }
                $path = $base . '.' . (MimeTypes::getDefault()->getExtensions($type)[0] ?? 'jpg');
                $disk->put($path, $response->body());
            }
        } catch (\Exception $e) {
            report($e);
            return $url;
        }

        return $disk->url($path);
    }
}
