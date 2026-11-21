<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ScheduleRuns;
use App\Http\Controllers\Controller;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Console\Kernel;

class ScheduleController extends Controller
{
    public function __invoke(Kernel $console, Schedule $schedule)
    {
        // Web requests never load routes/console.php, where the schedule is defined.
        $console->bootstrap();

        $tasks = collect($schedule->events())->map(fn ($event) => [
            'name' => $event->description,
            'expression' => $event->expression,
            'everyMinute' => $event->expression === '* * * * *',
            'nextRunAt' => $event->nextRunDate(),
            'runs' => ScheduleRuns::latestFirst($event->description),
        ]);

        return view('dashboard.schedule', ['tasks' => $tasks]);
    }
}
