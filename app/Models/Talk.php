<?php

namespace App\Models;

use Carbon\Carbon;

class Talk {
    public string $title;
    public string $description;
    public string $image;
    public string $pdf_link;
    public string $pptx_link;
    public string $rsvp_link;
    public Carbon $rsvp_expiry;
    public bool $featured;
    public string $code_link;
    public string $recording_link;
    public string $demo_link;

    public function __construct(array $attributes = [])
    {
        $this->title = $attributes['title'] ?? '';
        $this->description = $attributes['description'] ?? '';
        $this->image = $attributes['image'] ?? '';
        $this->pdf_link = $attributes['pdf_link'] ?? '';
        $this->pptx_link = $attributes['pptx_link'] ?? '';
        $this->rsvp_link = $attributes['rsvp_link'] ?? '';
        $this->rsvp_expiry = Carbon::parse($attributes['rsvp_expiry'] ?? 'now');
        $this->featured = $attributes['featured'] ?? false;
        $this->code_link = $attributes['code_link'] ?? '';
        $this->recording_link = $attributes['recording_link'] ?? '';
        $this->demo_link = $attributes['demo_link'] ?? '';
    }
}
