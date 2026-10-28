<?php

namespace App\Models;

use Carbon\Carbon;

class Award {
    public string $title;
    public Carbon $date;
    public string $description;
    public ?string $image;
    public ?string $link;

    public bool $featured;

    public function __construct(array $attributes = [])
    {
        $this->title = $attributes['title'] ?? '';
        $this->date = $attributes['date'] ?? Carbon::now();
        $this->description = $attributes['description'] ?? '';
        $this->image = $attributes['image'] ?? null;
        $this->link = $attributes['link'] ?? null;
        $this->featured = $attributes['featured'] ?? false;
    }

    public function getDateString(): string
    {
        return $this->date->format('F Y');
    }
}
