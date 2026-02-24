<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_article_id',
        'parent_id',
        'author_name',
        'author_email',
        'content',
        'is_admin',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($comment) {
            $comment->article->updateCommentsCount();
        });

        static::deleted(function ($comment) {
            $comment->article->updateCommentsCount();
        });
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(BlogArticle::class, 'blog_article_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(BlogComment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(BlogComment::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    public function isReply(): bool
    {
        return !is_null($this->parent_id);
    }
}