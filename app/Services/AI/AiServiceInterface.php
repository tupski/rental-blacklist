<?php

namespace App\Services\AI;

interface AiServiceInterface
{
    /**
     * Send message to AI provider
     */
    public function sendMessage(string $message, array $context = [], array $history = []): AiResponse;

    /**
     * Get provider name
     */
    public function getProviderName(): string;

    /**
     * Get model name
     */
    public function getModelName(): string;

    /**
     * Check if provider is available
     */
    public function isAvailable(): bool;
}
