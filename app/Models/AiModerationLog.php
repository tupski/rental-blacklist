<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiModerationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_type',
        'content_id',
        'content_text',
        'analysis_results',
        'toxicity_score',
        'spam_score',
        'relevance_score',
        'privacy_risk_score',
        'overall_risk_score',
        'ai_decision',
        'ai_reasoning',
        'admin_decision',
        'admin_notes',
        'processed_at',
    ];

    protected $casts = [
        'analysis_results' => 'array',
        'toxicity_score' => 'decimal:4',
        'spam_score' => 'decimal:4',
        'relevance_score' => 'decimal:4',
        'privacy_risk_score' => 'decimal:4',
        'overall_risk_score' => 'decimal:4',
        'processed_at' => 'datetime',
    ];

    /**
     * Get the content being moderated
     */
    public function getContentAttribute()
    {
        switch ($this->content_type) {
            case 'registration':
                return User::find($this->content_id);
            case 'report':
                return RentalBlacklist::find($this->content_id);
            case 'user_profile':
                return User::find($this->content_id);
            default:
                return null;
        }
    }

    /**
     * Get risk level based on overall score
     */
    public function getRiskLevelAttribute(): string
    {
        if ($this->overall_risk_score >= 0.8) return 'critical';
        if ($this->overall_risk_score >= 0.6) return 'high';
        if ($this->overall_risk_score >= 0.4) return 'medium';
        return 'low';
    }

    /**
     * Get risk color for UI
     */
    public function getRiskColorAttribute(): string
    {
        switch ($this->risk_level) {
            case 'critical': return 'danger';
            case 'high': return 'warning';
            case 'medium': return 'info';
            default: return 'success';
        }
    }

    /**
     * Scope for pending admin review
     */
    public function scopePendingReview($query)
    {
        return $query->whereNull('admin_decision')
                    ->where('ai_decision', '!=', 'approve');
    }

    /**
     * Scope for high risk content
     */
    public function scopeHighRisk($query)
    {
        return $query->where('overall_risk_score', '>=', 0.6);
    }

    /**
     * Scope for recent logs
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Get analytics for dashboard
     */
    public static function getAnalytics(int $days = 30): array
    {
        $startDate = now()->subDays($days);

        return [
            'total_processed' => static::where('created_at', '>=', $startDate)->count(),
            'auto_approved' => static::where('created_at', '>=', $startDate)
                                   ->where('ai_decision', 'approve')->count(),
            'flagged_for_review' => static::where('created_at', '>=', $startDate)
                                         ->where('ai_decision', 'flag')->count(),
            'auto_rejected' => static::where('created_at', '>=', $startDate)
                                    ->where('ai_decision', 'reject')->count(),
            'avg_risk_score' => static::where('created_at', '>=', $startDate)
                                     ->avg('overall_risk_score'),
            'high_risk_count' => static::where('created_at', '>=', $startDate)
                                      ->where('overall_risk_score', '>=', 0.6)->count(),
            'content_type_breakdown' => static::where('created_at', '>=', $startDate)
                                             ->groupBy('content_type')
                                             ->selectRaw('content_type, count(*) as count')
                                             ->pluck('count', 'content_type')
                                             ->toArray(),
            'decision_breakdown' => static::where('created_at', '>=', $startDate)
                                         ->groupBy('ai_decision')
                                         ->selectRaw('ai_decision, count(*) as count')
                                         ->pluck('count', 'ai_decision')
                                         ->toArray(),
        ];
    }

    /**
     * Get daily trends
     */
    public static function getDailyTrends(int $days = 30): array
    {
        $startDate = now()->subDays($days);

        return static::where('created_at', '>=', $startDate)
                    ->groupBy(\DB::raw('DATE(created_at)'))
                    ->selectRaw('DATE(created_at) as date, 
                                count(*) as total,
                                avg(overall_risk_score) as avg_risk,
                                sum(case when ai_decision = "approve" then 1 else 0 end) as approved,
                                sum(case when ai_decision = "flag" then 1 else 0 end) as flagged,
                                sum(case when ai_decision = "reject" then 1 else 0 end) as rejected')
                    ->orderBy('date')
                    ->get()
                    ->toArray();
    }
}
