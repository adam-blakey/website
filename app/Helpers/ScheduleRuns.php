<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class ScheduleRuns
{
    public const KEPT = 30;

    public static function record(string $task, bool $succeeded, string $output): void
    {
        Cache::forever(self::key($task), array_slice([
            ['ran_at' => now()->toIso8601String(), 'succeeded' => $succeeded, 'output' => $succeeded ? null : $output],
            ...self::latestFirst($task),
        ], 0, self::KEPT));
    }

    public static function latestFirst(string $task): array
    {
        return Cache::get(self::key($task), []);
    }

    private static function key(string $task): string
    {
        return "schedule-runs:{$task}";
    }
}
