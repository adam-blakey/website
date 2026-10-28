<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogMedia extends Model
{
    protected $fillable = [
        'filename',
        'original_url',
        'path',
        'path_small_webp',
        'path_medium_webp',
        'path_large_webp',
        'path_xlarge_webp',
        'mime_type',
    ];

    /**
     * Get the posts that use this media as featured media.
     */
    public function featuredInPosts(): HasMany
    {
        return $this->hasMany(BlogPost::class, 'featured_media_id');
    }

    /**
     * Get the post images that use this media.
     */
    public function postImages(): HasMany
    {
        return $this->hasMany(BlogPostImage::class, 'media_id');
    }

    /**
     * Get the URL for the media file.
     */
    public function getUrlAttribute(): string
    {
        return asset($this->path);
    }

    /**
     * Get the URL for the small webp version.
     */
    public function getSmallWebpUrlAttribute(): ?string
    {
        return $this->path_small_webp ? asset($this->path_small_webp) : null;
    }

    /**
     * Get the URL for the medium webp version.
     */
    public function getMediumWebpUrlAttribute(): ?string
    {
        return $this->path_medium_webp ? asset($this->path_medium_webp) : null;
    }

    /**
     * Get the URL for the large webp version.
     */
    public function getLargeWebpUrlAttribute(): ?string
    {
        return $this->path_large_webp ? asset($this->path_large_webp) : null;
    }

    /**
     * Get the URL for the extra large webp version.
     */
    public function getXlargeWebpUrlAttribute(): ?string
    {
        return $this->path_xlarge_webp ? asset($this->path_xlarge_webp) : null;
    }

    /**
     * Get the srcset attribute for responsive images.
     */
    public function getSrcsetAttribute(): ?string
    {
        $srcset = [];

        if ($this->small_webp_url) {
            $srcset[] = "{$this->small_webp_url} 320w";
        }

        if ($this->medium_webp_url) {
            $srcset[] = "{$this->medium_webp_url} 768w";
        }

        if ($this->large_webp_url) {
            $srcset[] = "{$this->large_webp_url} 1280w";
        }

        if ($this->xlarge_webp_url) {
            $srcset[] = "{$this->xlarge_webp_url} 1920w";
        }

        return !empty($srcset) ? implode(', ', $srcset) : null;
    }

    /**
     * Check if the media has responsive images.
     */
    public function getHasResponsiveImagesAttribute(): bool
    {
        return $this->path_small_webp !== null &&
               $this->path_medium_webp !== null &&
               $this->path_large_webp !== null &&
               $this->path_xlarge_webp !== null;
    }
}
