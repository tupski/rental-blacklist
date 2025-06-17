<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class BlogCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'meta_title',
        'meta_description',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get posts for this category
     */
    public function posts(): HasMany
    {
        return $this->hasMany(BlogPost::class, 'category_id');
    }

    /**
     * Get published posts for this category
     */
    public function publishedPosts(): HasMany
    {
        return $this->posts()->where('status', 'published')->where('published_at', '<=', now());
    }

    /**
     * Auto-generate slug from name
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        
        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = $this->generateUniqueSlug($value);
        }
    }

    /**
     * Generate unique slug
     */
    private function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Get the route key for the model
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Scope for active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered categories
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get posts count
     */
    public function getPostsCountAttribute()
    {
        return $this->publishedPosts()->count();
    }
}
