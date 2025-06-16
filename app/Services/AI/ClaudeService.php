<?php

namespace App\Services\AI;

use App\Models\AiProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClaudeService implements AiServiceInterface
{
    private AiProvider $provider;

    public function __construct(AiProvider $provider)
    {
        $this->provider = $provider;
    }

    public function sendMessage(string $message, array $context = [], array $history = []): AiResponse
    {
        $startTime = microtime(true);

        try {
            // Build system prompt with context
            $systemPrompt = $this->buildSystemPrompt($context);

            // Build messages array
            $messages = $this->buildMessages($message, $history);

            // Make API request
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-api-key' => $this->provider->api_key,
                'anthropic-version' => '2023-06-01',
            ])->timeout(30)->post($this->provider->endpoint, [
                'model' => $this->provider->model,
                'max_tokens' => 2000,
                'system' => $systemPrompt,
                'messages' => $messages,
                'temperature' => 0.7,
            ]);

            $responseTime = (int)((microtime(true) - $startTime) * 1000);

            if (!$response->successful()) {
                $error = $response->json('error.message') ?? 'API request failed';
                Log::error('Claude API Error', [
                    'status' => $response->status(),
                    'error' => $error,
                    'response' => $response->body()
                ]);

                return AiResponse::error($error, $responseTime);
            }

            $data = $response->json();
            $content = $data['content'][0]['text'] ?? '';
            $tokensUsed = $data['usage']['input_tokens'] + $data['usage']['output_tokens'];

            // Calculate cost (approximate)
            $cost = $this->calculateCost($tokensUsed);

            return AiResponse::success(
                content: $content,
                tokensUsed: $tokensUsed,
                cost: $cost,
                responseTimeMs: $responseTime,
                metadata: [
                    'model' => $this->provider->model,
                    'input_tokens' => $data['usage']['input_tokens'],
                    'output_tokens' => $data['usage']['output_tokens'],
                ]
            );

        } catch (\Exception $e) {
            $responseTime = (int)((microtime(true) - $startTime) * 1000);
            Log::error('Claude Service Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return AiResponse::error($e->getMessage(), $responseTime);
        }
    }

    private function buildSystemPrompt(array $context): string
    {
        $basePrompt = "Anda adalah asisten AI untuk platform CekPenyewa.com, sistem blacklist rental Indonesia. ";
        $basePrompt .= "Anda membantu pengguna memahami cara menggunakan platform, fitur-fitur yang tersedia, ";
        $basePrompt .= "dan menjawab pertanyaan terkait sistem blacklist rental.\n\n";

        if (!empty($context)) {
            $basePrompt .= "Informasi relevan dari sistem:\n";
            foreach ($context as $item) {
                $basePrompt .= "- {$item['title']}: {$item['content']}\n";
            }
            $basePrompt .= "\n";
        }

        $basePrompt .= "Jawab dalam bahasa Indonesia dengan ramah dan informatif. ";
        $basePrompt .= "Jika pertanyaan di luar scope platform, arahkan kembali ke topik CekPenyewa.com.";

        return $basePrompt;
    }

    private function buildMessages(string $message, array $history): array
    {
        $messages = [];

        // Add conversation history
        if (!empty($history)) {
            foreach ($history as $item) {
                // Handle both array and object formats
                if (is_array($item)) {
                    if (isset($item['role']) && isset($item['content'])) {
                        $messages[] = [
                            'role' => $item['role'],
                            'content' => $item['content']
                        ];
                    }
                } elseif (is_object($item)) {
                    // Handle ChatbotConversation model
                    if (isset($item->user_message) && isset($item->ai_response)) {
                        $messages[] = [
                            'role' => 'user',
                            'content' => $item->user_message
                        ];
                        $messages[] = [
                            'role' => 'assistant',
                            'content' => $item->ai_response
                        ];
                    }
                }
            }
        }

        // Add current message
        $messages[] = [
            'role' => 'user',
            'content' => $message
        ];

        return $messages;
    }

    private function calculateCost(int $tokens): float
    {
        // Claude pricing (approximate)
        // Input: $0.008 per 1K tokens
        // Output: $0.024 per 1K tokens
        // Simplified calculation
        return ($tokens / 1000) * 0.016; // Average cost
    }

    public function getProviderName(): string
    {
        return $this->provider->name;
    }

    public function getModelName(): string
    {
        return $this->provider->model;
    }

    public function isAvailable(): bool
    {
        return $this->provider->isAvailable();
    }
}
