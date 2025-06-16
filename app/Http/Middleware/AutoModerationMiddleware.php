<?php

namespace App\Http\Middleware;

use App\Services\AI\ContentModerationService;
use App\Models\AiRiskProfile;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AutoModerationMiddleware
{
    private ContentModerationService $moderationService;

    public function __construct(ContentModerationService $moderationService)
    {
        $this->moderationService = $moderationService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $contentType = 'general')
    {
        // Skip moderation for admin users
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        try {
            // Extract content to moderate based on content type
            $contentText = $this->extractContentText($request, $contentType);
            
            if (empty($contentText)) {
                return $next($request);
            }

            // Get user risk profile if authenticated
            $userRiskScore = 0;
            if (Auth::check()) {
                $userRiskScore = $this->getUserRiskScore(Auth::user());
            }

            // Perform content moderation
            $moderation = $this->moderationService->moderateContent(
                $contentType,
                0, // Will be updated after content is created
                $contentText
            );

            // Adjust decision based on user risk
            $finalDecision = $this->adjustDecisionByUserRisk($moderation['decision'], $userRiskScore);

            // Handle moderation decision
            $response = $this->handleModerationDecision($request, $finalDecision, $moderation);
            
            if ($response) {
                return $response;
            }

            // Store moderation info in request for later use
            $request->merge([
                'ai_moderation' => $moderation,
                'ai_decision' => $finalDecision,
            ]);

            return $next($request);

        } catch (\Exception $e) {
            Log::error('Auto moderation middleware error', [
                'error' => $e->getMessage(),
                'content_type' => $contentType,
                'user_id' => Auth::id(),
            ]);

            // Continue without moderation on error
            return $next($request);
        }
    }

    /**
     * Extract content text from request
     */
    private function extractContentText(Request $request, string $contentType): string
    {
        switch ($contentType) {
            case 'registration':
                return $this->extractRegistrationContent($request);
            case 'report':
                return $this->extractReportContent($request);
            case 'profile':
                return $this->extractProfileContent($request);
            default:
                return '';
        }
    }

    /**
     * Extract registration content
     */
    private function extractRegistrationContent(Request $request): string
    {
        $content = [];
        
        // Basic profile information
        if ($request->has('name')) {
            $content[] = $request->input('name');
        }
        
        if ($request->has('bio')) {
            $content[] = $request->input('bio');
        }

        // For rental owner registration
        if ($request->has('business_name')) {
            $content[] = $request->input('business_name');
        }
        
        if ($request->has('business_description')) {
            $content[] = $request->input('business_description');
        }
        
        if ($request->has('address')) {
            $content[] = $request->input('address');
        }

        return implode(' ', array_filter($content));
    }

    /**
     * Extract report content
     */
    private function extractReportContent(Request $request): string
    {
        $content = [];
        
        if ($request->has('nama_penyewa')) {
            $content[] = $request->input('nama_penyewa');
        }
        
        if ($request->has('masalah')) {
            $content[] = $request->input('masalah');
        }
        
        if ($request->has('kronologi')) {
            $content[] = $request->input('kronologi');
        }
        
        if ($request->has('penanganan')) {
            $content[] = $request->input('penanganan');
        }
        
        if ($request->has('kesepakatan')) {
            $content[] = $request->input('kesepakatan');
        }

        return implode(' ', array_filter($content));
    }

    /**
     * Extract profile content
     */
    private function extractProfileContent(Request $request): string
    {
        $content = [];
        
        if ($request->has('name')) {
            $content[] = $request->input('name');
        }
        
        if ($request->has('bio')) {
            $content[] = $request->input('bio');
        }
        
        if ($request->has('description')) {
            $content[] = $request->input('description');
        }

        return implode(' ', array_filter($content));
    }

    /**
     * Get user risk score
     */
    private function getUserRiskScore($user): float
    {
        $riskProfile = AiRiskProfile::where('user_id', $user->id)->first();
        
        if (!$riskProfile) {
            // Calculate risk score for new user
            return AiRiskProfile::calculateRiskScore($user);
        }

        // Recalculate if profile is old (more than 7 days)
        if ($riskProfile->last_calculated_at < now()->subDays(7)) {
            return AiRiskProfile::calculateRiskScore($user);
        }

        return $riskProfile->risk_score;
    }

    /**
     * Adjust decision based on user risk
     */
    private function adjustDecisionByUserRisk(string $aiDecision, float $userRiskScore): string
    {
        // High-risk users get stricter moderation
        if ($userRiskScore >= 0.7) {
            if ($aiDecision === 'approve') {
                return 'flag'; // High-risk users need manual review even for good content
            }
            if ($aiDecision === 'quarantine') {
                return 'reject'; // High-risk users get rejected for suspicious content
            }
        }

        // Low-risk users get more lenient treatment
        if ($userRiskScore <= 0.2) {
            if ($aiDecision === 'flag') {
                return 'approve'; // Low-risk users get approved for borderline content
            }
            if ($aiDecision === 'quarantine') {
                return 'flag'; // Low-risk users get flagged instead of quarantined
            }
        }

        return $aiDecision;
    }

    /**
     * Handle moderation decision
     */
    private function handleModerationDecision(Request $request, string $decision, array $moderation)
    {
        switch ($decision) {
            case 'reject':
                return $this->rejectContent($moderation);
            
            case 'quarantine':
                return $this->quarantineContent($moderation);
            
            case 'flag':
                // Continue but mark for review
                return null;
            
            case 'approve':
            default:
                // Continue normally
                return null;
        }
    }

    /**
     * Reject content
     */
    private function rejectContent(array $moderation)
    {
        return response()->json([
            'success' => false,
            'message' => 'Konten Anda tidak dapat diproses karena melanggar kebijakan platform.',
            'reason' => $moderation['reasoning'],
            'risk_score' => $moderation['risk_score'],
            'suggestions' => [
                'Pastikan konten relevan dengan laporan rental',
                'Hindari penggunaan bahasa yang tidak pantas',
                'Jangan sertakan informasi pribadi yang sensitif',
                'Fokus pada fakta dan pengalaman objektif'
            ]
        ], 422);
    }

    /**
     * Quarantine content
     */
    private function quarantineContent(array $moderation)
    {
        return response()->json([
            'success' => false,
            'message' => 'Konten Anda sedang dalam review untuk memastikan kesesuaian dengan kebijakan platform.',
            'reason' => $moderation['reasoning'],
            'estimated_review_time' => '24-48 jam',
            'contact_support' => 'Jika Anda merasa ini adalah kesalahan, silakan hubungi tim support.'
        ], 202); // 202 Accepted but processing
    }
}
