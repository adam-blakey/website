<?php

namespace App\Contracts;

use App\Models\MusicNowPlaying;

interface MusicServiceInterface
{
    public function getRecentTrack(): ?MusicNowPlaying;
    public function getName(): string;
}
