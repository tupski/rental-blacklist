<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'user_ip',
        'user_message',
        'bot_response',
        'ai_provider',
        'model_used',
        'context_data',
        'tokens_used',
        'cost',
        'response_time_ms',
        'status',
        'error_message',
        'metadata',
    ];

    protected $casts = [
        'context_data' => 'array',
        'metadata' => 'array',
        'cost' => 'decimal:6',
    ];

    /**
     * User relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * AI Provider relationship
     */
    public function provider()
    {
        return $this->belongsTo(AiProvider::class, 'ai_provider', 'name');
    }

    /**
     * Get conversation history for session
     */
    public static function getSessionHistory(string $sessionId, int $limit = 10): array
    {
        $conversations = static::where('session_id', $sessionId)
            ->where('status', 'success')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->reverse();

        $history = [];

        foreach ($conversations as $conversation) {
            // Add user message
            $history[] = [
                'role' => 'user',
                'content' => $conversation->user_message,
                'timestamp' => $conversation->created_at,
            ];

            // Add assistant response
            $history[] = [
                'role' => 'assistant',
                'content' => $conversation->ai_response,
                'timestamp' => $conversation->created_at,
            ];
        }

        return $history;
    }

    /**
     * Get analytics data
     */
    public static function getAnalytics(int $days = 30): array
    {
        $startDate = now()->subDays($days);

        return [
            'total_conversations' => static::where('created_at', '>=', $startDate)->count(),
            'successful_conversations' => static::where('created_at', '>=', $startDate)
                ->where('status', 'success')->count(),
            'error_rate' => static::where('created_at', '>=', $startDate)
                ->where('status', 'error')->count(),
            'avg_response_time' => static::where('created_at', '>=', $startDate)
                ->where('status', 'success')
                ->avg('response_time_ms'),
            'total_tokens' => static::where('created_at', '>=', $startDate)
                ->sum('tokens_used'),
            'total_cost' => static::where('created_at', '>=', $startDate)
                ->sum('cost'),
            'provider_usage' => static::where('created_at', '>=', $startDate)
                ->groupBy('ai_provider')
                ->selectRaw('ai_provider, count(*) as count')
                ->pluck('count', 'ai_provider')
                ->toArray(),
        ];
    }

    /**
     * Scope for successful conversations
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope for recent conversations
     */
    public function scopeRecent($query, int $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }
}
