<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogPostImage extends Model
{
    protected $fillable = [
        'post_id',
        'media_id',
        'original_url',
    ];

    /**
     * Get the post that owns the image.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class, 'post_id');
    }

    /**
     * Get the media for the image.
     */
    public function media(): BelongsTo
    {
        return $this->belongsTo(BlogMedia::class, 'media_id');
    }
}
