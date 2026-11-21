<?php

use App\Helpers\ScheduleRuns;
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

it('records each scheduled run, keeping the latest few', function () {
    $outcomes = [...array_fill(0, ScheduleRuns::KEPT, 0), 1];
    Artisan::command('music:fetch', function () use (&$outcomes) {
        $this->error('Last.fm is down');

        return array_shift($outcomes);
    });
    $event = collect(app(Schedule::class)->events())->firstWhere('description', 'music:fetch');

    foreach (range(1, ScheduleRuns::KEPT) as $run) {
        $event->run(app());
    }
    expect(fn () => $event->run(app()))->toThrow(RuntimeException::class);

    $runs = ScheduleRuns::latestFirst('music:fetch');
    expect($runs)->toHaveCount(ScheduleRuns::KEPT)
        ->and($runs[0])->toMatchArray(['succeeded' => false, 'output' => 'Last.fm is down'])
        ->and($runs[1])->toMatchArray(['succeeded' => true, 'output' => null]);
});

it('records a scheduled run that throws', function () {
    Artisan::command('music:fetch', fn () => throw new LogicException('Bad config'));
    $event = collect(app(Schedule::class)->events())->firstWhere('description', 'music:fetch');

    expect(fn () => $event->run(app()))->toThrow(LogicException::class, 'Bad config');
    expect(ScheduleRuns::latestFirst('music:fetch')[0])->toMatchArray(['succeeded' => false, 'output' => 'Bad config']);
});
