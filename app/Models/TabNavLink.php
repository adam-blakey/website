<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TabNavLink extends Model
{
    public string $name;
    public string $path;
    public string $icon;

    protected $fillable = ['name', 'path', 'icon'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->name = $attributes['name'] ?? '';
        $this->path = $attributes['path'] ?? '';
        $this->icon = $attributes['icon'] ?? '';
    }
}
