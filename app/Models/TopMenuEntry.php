<?php

namespace App\Models;

class TopMenuEntry
{
    protected $fillable = ['name', 'subEntries'];

    public function __construct($attributes = [])
    {
        $this->name = $attributes['name'] ?? '';
        $this->subEntries = $attributes['subEntries'] ?? [];
    }
}
