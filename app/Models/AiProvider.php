<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class AiProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'api_key',
        'endpoint',
        'model',
        'daily_limit',
        'monthly_limit',
        'daily_usage',
        'monthly_usage',
        'last_reset_daily',
        'last_reset_monthly',
        'is_active',
        'priority',
        'rate_limits',
        'error_counts',
        'last_used_at',
    ];

    protected $casts = [
        'rate_limits' => 'array',
        'error_counts' => 'array',
        'last_reset_daily' => 'datetime',
        'last_reset_monthly' => 'datetime',
        'last_used_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Encrypt API key when storing
     */
    public function setApiKeyAttribute($value)
    {
        $this->attributes['api_key'] = Crypt::encryptString($value);
    }

    /**
     * Decrypt API key when retrieving
     */
    public function getApiKeyAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value; // Return as-is if decryption fails
        }
    }

    /**
     * Check if provider is available (not rate limited)
     */
    public function isAvailable(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // Reset counters if needed
        $this->resetCountersIfNeeded();

        // Check daily limit
        if ($this->daily_usage >= $this->daily_limit) {
            return false;
        }

        // Check monthly limit
        if ($this->monthly_usage >= $this->monthly_limit) {
            return false;
        }

        return true;
    }

    /**
     * Reset usage counters if time period has passed
     */
    public function resetCountersIfNeeded(): void
    {
        $now = now();

        // Reset daily counter
        if (!$this->last_reset_daily || $this->last_reset_daily->isYesterday()) {
            $this->update([
                'daily_usage' => 0,
                'last_reset_daily' => $now,
            ]);
        }

        // Reset monthly counter
        if (!$this->last_reset_monthly || $this->last_reset_monthly->month !== $now->month) {
            $this->update([
                'monthly_usage' => 0,
                'last_reset_monthly' => $now,
            ]);
        }
    }

    /**
     * Increment usage counters
     */
    public function incrementUsage(int $tokens = 1): void
    {
        $this->increment('daily_usage', $tokens);
        $this->increment('monthly_usage', $tokens);
        $this->update(['last_used_at' => now()]);
    }

    /**
     * Record error
     */
    public function recordError(string $error): void
    {
        $errors = $this->error_counts ?? [];
        $today = now()->format('Y-m-d');
        
        if (!isset($errors[$today])) {
            $errors[$today] = 0;
        }
        
        $errors[$today]++;
        
        // Keep only last 30 days
        $errors = array_slice($errors, -30, 30, true);
        
        $this->update(['error_counts' => $errors]);
    }

    /**
     * Get available providers ordered by priority
     */
    public static function getAvailable()
    {
        return static::where('is_active', true)
            ->orderBy('priority')
            ->orderBy('daily_usage')
            ->get()
            ->filter(fn($provider) => $provider->isAvailable());
    }

    /**
     * Get next available provider
     */
    public static function getNextAvailable(): ?self
    {
        return static::getAvailable()->first();
    }

    /**
     * Conversations relationship
     */
    public function conversations()
    {
        return $this->hasMany(ChatbotConversation::class, 'ai_provider', 'name');
    }
}
