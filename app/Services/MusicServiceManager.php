<?php

namespace App\Services;

use App\Contracts\MusicServiceInterface;
use App\Models\MusicNowPlaying;
use Illuminate\Support\Collection;

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
                    $track->save();
                    return $track;
                }
            } catch (\Exception $e) {
                report($e); // Log the error
            }
            return null;
        })->filter();
    }
}
