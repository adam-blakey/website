<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BlogCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Get the posts that belong to the category.
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(BlogPost::class, 'blog_post_category', 'category_id', 'post_id');
    }

    /**
     * Get the URL for the category.
     */
    public function getUrlAttribute(): string
    {
        return route('blog.category', $this->slug, false);
    }
}
