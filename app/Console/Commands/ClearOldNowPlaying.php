<?php

namespace App\Console\Commands;

use App\Models\MusicNowPlaying;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class ClearOldNowPlaying extends Command
{
    protected $signature = 'music:clear-old';
    protected $description = 'Clear music now playing entries older than 30 days';

    public function handle(): int
    {
        $this->info('Clearing music now playing entries older than 30 days...');

        try {
            $cutoffDate = Carbon::now()->subDays(30);
            $count = MusicNowPlaying::where('played_at', '<', $cutoffDate)->delete();

            $disk = Storage::disk('public');
            $inUse = MusicNowPlaying::pluck('artwork_url')->map(fn ($url) => 'album-art/' . basename((string) $url));
            $disk->delete(collect($disk->files('album-art'))->diff($inUse)->all());

            if ($count > 0) {
                $this->info("Successfully deleted {$count} old entries.");
                return self::SUCCESS;
            }

            $this->info('No old entries found to delete.');
            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Error: {$e->getMessage()}");
            return self::FAILURE;
        }
    }
}
