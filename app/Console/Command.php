<?php

namespace App\Console;

use Illuminate\Console\Command as BaseCommand;
use Illuminate\Support\Facades\Log;

abstract class Command extends BaseCommand
{
    public function line($string, $style = null, $verbosity = null)
    {
        match ($style) {
            'error' => Log::error($string, ['command' => $this->getName()]),
            'warning' => Log::warning($string, ['command' => $this->getName()]),
            default => null,
        };

        parent::line($string, $style, $verbosity);
    }
}
