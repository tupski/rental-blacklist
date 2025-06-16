<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiModerationLog;
use App\Models\AiRiskProfile;
use App\Models\User;
use App\Models\RentalBlacklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AiAnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Show AI analytics dashboard
     */
    public function index(Request $request)
    {
        $days = $request->get('days', 30);
        
        // Get overview statistics
        $overview = $this->getOverviewStats($days);
        
        // Get moderation trends
        $trends = $this->getModerationTrends($days);
        
        // Get risk distribution
        $riskDistribution = $this->getRiskDistribution();
        
        // Get top flagged content
        $flaggedContent = $this->getTopFlaggedContent();
        
        // Get model performance
        $modelPerformance = $this->getModelPerformance();

        return view('admin.ai-analytics.index', compact(
            'overview',
            'trends',
            'riskDistribution',
            'flaggedContent',
            'modelPerformance',
            'days'
        ));
    }

    /**
     * Get moderation queue
     */
    public function moderationQueue(Request $request)
    {
        $query = AiModerationLog::with(['content'])
            ->whereNull('admin_decision')
            ->where('ai_decision', '!=', 'approve');

        // Filter by content type
        if ($request->has('content_type') && $request->content_type !== 'all') {
            $query->where('content_type', $request->content_type);
        }

        // Filter by risk level
        if ($request->has('risk_level') && $request->risk_level !== 'all') {
            switch ($request->risk_level) {
                case 'high':
                    $query->where('overall_risk_score', '>=', 0.6);
                    break;
                case 'medium':
                    $query->where('overall_risk_score', '>=', 0.4)
                          ->where('overall_risk_score', '<', 0.6);
                    break;
                case 'low':
                    $query->where('overall_risk_score', '<', 0.4);
                    break;
            }
        }

        $moderationQueue = $query->orderBy('overall_risk_score', 'desc')
                                ->orderBy('created_at', 'desc')
                                ->paginate(20);

        return view('admin.ai-analytics.moderation-queue', compact('moderationQueue'));
    }

    /**
     * Review moderation item
     */
    public function reviewModeration(Request $request, AiModerationLog $log)
    {
        $request->validate([
            'decision' => 'required|in:approve,reject',
            'notes' => 'nullable|string|max:1000',
        ]);

        $log->update([
            'admin_decision' => $request->decision,
            'admin_notes' => $request->notes,
        ]);

        // Update the actual content based on decision
        $this->applyModerationDecision($log, $request->decision);

        return response()->json([
            'success' => true,
            'message' => 'Review berhasil disimpan'
        ]);
    }

    /**
     * Get risk profiles
     */
    public function riskProfiles(Request $request)
    {
        $riskLevel = $request->get('risk_level', 'all');
        
        $query = AiRiskProfile::with('user');

        if ($riskLevel !== 'all') {
            $ranges = [
                'low' => [0, 0.4],
                'medium' => [0.4, 0.6],
                'high' => [0.6, 0.8],
                'critical' => [0.8, 1.0],
            ];

            if (isset($ranges[$riskLevel])) {
                [$min, $max] = $ranges[$riskLevel];
                $query->where('risk_score', '>=', $min)
                      ->where('risk_score', '<', $max);
            }
        }

        $riskProfiles = $query->orderBy('risk_score', 'desc')
                             ->paginate(20);

        return view('admin.ai-analytics.risk-profiles', compact('riskProfiles', 'riskLevel'));
    }

    /**
     * Get API data for charts
     */
    public function getChartData(Request $request)
    {
        $type = $request->get('type');
        $days = $request->get('days', 30);

        switch ($type) {
            case 'moderation_trends':
                return response()->json($this->getModerationTrends($days));
            
            case 'risk_distribution':
                return response()->json($this->getRiskDistribution());
            
            case 'content_types':
                return response()->json($this->getContentTypeBreakdown($days));
            
            case 'decision_breakdown':
                return response()->json($this->getDecisionBreakdown($days));
            
            default:
                return response()->json(['error' => 'Invalid chart type'], 400);
        }
    }

    /**
     * Get overview statistics
     */
    private function getOverviewStats(int $days): array
    {
        $startDate = now()->subDays($days);

        return [
            'total_processed' => AiModerationLog::where('created_at', '>=', $startDate)->count(),
            'auto_approved' => AiModerationLog::where('created_at', '>=', $startDate)
                                             ->where('ai_decision', 'approve')->count(),
            'flagged_for_review' => AiModerationLog::where('created_at', '>=', $startDate)
                                                  ->where('ai_decision', 'flag')->count(),
            'auto_rejected' => AiModerationLog::where('created_at', '>=', $startDate)
                                             ->where('ai_decision', 'reject')->count(),
            'pending_review' => AiModerationLog::whereNull('admin_decision')
                                              ->where('ai_decision', '!=', 'approve')->count(),
            'avg_risk_score' => AiModerationLog::where('created_at', '>=', $startDate)
                                              ->avg('overall_risk_score') ?? 0,
            'high_risk_users' => AiRiskProfile::where('risk_score', '>=', 0.6)->count(),
            'processing_accuracy' => $this->calculateProcessingAccuracy($days),
        ];
    }

    /**
     * Get moderation trends
     */
    private function getModerationTrends(int $days): array
    {
        return AiModerationLog::getDailyTrends($days);
    }

    /**
     * Get risk distribution
     */
    private function getRiskDistribution(): array
    {
        return [
            'low' => AiRiskProfile::where('risk_score', '<', 0.4)->count(),
            'medium' => AiRiskProfile::where('risk_score', '>=', 0.4)
                                   ->where('risk_score', '<', 0.6)->count(),
            'high' => AiRiskProfile::where('risk_score', '>=', 0.6)
                                  ->where('risk_score', '<', 0.8)->count(),
            'critical' => AiRiskProfile::where('risk_score', '>=', 0.8)->count(),
        ];
    }

    /**
     * Get top flagged content
     */
    private function getTopFlaggedContent(): array
    {
        return AiModerationLog::where('ai_decision', '!=', 'approve')
                             ->orderBy('overall_risk_score', 'desc')
                             ->limit(10)
                             ->get()
                             ->toArray();
    }

    /**
     * Get model performance metrics
     */
    private function getModelPerformance(): array
    {
        // This would be calculated based on admin reviews vs AI decisions
        $totalReviewed = AiModerationLog::whereNotNull('admin_decision')->count();
        
        if ($totalReviewed === 0) {
            return [
                'accuracy' => 0,
                'precision' => 0,
                'recall' => 0,
                'total_reviewed' => 0,
            ];
        }

        $correctPredictions = AiModerationLog::whereNotNull('admin_decision')
            ->whereRaw('(ai_decision = "approve" AND admin_decision = "approve") OR 
                       (ai_decision != "approve" AND admin_decision = "reject")')
            ->count();

        return [
            'accuracy' => $correctPredictions / $totalReviewed,
            'precision' => $this->calculatePrecision(),
            'recall' => $this->calculateRecall(),
            'total_reviewed' => $totalReviewed,
        ];
    }

    /**
     * Calculate processing accuracy
     */
    private function calculateProcessingAccuracy(int $days): float
    {
        $reviewed = AiModerationLog::where('created_at', '>=', now()->subDays($days))
                                  ->whereNotNull('admin_decision')
                                  ->count();

        if ($reviewed === 0) return 0;

        $correct = AiModerationLog::where('created_at', '>=', now()->subDays($days))
                                 ->whereNotNull('admin_decision')
                                 ->whereRaw('(ai_decision = "approve" AND admin_decision = "approve") OR 
                                           (ai_decision != "approve" AND admin_decision = "reject")')
                                 ->count();

        return $correct / $reviewed;
    }

    /**
     * Calculate precision
     */
    private function calculatePrecision(): float
    {
        $flaggedByAi = AiModerationLog::where('ai_decision', '!=', 'approve')
                                     ->whereNotNull('admin_decision')
                                     ->count();

        if ($flaggedByAi === 0) return 0;

        $correctlyFlagged = AiModerationLog::where('ai_decision', '!=', 'approve')
                                          ->where('admin_decision', 'reject')
                                          ->count();

        return $correctlyFlagged / $flaggedByAi;
    }

    /**
     * Calculate recall
     */
    private function calculateRecall(): float
    {
        $shouldBeFlagged = AiModerationLog::where('admin_decision', 'reject')
                                         ->count();

        if ($shouldBeFlagged === 0) return 0;

        $actuallyFlagged = AiModerationLog::where('admin_decision', 'reject')
                                         ->where('ai_decision', '!=', 'approve')
                                         ->count();

        return $actuallyFlagged / $shouldBeFlagged;
    }

    /**
     * Get content type breakdown
     */
    private function getContentTypeBreakdown(int $days): array
    {
        return AiModerationLog::where('created_at', '>=', now()->subDays($days))
                             ->groupBy('content_type')
                             ->selectRaw('content_type, count(*) as count')
                             ->pluck('count', 'content_type')
                             ->toArray();
    }

    /**
     * Get decision breakdown
     */
    private function getDecisionBreakdown(int $days): array
    {
        return AiModerationLog::where('created_at', '>=', now()->subDays($days))
                             ->groupBy('ai_decision')
                             ->selectRaw('ai_decision, count(*) as count')
                             ->pluck('count', 'ai_decision')
                             ->toArray();
    }

    /**
     * Apply moderation decision to actual content
     */
    private function applyModerationDecision(AiModerationLog $log, string $decision): void
    {
        switch ($log->content_type) {
            case 'registration':
                $user = User::find($log->content_id);
                if ($user) {
                    if ($decision === 'approve') {
                        $user->update(['email_verified_at' => now()]);
                    } else {
                        $user->update(['is_active' => false]);
                    }
                }
                break;

            case 'report':
                $report = RentalBlacklist::find($log->content_id);
                if ($report) {
                    if ($decision === 'approve') {
                        $report->update(['status' => 'published']);
                    } else {
                        $report->update(['status' => 'rejected']);
                    }
                }
                break;
        }
    }
}
