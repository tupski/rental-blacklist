<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotKnowledgeBase extends Model
{
    use HasFactory;

    protected $table = 'chatbot_knowledge_base';

    protected $fillable = [
        'category',
        'title',
        'content',
        'keywords',
        'related_routes',
        'related_models',
        'priority',
        'is_active',
        'usage_count',
        'last_used_at',
    ];

    protected $casts = [
        'keywords' => 'array',
        'related_routes' => 'array',
        'related_models' => 'array',
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    /**
     * Search knowledge base by keywords
     */
    public static function search(string $query): \Illuminate\Database\Eloquent\Collection
    {
        $keywords = explode(' ', strtolower($query));
        
        return static::where('is_active', true)
            ->where(function ($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->orWhere('title', 'like', "%{$keyword}%")
                      ->orWhere('content', 'like', "%{$keyword}%")
                      ->orWhereJsonContains('keywords', $keyword);
                }
            })
            ->orderBy('priority')
            ->orderBy('usage_count', 'desc')
            ->get();
    }

    /**
     * Get relevant context for AI
     */
    public static function getRelevantContext(string $userMessage): array
    {
        $relevant = static::search($userMessage);
        
        return $relevant->map(function ($item) {
            $item->increment('usage_count');
            $item->update(['last_used_at' => now()]);
            
            return [
                'category' => $item->category,
                'title' => $item->title,
                'content' => $item->content,
                'keywords' => $item->keywords,
            ];
        })->toArray();
    }

    /**
     * Get all categories
     */
    public static function getCategories(): array
    {
        return static::where('is_active', true)
            ->distinct()
            ->pluck('category')
            ->toArray();
    }

    /**
     * Scope for active items
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope by category
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
