<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class DocumentVerification extends Model
{
    protected $fillable = [
        'verification_code',
        'blacklist_id',
        'user_id',
        'document_type',
        'generated_at',
        'verified_at',
        'user_agent',
        'ip_address'
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function blacklist(): BelongsTo
    {
        return $this->belongsTo(RentalBlacklist::class, 'blacklist_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function generateVerificationCode(): string
    {
        do {
            $code = strtoupper(Str::random(8) . '-' . Str::random(8) . '-' . Str::random(8));
        } while (self::where('verification_code', $code)->exists());

        return $code;
    }

    public function markAsVerified(): void
    {
        $this->update([
            'verified_at' => now(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    public function isVerified(): bool
    {
        return !is_null($this->verified_at);
    }
}
