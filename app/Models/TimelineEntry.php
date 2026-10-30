<?php

namespace App\Models;

use Carbon\Carbon;

class TimelineEntry {
    public string $title;
    public string $company;
    public string $company_url;
    public Carbon $start_date;
    public ?Carbon $end_date;
    public string $description;
    public array $buttons;
    public string $icon;

    public function __construct(array $attributes = [])
    {
        $this->title = $attributes['title'] ?? '';
        $this->company = $attributes['company'] ?? '';
        $this->company_url = $attributes['company_url'] ?? '';
        $this->start_date = Carbon::parse($attributes['start_date'] ?? 'now');
        $this->end_date = isset($attributes['end_date']) ? Carbon::parse($attributes['end_date']) : null;
        $this->description = $attributes['description'] ?? '';
        $this->buttons = $attributes['buttons'] ?? [];
        $this->icon = $attributes['icon'] ?? '';
    }

    public function getDateRangeString(): string
    {
        $start = $this->start_date->format('F Y');

        if ($this->end_date == null) {
            $end = 'present';
        }
        else {
            $end = $this->end_date->format('F Y');
        }

        return $start . ' – ' . $end;
    }
}
