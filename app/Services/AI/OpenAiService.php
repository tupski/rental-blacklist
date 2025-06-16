<?php

namespace App\Services\AI;

use App\Models\AiProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAiService implements AiServiceInterface
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
            // Build messages array with system prompt
            $messages = $this->buildMessages($message, $context, $history);

            // Make API request
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->provider->api_key,
            ])->timeout(30)->post($this->provider->endpoint, [
                'model' => $this->provider->model,
                'messages' => $messages,
                'max_tokens' => 2000,
                'temperature' => 0.7,
                'top_p' => 1,
                'frequency_penalty' => 0,
                'presence_penalty' => 0,
            ]);

            $responseTime = (int)((microtime(true) - $startTime) * 1000);

            if (!$response->successful()) {
                $error = $response->json('error.message') ?? 'API request failed';
                Log::error('OpenAI API Error', [
                    'status' => $response->status(),
                    'error' => $error,
                    'response' => $response->body()
                ]);

                return AiResponse::error($error, $responseTime);
            }

            $data = $response->json();
            $content = $data['choices'][0]['message']['content'] ?? '';
            $tokensUsed = $data['usage']['total_tokens'] ?? 0;

            // Calculate cost
            $cost = $this->calculateCost($tokensUsed);

            return AiResponse::success(
                content: $content,
                tokensUsed: $tokensUsed,
                cost: $cost,
                responseTimeMs: $responseTime,
                metadata: [
                    'model' => $this->provider->model,
                    'prompt_tokens' => $data['usage']['prompt_tokens'] ?? 0,
                    'completion_tokens' => $data['usage']['completion_tokens'] ?? 0,
                    'finish_reason' => $data['choices'][0]['finish_reason'] ?? null,
                ]
            );

        } catch (\Exception $e) {
            $responseTime = (int)((microtime(true) - $startTime) * 1000);
            Log::error('OpenAI Service Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return AiResponse::error($e->getMessage(), $responseTime);
        }
    }

    private function buildMessages(string $message, array $context, array $history): array
    {
        $messages = [];

        // System message
        $systemPrompt = $this->buildSystemPrompt($context);
        $messages[] = [
            'role' => 'system',
            'content' => $systemPrompt
        ];

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

    private function calculateCost(int $tokens): float
    {
        // OpenAI pricing (approximate for GPT-3.5/4)
        // GPT-3.5: $0.002 per 1K tokens
        // GPT-4: $0.03 per 1K tokens
        $costPer1K = str_contains($this->provider->model, 'gpt-4') ? 0.03 : 0.002;
        return ($tokens / 1000) * $costPer1K;
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
