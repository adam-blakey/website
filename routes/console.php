<?php

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

$artisan = fn (string $command) => function () use ($command) {
    if (Artisan::call($command) !== Command::SUCCESS) {
        throw new RuntimeException("{$command} failed: ".trim(Artisan::output()));
    }
};

Schedule::call($artisan('app:send-contact-confirmation-emails'))->name('app:send-contact-confirmation-emails')->everyMinute();
Schedule::call($artisan('music:fetch'))->name('music:fetch')->everyMinute();
Schedule::call($artisan('programming-time:fetch'))->name('programming-time:fetch')->everyMinute();

Schedule::call($artisan('blog:import-wordpress'))->name('blog:import-wordpress')->hourly();
Schedule::call($artisan('blog:generate-responsive-images'))->name('blog:generate-responsive-images')->hourly();
Schedule::call($artisan('concerts:import'))->name('concerts:import')->hourly();

Schedule::call($artisan('music:clear-old'))->name('music:clear-old')->hourly();
