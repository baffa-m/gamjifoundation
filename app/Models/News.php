<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    use HasFactory;
    protected $fillable = [
        'author_id',
        'title',
        'slug',
        'description',
        'content',
        'featured_image',
        'category',
        'related_award_id',
        'is_published',
        'published_at'
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function relatedAward(): BelongsTo
    {
        return $this->belongsTo(Award::class, 'related_award_id');
    }

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function scopeUnpublished($query)
    {
        return $query->where('is_published', false)
                     ->orWhereNull('published_at')
                     ->orWhere('published_at', '>', now());
    }
}