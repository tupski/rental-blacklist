<?php

namespace App\Services\AI;

use App\Models\AiProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService implements AiServiceInterface
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
            // Build the prompt with context and history
            $prompt = $this->buildPrompt($message, $context, $history);

            // Make API request - use correct Gemini 1.5 Flash model
            $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=' . $this->provider->api_key;

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 2000,
                ],
                'safetySettings' => [
                    [
                        'category' => 'HARM_CATEGORY_HARASSMENT',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                    ],
                    [
                        'category' => 'HARM_CATEGORY_HATE_SPEECH',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                    ],
                    [
                        'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                    ],
                    [
                        'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                    ]
                ]
            ]);

            $responseTime = (int)((microtime(true) - $startTime) * 1000);

            if (!$response->successful()) {
                $error = $response->json('error.message') ?? 'API request failed';
                Log::error('Gemini API Error', [
                    'status' => $response->status(),
                    'error' => $error,
                    'response' => $response->body()
                ]);

                return AiResponse::error($error, $responseTime);
            }

            $data = $response->json();

            if (empty($data['candidates'])) {
                return AiResponse::error('No response from Gemini', $responseTime);
            }

            $content = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

            // Gemini doesn't provide token usage in response, estimate it
            $tokensUsed = $this->estimateTokens($prompt . $content);

            // Calculate cost
            $cost = $this->calculateCost($tokensUsed);

            return AiResponse::success(
                content: $content,
                tokensUsed: $tokensUsed,
                cost: $cost,
                responseTimeMs: $responseTime,
                metadata: [
                    'model' => $this->provider->model,
                    'finish_reason' => $data['candidates'][0]['finishReason'] ?? null,
                    'safety_ratings' => $data['candidates'][0]['safetyRatings'] ?? [],
                ]
            );

        } catch (\Exception $e) {
            $responseTime = (int)((microtime(true) - $startTime) * 1000);
            Log::error('Gemini Service Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return AiResponse::error($e->getMessage(), $responseTime);
        }
    }

    private function buildPrompt(string $message, array $context, array $history): string
    {
        $prompt = "Anda adalah asisten AI untuk platform CekPenyewa.com, sistem blacklist rental Indonesia. ";
        $prompt .= "Anda membantu pengguna memahami cara menggunakan platform, fitur-fitur yang tersedia, ";
        $prompt .= "dan menjawab pertanyaan terkait sistem blacklist rental.\n\n";

        if (!empty($context)) {
            $prompt .= "Informasi relevan dari sistem:\n";
            foreach ($context as $item) {
                $prompt .= "- {$item['title']}: {$item['content']}\n";
            }
            $prompt .= "\n";
        }

        if (!empty($history)) {
            $prompt .= "Riwayat percakapan:\n";
            foreach ($history as $item) {
                if (isset($item['role']) && isset($item['content'])) {
                    $role = $item['role'] === 'user' ? 'Pengguna' : 'Asisten';
                    $prompt .= "{$role}: {$item['content']}\n";
                }
            }
            $prompt .= "\n";
        }

        $prompt .= "Jawab dalam bahasa Indonesia dengan ramah dan informatif. ";
        $prompt .= "Jika pertanyaan di luar scope platform, arahkan kembali ke topik CekPenyewa.com.\n\n";
        $prompt .= "Pertanyaan: {$message}";

        return $prompt;
    }

    private function estimateTokens(string $text): int
    {
        // Rough estimation: 1 token ≈ 4 characters for Indonesian text
        return (int) ceil(strlen($text) / 4);
    }

    private function calculateCost(int $tokens): float
    {
        // Gemini pricing (approximate)
        // Gemini Pro: $0.0005 per 1K tokens
        return ($tokens / 1000) * 0.0005;
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
