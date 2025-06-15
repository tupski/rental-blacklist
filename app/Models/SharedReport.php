<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SharedReport extends Model
{
    protected $fillable = [
        'token',
        'blacklist_id',
        'user_id',
        'password',
        'expires_at',
        'one_time_view',
        'is_accessed',
        'accessed_at',
        'access_ip'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'accessed_at' => 'datetime',
        'one_time_view' => 'boolean',
        'is_accessed' => 'boolean'
    ];

    public function blacklist(): BelongsTo
    {
        return $this->belongsTo(RentalBlacklist::class, 'blacklist_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate unique token for sharing
     */
    public static function generateToken(): string
    {
        do {
            $token = \Str::random(32);
        } while (self::where('token', $token)->exists());

        return $token;
    }

    /**
     * Check if shared report is still valid
     */
    public function isValid(): bool
    {
        // Check if expired
        if ($this->expires_at->isPast()) {
            return false;
        }

        // Check if one-time view and already accessed
        if ($this->one_time_view && $this->is_accessed) {
            return false;
        }

        return true;
    }

    /**
     * Verify password
     */
    public function verifyPassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }

    /**
     * Mark as accessed
     */
    public function markAsAccessed(string $ip = null): void
    {
        $this->update([
            'is_accessed' => true,
            'accessed_at' => now(),
            'access_ip' => $ip ?? request()->ip()
        ]);
    }

    /**
     * Get formatted expiry time
     */
    public function getFormattedExpiryAttribute(): string
    {
        return $this->expires_at->format('d/m/Y H:i');
    }

    /**
     * Check if user can access uncensored data
     */
    public function canAccessUncensoredData(): bool
    {
        $user = $this->user;
        
        // Rental owner can always share uncensored data
        if ($user->role === 'pengusaha_rental') {
            return true;
        }

        // Regular user can share uncensored data only if they have unlocked it
        if ($user->role === 'pengguna') {
            return $user->hasUnlockedData($this->blacklist_id) || 
                   $user->hasUnlockedNik($this->blacklist->nik);
        }

        return false;
    }
}
