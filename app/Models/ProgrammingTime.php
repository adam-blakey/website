<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgrammingTime extends Model
{
    protected $fillable = [
        'time_text',
        'total_seconds',
        'fetched_at',
    ];

    protected $casts = [
        'fetched_at' => 'datetime',
    ];

    /**
     * Get the latest programming time record.
     *
     * @return \App\Models\ProgrammingTime|null
     */
    public static function getLatest()
    {
        return self::latest('fetched_at')->first();
    }
}
