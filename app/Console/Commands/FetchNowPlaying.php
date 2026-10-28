<?php

namespace App\Console\Commands;

use App\Services\MusicServiceManager;
use App\Services\MusicServices\LastFmService;
use App\Services\MusicServices\ListenBrainzService;
use Illuminate\Console\Command;

class FetchNowPlaying extends Command
{
    protected $signature = 'music:fetch';
    protected $description = 'Fetch the latest tracks from configured music services';

    public function handle(MusicServiceManager $manager): int
    {
        $this->info('Fetching latest tracks from music services...');

        // Add configured services
        if (config('music-services.lastfm.enabled')) {
            $manager->addService(new LastFmService());
        }

        if (config('music-services.listenbrainz.enabled')) {
            $manager->addService(new ListenBrainzService());
        }

        try {
            $tracks = $manager->fetchFromAllSources();

            if ($tracks->isNotEmpty()) {
                foreach ($tracks as $track) {
                    $this->info("[{$track->source}] Updated: {$track->artist_name} - {$track->track_name}");
                }
                return self::SUCCESS;
            }

            $this->error('No tracks found from any service');
            return self::FAILURE;
        } catch (\Exception $e) {
            $this->error("Error: {$e->getMessage()}");
            return self::FAILURE;
        }
    }
}
