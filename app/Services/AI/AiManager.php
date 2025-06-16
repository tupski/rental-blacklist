<?php

namespace App\Services\AI;

use App\Models\AiProvider;
use App\Models\ChatbotConversation;
use App\Models\ChatbotKnowledgeBase;
use Illuminate\Support\Facades\Log;

class AiManager
{
    private array $serviceMap = [
        'claude' => ClaudeService::class,
        'openai' => OpenAiService::class,
        'gemini' => GeminiService::class,
    ];

    /**
     * Send message with automatic provider rotation
     */
    public function sendMessage(
        string $message,
        string $sessionId,
        ?int $userId = null,
        string $userIp = '127.0.0.1'
    ): AiResponse {
        try {
            // Get relevant context from knowledge base
            $context = ChatbotKnowledgeBase::getRelevantContext($message);

            // Get conversation history
            $history = ChatbotConversation::getSessionHistory($sessionId, 5);

            // Try providers in order of priority - only those with valid API keys
            $providers = AiProvider::where('is_active', true)
                ->where('api_key', '!=', '')
                ->where('api_key', 'not like', '%your-%')
                ->orderBy('priority')
                ->orderBy('daily_usage')
                ->get()
                ->filter(fn($provider) => $provider->isAvailable());

            if ($providers->isEmpty()) {
                return AiResponse::error('Tidak ada AI provider yang tersedia. Silakan hubungi administrator untuk mengaktifkan layanan AI.');
            }

            $lastError = null;

            foreach ($providers as $provider) {
                try {
                    // Create service instance
                    $service = $this->createService($provider);

                    if (!$service->isAvailable()) {
                        continue;
                    }

                    // Send message
                    $response = $service->sendMessage($message, $context, $history);

                    if ($response->isSuccess()) {
                        // Update provider usage
                        $provider->incrementUsage($response->tokensUsed ?? 1);

                        // Save conversation
                        $this->saveConversation(
                            $sessionId,
                            $userId,
                            $userIp,
                            $message,
                            $response,
                            $provider,
                            $context
                        );

                        return $response;
                    } else {
                        // Record error
                        $provider->recordError($response->getError() ?? 'Unknown error');
                        $lastError = $response->getError();

                        // Try next provider
                        continue;
                    }

                } catch (\Exception $e) {
                    Log::error('AI Service Error', [
                        'provider' => $provider->name,
                        'error' => $e->getMessage()
                    ]);

                    $provider->recordError($e->getMessage());
                    $lastError = $e->getMessage();
                    continue;
                }
            }

            // All providers failed
            return AiResponse::error($lastError ?? 'All AI providers failed');

        } catch (\Exception $e) {
            Log::error('AI Manager Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return AiResponse::error('Internal error: ' . $e->getMessage());
        }
    }

    /**
     * Create service instance for provider
     */
    private function createService(AiProvider $provider): AiServiceInterface
    {
        $serviceClass = $this->serviceMap[$provider->name] ?? null;

        if (!$serviceClass || !class_exists($serviceClass)) {
            throw new \Exception("Service not found for provider: {$provider->name}");
        }

        return new $serviceClass($provider);
    }

    /**
     * Save conversation to database
     */
    private function saveConversation(
        string $sessionId,
        ?int $userId,
        string $userIp,
        string $message,
        AiResponse $response,
        AiProvider $provider,
        array $context
    ): void {
        try {
            ChatbotConversation::create([
                'session_id' => $sessionId,
                'user_id' => $userId,
                'user_ip' => $userIp,
                'user_message' => $message,
                'bot_response' => $response->getContent(),
                'ai_provider' => $provider->name,
                'model_used' => $provider->model,
                'context_data' => $context,
                'tokens_used' => $response->tokensUsed,
                'cost' => $response->cost,
                'response_time_ms' => $response->responseTimeMs,
                'status' => $response->isSuccess() ? 'success' : 'error',
                'error_message' => $response->getError(),
                'metadata' => $response->metadata,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to save conversation', [
                'error' => $e->getMessage(),
                'session_id' => $sessionId
            ]);
        }
    }

    /**
     * Get available providers
     */
    public function getAvailableProviders(): \Illuminate\Database\Eloquent\Collection
    {
        return AiProvider::getAvailable();
    }

    /**
     * Get provider statistics
     */
    public function getProviderStats(): array
    {
        $providers = AiProvider::all();
        $stats = [];

        foreach ($providers as $provider) {
            $stats[$provider->name] = [
                'name' => $provider->display_name,
                'is_active' => $provider->is_active,
                'is_available' => $provider->isAvailable(),
                'daily_usage' => $provider->daily_usage,
                'daily_limit' => $provider->daily_limit,
                'monthly_usage' => $provider->monthly_usage,
                'monthly_limit' => $provider->monthly_limit,
                'usage_percentage' => $provider->daily_limit > 0
                    ? round(($provider->daily_usage / $provider->daily_limit) * 100, 2)
                    : 0,
                'last_used' => $provider->last_used_at?->diffForHumans(),
                'error_count_today' => $this->getTodayErrorCount($provider),
            ];
        }

        return $stats;
    }

    /**
     * Get today's error count for provider
     */
    private function getTodayErrorCount(AiProvider $provider): int
    {
        $errors = $provider->error_counts ?? [];
        $today = now()->format('Y-m-d');

        return $errors[$today] ?? 0;
    }

    /**
     * Test provider connection
     */
    public function testProvider(AiProvider $provider): AiResponse
    {
        try {
            $service = $this->createService($provider);
            return $service->sendMessage(
                'Halo, ini adalah test koneksi. Jawab dengan singkat "Test berhasil".',
                [],
                []
            );
        } catch (\Exception $e) {
            return AiResponse::error($e->getMessage());
        }
    }

    /**
     * Check if chatbot is available (has at least one working provider)
     */
    public function isChatbotAvailable(): bool
    {
        // Check if chatbot is enabled in settings
        $chatbotEnabled = \App\Models\Setting::where('key', 'chatbot_enabled')->value('value') ?? 'true';

        if ($chatbotEnabled !== 'true') {
            return false;
        }

        return AiProvider::where('is_active', true)
            ->where('api_key', '!=', '')
            ->where('api_key', 'not like', '%your-%')
            ->exists();
    }

    /**
     * Get chatbot status for frontend
     */
    public function getChatbotStatus(): array
    {
        $available = $this->isChatbotAvailable();
        $providers = AiProvider::where('is_active', true)
            ->where('api_key', '!=', '')
            ->where('api_key', 'not like', '%your-%')
            ->get();

        return [
            'available' => $available,
            'provider_count' => $providers->count(),
            'providers' => $providers->pluck('display_name')->toArray(),
        ];
    }
}
