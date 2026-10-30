<?php

namespace App\Console\Commands;

use App\Helpers\Image;
use Illuminate\Console\Command;

class GenerateImageSizes extends Command
{
    protected $signature = 'images:resize';

    protected $description = 'Generate resized webp copies of the images in resources/img';

    public function handle(): void
    {
        foreach (Image::all() as $image) {
            try {
                $this->info("{$image->path}: " . implode(', ', $image->generate()));
            } catch (\Throwable $e) {
                $this->warn("{$image->path}: skipped, pages will use the original. {$e->getMessage()}");
            }
        }
    }
}
