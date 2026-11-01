<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Artisan;

it('throws with the command output when a scheduled command fails', function () {
    Artisan::command('music:fetch', function () {
        $this->error('Last.fm is down');

        return 1;
    });
    $event = collect(app(Schedule::class)->events())->firstWhere('description', 'music:fetch');

    expect(fn () => $event->run(app()))->toThrow(RuntimeException::class, 'music:fetch failed: Last.fm is down');
});
