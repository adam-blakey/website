<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Concert extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'start_datetime',
        'end_datetime',
        'location',
        'poster_url',
        'ticket_url',
        'ensemble',
        'external_id',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
    ];

    /**
     * Get the location for the concert.
     */
    protected function location(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => html_entity_decode($value, ENT_QUOTES)
        );
    }

    /**
     * Get the URL for the concert.
     */
    public function getUrlAttribute(): string
    {
        return route('interests.music', [], false) . '#concert-' . $this->id;
    }

    /**
     * Get the formatted start date.
     */
    public function getFormattedStartDateAttribute(): string
    {
        return $this->start_datetime->format('jS F Y');
    }

    /**
     * Get the formatted start time.
     */
    public function getFormattedStartTimeAttribute(): string
    {
        return $this->start_datetime->format('g:i A');
    }

    /**
     * Get the formatted end time.
     */
    public function getFormattedEndTimeAttribute(): ?string
    {
        return $this->end_datetime ? $this->end_datetime->format('g:i A') : null;
    }

    /**
     * Get the formatted time range.
     */
    public function getFormattedTimeRangeAttribute(): string
    {
        $startTime = $this->formatted_start_time;
        $endTime = $this->formatted_end_time;

        return $endTime ? "$startTime – $endTime" : $startTime;
    }

    /**
     * Scope a query to only include upcoming concerts.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_datetime', '>=', Carbon::now())->orderBy('start_datetime', 'asc');
    }

    /**
     * Scope a query to only include past concerts.
     */
    public function scopePast($query)
    {
        return $query->where('start_datetime', '<', Carbon::now())->orderBy('start_datetime', 'desc');
    }

    /**
     * Get the next upcoming concert.
     */
    public static function getNextConcert()
    {
        return self::upcoming()->first();
    }
}
