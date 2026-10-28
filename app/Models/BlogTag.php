<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BlogTag extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Get the posts that belong to the tag.
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(BlogPost::class, 'blog_post_tag', 'tag_id', 'post_id');
    }

    /**
     * Get the URL for the tag.
     */
    public function getUrlAttribute(): string
    {
        return route('blog.tag', $this->slug, false);
    }
}
