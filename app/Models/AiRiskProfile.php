<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiRiskProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'risk_score',
        'risk_factors',
        'total_reports',
        'approved_reports',
        'rejected_reports',
        'flagged_reports',
        'avg_content_quality',
        'last_calculated_at',
    ];

    protected $casts = [
        'risk_factors' => 'array',
        'risk_score' => 'decimal:4',
        'avg_content_quality' => 'decimal:4',
        'last_calculated_at' => 'datetime',
    ];

    /**
     * User relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get risk level
     */
    public function getRiskLevelAttribute(): string
    {
        if ($this->risk_score >= 0.8) return 'critical';
        if ($this->risk_score >= 0.6) return 'high';
        if ($this->risk_score >= 0.4) return 'medium';
        return 'low';
    }

    /**
     * Get risk badge color
     */
    public function getRiskBadgeAttribute(): string
    {
        switch ($this->risk_level) {
            case 'critical': return 'badge-danger';
            case 'high': return 'badge-warning';
            case 'medium': return 'badge-info';
            default: return 'badge-success';
        }
    }

    /**
     * Calculate risk score for user
     */
    public static function calculateRiskScore(User $user): float
    {
        $factors = [];
        $weights = [
            'report_history' => 0.4,
            'content_quality' => 0.3,
            'behavioral_patterns' => 0.2,
            'profile_completeness' => 0.1,
        ];

        // 1. Report History Analysis
        $reportHistory = static::analyzeReportHistory($user);
        $factors['report_history'] = $reportHistory;

        // 2. Content Quality Analysis
        $contentQuality = static::analyzeContentQuality($user);
        $factors['content_quality'] = $contentQuality;

        // 3. Behavioral Patterns
        $behavioralPatterns = static::analyzeBehavioralPatterns($user);
        $factors['behavioral_patterns'] = $behavioralPatterns;

        // 4. Profile Completeness
        $profileCompleteness = static::analyzeProfileCompleteness($user);
        $factors['profile_completeness'] = $profileCompleteness;

        // Calculate weighted score
        $riskScore = 0;
        foreach ($factors as $factor => $score) {
            $riskScore += $score * $weights[$factor];
        }

        // Update or create risk profile
        static::updateOrCreate(
            ['user_id' => $user->id],
            [
                'risk_score' => $riskScore,
                'risk_factors' => $factors,
                'last_calculated_at' => now(),
            ]
        );

        return $riskScore;
    }

    /**
     * Analyze report history
     */
    private static function analyzeReportHistory(User $user): float
    {
        $reports = RentalBlacklist::where('user_id', $user->id)->get();
        
        if ($reports->isEmpty()) {
            return 0.1; // New users get low risk
        }

        $totalReports = $reports->count();
        $recentReports = $reports->where('created_at', '>=', now()->subMonths(3))->count();
        
        // High frequency of reports = higher risk
        $frequencyScore = min($totalReports / 10, 1.0); // Max at 10 reports
        $recencyScore = min($recentReports / 5, 1.0); // Max at 5 recent reports
        
        return ($frequencyScore + $recencyScore) / 2;
    }

    /**
     * Analyze content quality
     */
    private static function analyzeContentQuality(User $user): float
    {
        $moderationLogs = AiModerationLog::where('content_type', 'report')
            ->whereIn('content_id', function($query) use ($user) {
                $query->select('id')
                      ->from('rental_blacklists')
                      ->where('user_id', $user->id);
            })->get();

        if ($moderationLogs->isEmpty()) {
            return 0.2; // Neutral for new users
        }

        $avgToxicity = $moderationLogs->avg('toxicity_score');
        $avgSpam = $moderationLogs->avg('spam_score');
        $avgRelevance = 1 - $moderationLogs->avg('relevance_score'); // Lower relevance = higher risk
        
        return ($avgToxicity + $avgSpam + $avgRelevance) / 3;
    }

    /**
     * Analyze behavioral patterns
     */
    private static function analyzeBehavioralPatterns(User $user): float
    {
        $reports = RentalBlacklist::where('user_id', $user->id)
                                 ->orderBy('created_at')
                                 ->get();

        if ($reports->count() < 2) {
            return 0.1;
        }

        $riskFactors = 0;
        $totalFactors = 0;

        // Check for rapid-fire reporting (multiple reports in short time)
        $rapidReports = 0;
        for ($i = 1; $i < $reports->count(); $i++) {
            $timeDiff = $reports[$i]->created_at->diffInHours($reports[$i-1]->created_at);
            if ($timeDiff < 24) {
                $rapidReports++;
            }
        }
        
        if ($rapidReports > 0) {
            $riskFactors += min($rapidReports / $reports->count(), 0.5);
        }
        $totalFactors++;

        // Check for unusual timing patterns
        $nightReports = $reports->filter(function($report) {
            $hour = $report->created_at->hour;
            return $hour >= 23 || $hour <= 5; // 11PM to 5AM
        })->count();
        
        if ($nightReports > $reports->count() * 0.7) {
            $riskFactors += 0.3; // Mostly night reports = suspicious
        }
        $totalFactors++;

        return $totalFactors > 0 ? $riskFactors / $totalFactors : 0.1;
    }

    /**
     * Analyze profile completeness
     */
    private static function analyzeProfileCompleteness(User $user): float
    {
        $completeness = 0;
        $totalFields = 0;

        // Check basic profile fields
        $fields = ['name', 'email', 'phone'];
        foreach ($fields as $field) {
            $totalFields++;
            if (!empty($user->$field)) {
                $completeness++;
            }
        }

        // Check email verification
        $totalFields++;
        if ($user->email_verified_at) {
            $completeness++;
        }

        // Check if has rental registration (for rental owners)
        if ($user->role === 'pengusaha_rental') {
            $totalFields++;
            if ($user->rentalRegistration) {
                $completeness++;
            }
        }

        $completenessRatio = $completeness / $totalFields;
        
        // Lower completeness = higher risk
        return 1 - $completenessRatio;
    }

    /**
     * Get users by risk level
     */
    public static function getUsersByRiskLevel(string $level): \Illuminate\Database\Eloquent\Collection
    {
        $ranges = [
            'low' => [0, 0.4],
            'medium' => [0.4, 0.6],
            'high' => [0.6, 0.8],
            'critical' => [0.8, 1.0],
        ];

        if (!isset($ranges[$level])) {
            return collect();
        }

        [$min, $max] = $ranges[$level];
        
        return static::with('user')
                    ->where('risk_score', '>=', $min)
                    ->where('risk_score', '<', $max)
                    ->orderBy('risk_score', 'desc')
                    ->get();
    }
}
