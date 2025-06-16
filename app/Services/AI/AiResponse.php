<?php

namespace App\Services\AI;

class AiResponse
{
    public function __construct(
        public readonly string $content,
        public readonly bool $success,
        public readonly ?string $error = null,
        public readonly ?int $tokensUsed = null,
        public readonly ?float $cost = null,
        public readonly ?int $responseTimeMs = null,
        public readonly ?array $metadata = null
    ) {}

    /**
     * Create successful response
     */
    public static function success(
        string $content,
        ?int $tokensUsed = null,
        ?float $cost = null,
        ?int $responseTimeMs = null,
        ?array $metadata = null
    ): self {
        return new self(
            content: $content,
            success: true,
            tokensUsed: $tokensUsed,
            cost: $cost,
            responseTimeMs: $responseTimeMs,
            metadata: $metadata
        );
    }

    /**
     * Create error response
     */
    public static function error(string $error, ?int $responseTimeMs = null): self
    {
        return new self(
            content: '',
            success: false,
            error: $error,
            responseTimeMs: $responseTimeMs
        );
    }

    /**
     * Check if response is successful
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * Check if response is error
     */
    public function isError(): bool
    {
        return !$this->success;
    }

    /**
     * Get response content
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Get error message
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * Convert to array
     */
    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'success' => $this->success,
            'error' => $this->error,
            'tokens_used' => $this->tokensUsed,
            'cost' => $this->cost,
            'response_time_ms' => $this->responseTimeMs,
            'metadata' => $this->metadata,
        ];
    }
}
