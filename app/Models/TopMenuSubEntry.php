<?php

namespace App\Models;

class TopMenuSubEntry
{
    protected $fillable = ['name', 'url', 'icon', 'use-simple-icons', 'enabled'];

    public function __construct($attributes = [])
    {
        $this->name = $attributes['name'] ?? '';
        $this->url = $attributes['url'] ?? '';
        $this->icon = $attributes['icon'] ?? '';
        $this->use_simple_icons = $attributes['use-simple-icons'] ?? false;
        $this->enabled = $attributes['enabled'] ?? true;
    }
}
