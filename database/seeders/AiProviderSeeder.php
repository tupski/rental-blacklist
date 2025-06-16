<?php

namespace Database\Seeders;

use App\Models\AiProvider;
use Illuminate\Database\Seeder;

class AiProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providers = [
            [
                'name' => 'claude',
                'display_name' => 'Claude (Anthropic)',
                'api_key' => 'your-claude-api-key-here',
                'endpoint' => 'https://api.anthropic.com/v1/messages',
                'model' => 'claude-3-haiku-20240307',
                'daily_limit' => 1000,
                'monthly_limit' => 25000,
                'priority' => 1,
                'is_active' => false, // Set to false until API key is configured
                'rate_limits' => [
                    'requests_per_minute' => 60,
                    'tokens_per_minute' => 100000,
                ],
            ],
            [
                'name' => 'openai',
                'display_name' => 'ChatGPT (OpenAI)',
                'api_key' => 'your-openai-api-key-here',
                'endpoint' => 'https://api.openai.com/v1/chat/completions',
                'model' => 'gpt-3.5-turbo',
                'daily_limit' => 1500,
                'monthly_limit' => 40000,
                'priority' => 2,
                'is_active' => false, // Set to false until API key is configured
                'rate_limits' => [
                    'requests_per_minute' => 60,
                    'tokens_per_minute' => 90000,
                ],
            ],
            [
                'name' => 'gemini',
                'display_name' => 'Gemini (Google)',
                'api_key' => 'AIzaSyAYWP15JRHglOrfacequglAWw2JEr6-e-o',
                'endpoint' => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent',
                'model' => 'gemini-pro',
                'daily_limit' => 2000,
                'monthly_limit' => 50000,
                'priority' => 1, // Set as priority 1 since it's the only working key
                'is_active' => true, // Set to true since API key is configured
                'rate_limits' => [
                    'requests_per_minute' => 60,
                    'tokens_per_minute' => 120000,
                ],
            ],
        ];

        foreach ($providers as $provider) {
            AiProvider::updateOrCreate(
                ['name' => $provider['name']],
                $provider
            );
        }
    }
}
