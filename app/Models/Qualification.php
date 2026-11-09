<?php

namespace App\Models;

use Carbon\Carbon;

class Qualification {
    public string $title;
    public string $issuer;
    public Carbon $date;
    public ?Carbon $expires;
    public ?string $result;
    public ?string $credentialId;
    public ?string $link;
    public string $icon;

    public function __construct(array $attributes = [])
    {
        $this->title = $attributes['title'] ?? '';
        $this->issuer = $attributes['issuer'] ?? '';
        $this->date = Carbon::parse($attributes['date'] ?? 'now');
        $this->expires = isset($attributes['expires']) ? Carbon::parse($attributes['expires']) : null;
        $this->result = $attributes['result'] ?? null;
        $this->credentialId = $attributes['credential_id'] ?? null;
        $this->link = $attributes['link'] ?? null;
        $this->icon = $attributes['icon'] ?? 'certificate';
    }
}
