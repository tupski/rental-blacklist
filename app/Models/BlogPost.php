<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'category_id',
        'author_id',
        'status',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'canonical_url',
        'published_at',
        'views_count',
        'reading_time',
        'seo_score',
        'seo_analysis',
        'comments_enabled',
        'comments_require_approval',
        'comments_count',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'seo_analysis' => 'array'
    ];

    /**
     * Get the category for this post
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    /**
     * Get the author for this post
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the comments for the post
     */
    public function comments()
    {
        return $this->hasMany(BlogComment::class, 'post_id');
    }

    /**
     * Get approved comments for the post
     */
    public function approvedComments()
    {
        return $this->hasMany(BlogComment::class, 'post_id')->approved();
    }

    /**
     * Get pending comments for the post
     */
    public function pendingComments()
    {
        return $this->hasMany(BlogComment::class, 'post_id')->pending();
    }

    /**
     * Auto-generate slug from title
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;

        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = $this->generateUniqueSlug($value);
        }
    }

    /**
     * Generate unique slug
     */
    private function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Update slug manually
     */
    public function updateSlug($newSlug)
    {
        $slug = Str::slug($newSlug);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $this->update(['slug' => $slug]);
        return $slug;
    }

    /**
     * Calculate reading time based on content
     */
    public function calculateReadingTime()
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $readingTime = ceil($wordCount / 200); // Average reading speed: 200 words per minute

        $this->update(['reading_time' => $readingTime]);
        return $readingTime;
    }

    /**
     * Get the route key for the model
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Scope for published posts
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')->where('published_at', '<=', now());
    }

    /**
     * Scope for draft posts
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope for scheduled posts
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled')->where('published_at', '>', now());
    }

    /**
     * Get formatted published date
     */
    public function getFormattedPublishedDateAttribute()
    {
        if (!$this->published_at) {
            return null;
        }

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $date = $this->published_at;
        return $date->format('d') . ' ' . $months[$date->format('n')] . ' ' . $date->format('Y');
    }

    /**
     * Get excerpt or generate from content
     */
    public function getExcerptAttribute($value)
    {
        if ($value) {
            return $value;
        }

        return Str::limit(strip_tags($this->content), 160);
    }

    /**
     * Get SEO title or use post title
     */
    public function getSeoTitleAttribute($value)
    {
        return $value ?: $this->title;
    }

    /**
     * Get full URL for this post
     */
    public function getUrlAttribute()
    {
        return route('blog.detail', [
            'kategori' => $this->category->slug,
            'slug' => $this->slug
        ]);
    }

    /**
     * Increment views count
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Check if post is published
     */
    public function isPublished()
    {
        return $this->status === 'published' && $this->published_at <= now();
    }

    /**
     * Check if post is scheduled
     */
    public function isScheduled()
    {
        return $this->status === 'scheduled' && $this->published_at > now();
    }
}
