<?php

namespace App\Console\Commands;

use App\Models\Concert;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

class ClearConcerts extends Command
{
    use ConfirmableTrait;

    protected $signature = 'concerts:clear {--force : Force the operation to run when in production}';
    protected $description = 'Delete all concerts so the next concerts:import starts afresh';

    public function handle(): int
    {
        if (! $this->confirmToProceed()) {
            return self::FAILURE;
        }

        $count = Concert::query()->delete();
        $this->info("Deleted {$count} concerts.");

        return self::SUCCESS;
    }
}
