<?php

namespace App\Models;

use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogPost extends Model
{
    protected $fillable = [
        'wordpress_id',
        'title',
        'slug',
        'content',
        'featured_media_id',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Get the featured media for the blog post.
     */
    public function featuredMedia(): BelongsTo
    {
        return $this->belongsTo(BlogMedia::class, 'featured_media_id');
    }

    /**
     * Get the categories for the blog post.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_post_category', 'post_id', 'category_id');
    }

    /**
     * Get the tags for the blog post.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(BlogTag::class, 'blog_post_tag', 'post_id', 'tag_id');
    }

    /**
     * Get the images embedded in the blog post content.
     */
    public function contentImages(): HasMany
    {
        return $this->hasMany(BlogPostImage::class, 'post_id');
    }

    /**
     * Get the URL for the blog post.
     */
    public function getUrlAttribute(): string
    {
        return route('blog.show', $this->slug, false);
    }

    /**
     * Get the formatted published date.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->published_at->format('jS F Y');
    }

    public function getSafeContentAttribute(): string
    {
        return self::contentPurifier()->purify($this->content);
    }

    private static function contentPurifier(): HTMLPurifier
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.TargetBlank', true);
        $config->set('HTML.DefinitionID', 'blog-post-content');
        $config->set('HTML.DefinitionRev', 1);

        if ($definition = $config->maybeGetRawHTMLDefinition()) {
            $definition->addElement('video', 'Inline', 'Optional: #PCDATA | Inline | source', 'Common', [
                'src' => 'URI',
                'poster' => 'URI',
                'preload' => 'Enum#auto,metadata,none',
                'width' => 'Length',
                'height' => 'Length',
                'controls' => 'Bool',
                'muted' => 'Bool',
                'loop' => 'Bool',
                'playsinline' => 'Bool',
            ]);
            $definition->addElement('source', 'Block', 'Empty', 'Common', [
                'src*' => 'URI',
                'type' => 'Text',
            ]);
        }

        return new HTMLPurifier($config);
    }
}
