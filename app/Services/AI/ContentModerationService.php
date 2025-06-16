<?php

namespace App\Services\AI;

use App\Models\AiModerationLog;
use App\Models\AiRiskProfile;
use App\Models\User;
use App\Models\RentalBlacklist;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ContentModerationService
{
    private array $toxicWords;
    private array $spamPatterns;
    private array $privateInfoPatterns;

    public function __construct()
    {
        $this->loadModerationRules();
    }

    /**
     * Moderate content and return decision
     */
    public function moderateContent(string $contentType, int $contentId, string $text): array
    {
        try {
            // Extract and analyze content
            $analysis = $this->analyzeContent($text);
            
            // Calculate overall risk score
            $overallRisk = $this->calculateOverallRisk($analysis);
            
            // Make AI decision
            $decision = $this->makeDecision($overallRisk, $analysis);
            
            // Generate reasoning
            $reasoning = $this->generateReasoning($analysis, $decision);
            
            // Log the moderation
            $log = AiModerationLog::create([
                'content_type' => $contentType,
                'content_id' => $contentId,
                'content_text' => $text,
                'analysis_results' => $analysis,
                'toxicity_score' => $analysis['toxicity_score'],
                'spam_score' => $analysis['spam_score'],
                'relevance_score' => $analysis['relevance_score'],
                'privacy_risk_score' => $analysis['privacy_risk_score'],
                'overall_risk_score' => $overallRisk,
                'ai_decision' => $decision,
                'ai_reasoning' => $reasoning,
                'processed_at' => now(),
            ]);

            return [
                'decision' => $decision,
                'risk_score' => $overallRisk,
                'reasoning' => $reasoning,
                'analysis' => $analysis,
                'log_id' => $log->id,
            ];

        } catch (\Exception $e) {
            Log::error('Content moderation failed', [
                'content_type' => $contentType,
                'content_id' => $contentId,
                'error' => $e->getMessage()
            ]);

            return [
                'decision' => 'flag',
                'risk_score' => 0.5,
                'reasoning' => 'Gagal menganalisis konten, perlu review manual',
                'analysis' => [],
                'log_id' => null,
            ];
        }
    }

    /**
     * Analyze content for various risk factors
     */
    private function analyzeContent(string $text): array
    {
        $text = strtolower(trim($text));
        
        return [
            'toxicity_score' => $this->analyzeToxicity($text),
            'spam_score' => $this->analyzeSpam($text),
            'relevance_score' => $this->analyzeRelevance($text),
            'privacy_risk_score' => $this->analyzePrivacyRisk($text),
            'sentiment_score' => $this->analyzeSentiment($text),
            'language_quality' => $this->analyzeLanguageQuality($text),
            'content_length' => strlen($text),
            'word_count' => str_word_count($text),
            'detected_patterns' => $this->detectPatterns($text),
        ];
    }

    /**
     * Analyze toxicity in text
     */
    private function analyzeToxicity(string $text): float
    {
        $toxicityScore = 0;
        $totalWords = str_word_count($text);
        
        if ($totalWords === 0) return 0;

        // Check for toxic words
        $toxicWordCount = 0;
        foreach ($this->toxicWords as $word) {
            if (strpos($text, $word) !== false) {
                $toxicWordCount++;
            }
        }

        // Check for aggressive patterns
        $aggressivePatterns = [
            '/\b(bodoh|tolol|goblok|idiot)\b/i',
            '/\b(bangsat|anjing|babi)\b/i',
            '/\b(mati|bunuh|hancur)\b/i',
            '/[A-Z]{3,}/', // ALL CAPS (aggressive)
            '/!{3,}/', // Multiple exclamation marks
        ];

        $aggressiveCount = 0;
        foreach ($aggressivePatterns as $pattern) {
            if (preg_match($pattern, $text)) {
                $aggressiveCount++;
            }
        }

        $toxicityScore = ($toxicWordCount / $totalWords) + ($aggressiveCount * 0.1);
        
        return min($toxicityScore, 1.0);
    }

    /**
     * Analyze spam characteristics
     */
    private function analyzeSpam(string $text): float
    {
        $spamScore = 0;

        // Check for spam patterns
        foreach ($this->spamPatterns as $pattern) {
            if (preg_match($pattern, $text)) {
                $spamScore += 0.2;
            }
        }

        // Check for repetitive content
        $words = explode(' ', $text);
        $wordCounts = array_count_values($words);
        $maxRepetition = max($wordCounts);
        $totalWords = count($words);
        
        if ($totalWords > 0 && $maxRepetition / $totalWords > 0.3) {
            $spamScore += 0.3; // Too much repetition
        }

        // Check for promotional content
        $promoPatterns = [
            '/\b(promo|diskon|gratis|murah|terbaik)\b/i',
            '/\b(hubungi|call|wa|whatsapp)\b/i',
            '/\b(jual|beli|sewa|rental)\b/i',
            '/\d{4,}/', // Long numbers (phone numbers)
        ];

        foreach ($promoPatterns as $pattern) {
            if (preg_match($pattern, $text)) {
                $spamScore += 0.1;
            }
        }

        return min($spamScore, 1.0);
    }

    /**
     * Analyze content relevance to rental reports
     */
    private function analyzeRelevance(string $text): float
    {
        $relevantKeywords = [
            'sewa', 'rental', 'penyewa', 'mobil', 'motor', 'kamera',
            'rusak', 'hilang', 'telat', 'tidak kembali', 'bermasalah',
            'pembayaran', 'deposit', 'kerusakan', 'accident',
            'sopan', 'ramah', 'baik', 'recommended', 'lancar'
        ];

        $relevanceScore = 0;
        $foundKeywords = 0;

        foreach ($relevantKeywords as $keyword) {
            if (strpos($text, $keyword) !== false) {
                $foundKeywords++;
            }
        }

        $relevanceScore = min($foundKeywords / 5, 1.0); // Max at 5 keywords

        // Penalty for very short content
        if (strlen($text) < 50) {
            $relevanceScore *= 0.5;
        }

        return $relevanceScore;
    }

    /**
     * Analyze privacy risk (personal information exposure)
     */
    private function analyzePrivacyRisk(string $text): float
    {
        $privacyRisk = 0;

        foreach ($this->privateInfoPatterns as $pattern) {
            if (preg_match($pattern, $text)) {
                $privacyRisk += 0.3;
            }
        }

        return min($privacyRisk, 1.0);
    }

    /**
     * Analyze sentiment
     */
    private function analyzeSentiment(string $text): float
    {
        $positiveWords = ['baik', 'bagus', 'ramah', 'sopan', 'lancar', 'recommended', 'puas'];
        $negativeWords = ['buruk', 'jelek', 'kasar', 'tidak sopan', 'bermasalah', 'kecewa'];

        $positiveCount = 0;
        $negativeCount = 0;

        foreach ($positiveWords as $word) {
            if (strpos($text, $word) !== false) {
                $positiveCount++;
            }
        }

        foreach ($negativeWords as $word) {
            if (strpos($text, $word) !== false) {
                $negativeCount++;
            }
        }

        if ($positiveCount + $negativeCount === 0) {
            return 0.5; // Neutral
        }

        return $positiveCount / ($positiveCount + $negativeCount);
    }

    /**
     * Analyze language quality
     */
    private function analyzeLanguageQuality(string $text): float
    {
        $quality = 1.0;

        // Check for excessive typos or poor grammar indicators
        $poorQualityIndicators = [
            '/\b\w{1,2}\b/', // Too many very short words
            '/[a-z][A-Z]/', // Mixed case within words
            '/\d+[a-zA-Z]/', // Numbers mixed with letters
        ];

        foreach ($poorQualityIndicators as $pattern) {
            $matches = preg_match_all($pattern, $text);
            if ($matches > 0) {
                $quality -= ($matches * 0.1);
            }
        }

        return max($quality, 0);
    }

    /**
     * Detect specific patterns
     */
    private function detectPatterns(string $text): array
    {
        $patterns = [];

        // Detect contact information
        if (preg_match('/\b\d{4,}\b/', $text)) {
            $patterns[] = 'contains_numbers';
        }

        // Detect URLs
        if (preg_match('/https?:\/\//', $text)) {
            $patterns[] = 'contains_url';
        }

        // Detect email
        if (preg_match('/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b/', $text)) {
            $patterns[] = 'contains_email';
        }

        return $patterns;
    }

    /**
     * Calculate overall risk score
     */
    private function calculateOverallRisk(array $analysis): float
    {
        $weights = [
            'toxicity_score' => 0.3,
            'spam_score' => 0.25,
            'privacy_risk_score' => 0.25,
            'relevance_penalty' => 0.2, // Low relevance increases risk
        ];

        $riskScore = 0;
        $riskScore += $analysis['toxicity_score'] * $weights['toxicity_score'];
        $riskScore += $analysis['spam_score'] * $weights['spam_score'];
        $riskScore += $analysis['privacy_risk_score'] * $weights['privacy_risk_score'];
        $riskScore += (1 - $analysis['relevance_score']) * $weights['relevance_penalty'];

        return min($riskScore, 1.0);
    }

    /**
     * Make moderation decision
     */
    private function makeDecision(float $riskScore, array $analysis): string
    {
        // Auto-reject for very high risk
        if ($riskScore >= 0.8) {
            return 'reject';
        }

        // Auto-approve for very low risk
        if ($riskScore <= 0.2) {
            return 'approve';
        }

        // Flag for manual review for medium risk
        if ($riskScore >= 0.4) {
            return 'flag';
        }

        // Quarantine for suspicious but not clearly bad content
        return 'quarantine';
    }

    /**
     * Generate human-readable reasoning
     */
    private function generateReasoning(array $analysis, string $decision): string
    {
        $reasons = [];

        if ($analysis['toxicity_score'] > 0.5) {
            $reasons[] = 'Konten mengandung bahasa yang tidak pantas atau agresif';
        }

        if ($analysis['spam_score'] > 0.5) {
            $reasons[] = 'Konten terdeteksi sebagai spam atau promosi';
        }

        if ($analysis['privacy_risk_score'] > 0.5) {
            $reasons[] = 'Konten mengandung informasi pribadi yang berisiko';
        }

        if ($analysis['relevance_score'] < 0.3) {
            $reasons[] = 'Konten tidak relevan dengan laporan rental';
        }

        if (empty($reasons)) {
            switch ($decision) {
                case 'approve':
                    return 'Konten aman dan relevan untuk dipublikasikan';
                case 'flag':
                    return 'Konten memerlukan review manual untuk memastikan kesesuaian';
                case 'quarantine':
                    return 'Konten mencurigakan, perlu verifikasi lebih lanjut';
                default:
                    return 'Konten tidak memenuhi standar platform';
            }
        }

        return implode('. ', $reasons);
    }

    /**
     * Load moderation rules
     */
    private function loadModerationRules(): void
    {
        $this->toxicWords = [
            'bodoh', 'tolol', 'goblok', 'idiot', 'bangsat', 'anjing', 'babi',
            'kampret', 'sialan', 'bajingan', 'keparat', 'tai', 'shit', 'fuck'
        ];

        $this->spamPatterns = [
            '/\b(promo|diskon|gratis|murah|terbaik)\b/i',
            '/\b(hubungi|call|wa|whatsapp)\b/i',
            '/\b(jual|beli|sewa|rental)\b.*\b(murah|terbaik|promo)\b/i',
            '/\d{4,}/', // Phone numbers
        ];

        $this->privateInfoPatterns = [
            '/\b\d{16}\b/', // Credit card numbers
            '/\b\d{3,4}[-.\s]?\d{3,4}[-.\s]?\d{4}\b/', // Phone numbers
            '/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b/', // Email
            '/\b\d{1,5}\s\w+\s(street|st|avenue|ave|road|rd|lane|ln)\b/i', // Addresses
        ];
    }
}
