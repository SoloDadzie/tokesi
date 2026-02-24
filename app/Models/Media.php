<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Media extends Model
{
    protected $table = 'media_library';
    
    protected $fillable = [
        'path',
        'filename',
        'original_filename',
        'mime_type',
        'size',
        'size_human',
        'type',
        'disk',
        'collection',
        'alt_text',
        'caption',
        'width',
        'height',
        'dimensions',
        'tags',
    ];

    protected $casts = [
        'tags' => 'array',
        'width' => 'integer',
        'height' => 'integer',
        'size' => 'integer',
    ];

    /**
     * Get articles where this media is used as featured image
     */
    public function blogArticles(): HasMany
    {
        return $this->hasMany(BlogArticle::class, 'featured_image', 'path');
    }

    /**
     * Check if media is used anywhere
     */
    public function isUsed(): bool
    {
        return BlogArticle::where('featured_image', $this->path)
            ->orWhere('content', 'like', '%' . $this->path . '%')
            ->exists();
    }

    /**
     * Get usage count
     */
    public function getUsageCountAttribute(): int
    {
        return BlogArticle::where('featured_image', $this->path)
            ->orWhere('content', 'like', '%' . $this->path . '%')
            ->count();
    }

    /**
     * Get full URL
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }
}