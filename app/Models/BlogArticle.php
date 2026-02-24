<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class BlogArticle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'type',
        'short_description',
        'content',
        'featured_image',
        'author_name',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status',
        'published_at',
        'sort_order',
        'views_count',
        'comments_count',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'meta_keywords' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
            
            if (empty($article->meta_title)) {
                $article->meta_title = $article->title;
            }
        });

        static::updating(function ($article) {
            if ($article->isDirty('title') && empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    // Relationships
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_article_blog_category');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(BlogTag::class, 'blog_article_blog_tag');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(BlogComment::class);
    }

    public function topLevelComments(): HasMany
    {
        return $this->hasMany(BlogComment::class)->whereNull('parent_id')->orderBy('created_at', 'desc');
    }

    // Scopes
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('status', 'scheduled')
            ->where('published_at', '>', now());
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc')
            ->orderBy('published_at', 'desc');
    }

    // Helpers
    public function isPublished(): bool
    {
        return $this->status === 'published' && $this->published_at <= now();
    }

    public function incrementViewCount(): void
    {
        $this->increment('views_count');
    }

    public function updateCommentsCount(): void
    {
        $this->comments_count = $this->comments()->count();
        $this->saveQuietly();
    }

    public function getSchemaMarkup(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $this->title,
            'description' => $this->meta_description ?? strip_tags($this->short_description),
            'image' => $this->featured_image ? asset('storage/' . $this->featured_image) : null,
            'author' => [
                '@type' => 'Person',
                'name' => $this->author_name,
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('app.name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('logo.png'),
                ],
            ],
            'datePublished' => $this->published_at?->toIso8601String(),
            'dateModified' => $this->updated_at->toIso8601String(),
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => route('blog.show', $this->slug),
            ],
        ];
    }
}