<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserUnlock extends Model
{
    protected $fillable = [
        'user_id',
        'blacklist_id',
        'amount_paid',
        'unlocked_at'
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'unlocked_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function blacklist(): BelongsTo
    {
        return $this->belongsTo(RentalBlacklist::class, 'blacklist_id');
    }

    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount_paid, 0, ',', '.');
    }

    // Check if user has unlocked specific blacklist data
    public static function hasUnlocked($userId, $blacklistId)
    {
        return self::where('user_id', $userId)
                   ->where('blacklist_id', $blacklistId)
                   ->exists();
    }

    // Get all unlocked blacklist IDs for a user
    public static function getUnlockedIds($userId)
    {
        return self::where('user_id', $userId)
                   ->pluck('blacklist_id')
                   ->toArray();
    }
}
