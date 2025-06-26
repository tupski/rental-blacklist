<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\UserUnlock;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'account_status',
        'approved_at',
        'approved_by',
        'nik',
        'no_hp',
        'alamat',
        'is_banned',
        'banned_reason',
        'banned_at',
        'banned_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'approved_at' => 'datetime',
            'banned_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function apiKeys()
    {
        return $this->hasMany(ApiKey::class);
    }

    public function getActiveApiKey()
    {
        return $this->apiKeys()->where('is_active', true)->first();
    }

    public function createApiKey($name = 'Default API Key')
    {
        // Deactivate existing keys
        $this->apiKeys()->update(['is_active' => false]);

        // Create new key
        return $this->apiKeys()->create([
            'name' => $name,
            'key' => ApiKey::generateKey(),
            'is_active' => true
        ]);
    }



    // Relasi user unlocks
    public function userUnlocks(): HasMany
    {
        return $this->hasMany(UserUnlock::class);
    }

    // Relasi shared reports
    public function sharedReports(): HasMany
    {
        return $this->hasMany(SharedReport::class);
    }

    // Relasi rental registration
    public function rentalRegistration()
    {
        return $this->hasOne(RentalRegistration::class);
    }



    // Check if user has unlocked specific blacklist data
    public function hasUnlockedData($blacklistId)
    {
        return UserUnlock::hasUnlocked($this->id, $blacklistId);
    }

    // Check if user has unlocked any data for specific NIK
    public function hasUnlockedNik($nik)
    {
        return UserUnlock::where('user_id', $this->id)
            ->whereHas('blacklist', function($query) use ($nik) {
                $query->where('nik', $nik);
            })
            ->exists();
    }

    // Get all unlocked blacklist IDs for this user
    public function getUnlockedDataIds()
    {
        return UserUnlock::getUnlockedIds($this->id);
    }



    /**
     * Check if account is active
     */
    public function isActive()
    {
        return $this->account_status === 'active';
    }

    /**
     * Check if account is pending approval
     */
    public function isPending()
    {
        return $this->account_status === 'pending';
    }

    /**
     * Check if account is suspended
     */
    public function isSuspended()
    {
        return $this->account_status === 'suspended';
    }

    /**
     * Check if account is banned
     */
    public function isBanned()
    {
        return $this->is_banned;
    }

    /**
     * Approve account
     */
    public function approve($approvedBy = null)
    {
        $this->update([
            'account_status' => 'active',
            'approved_at' => now(),
            'approved_by' => $approvedBy
        ]);
    }

    /**
     * Suspend account
     */
    public function suspend()
    {
        $this->update([
            'account_status' => 'suspended'
        ]);
    }

    /**
     * Ban account
     */
    public function ban($reason, $bannedBy = null)
    {
        $this->update([
            'is_banned' => true,
            'banned_reason' => $reason,
            'banned_at' => now(),
            'banned_by' => $bannedBy
        ]);
    }

    /**
     * Unban account
     */
    public function unban()
    {
        $this->update([
            'is_banned' => false,
            'banned_reason' => null,
            'banned_at' => null,
            'banned_by' => null
        ]);
    }

    /**
     * Get approved by user
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get banned by user
     */
    public function bannedBy()
    {
        return $this->belongsTo(User::class, 'banned_by');
    }

    /**
     * Check if user can access data (not censored)
     */
    public function canAccessData()
    {
        // Admin always can access data
        if ($this->role === 'admin') {
            return true;
        }

        // Must be active
        if (!$this->isActive()) {
            return false;
        }

        // Must have verified email if required
        if ($this->requiresEmailVerification()) {
            return false;
        }

        // Rental owners can access data if active and email verified
        if ($this->role === 'pengusaha_rental') {
            return true;
        }

        return false;
    }

    /**
     * Check if user can search data
     */
    public function canSearchData()
    {
        // Must have full features access
        return $this->canAccessFullFeatures();
    }

    /**
     * Check if user can use API
     */
    public function canUseApi()
    {
        // Must have full features access
        return $this->canAccessFullFeatures();
    }

    /**
     * Check if user can edit profile
     */
    public function canEditProfile()
    {
        // Must be active and email verified (if required)
        if (!$this->isActive()) {
            return false;
        }

        $requireEmailVerification = Setting::get('require_email_verification', '1') === '1';
        if ($requireEmailVerification && !$this->hasVerifiedEmail()) {
            return false;
        }

        return true;
    }

    /**
     * Check if email verification is required
     */
    public function requiresEmailVerification()
    {
        $requireEmailVerification = Setting::get('require_email_verification', '1') === '1';
        return $requireEmailVerification && !$this->hasVerifiedEmail();
    }

    /**
     * Check if user can access full features
     */
    public function canAccessFullFeatures()
    {
        // Must not be banned
        if ($this->isBanned()) {
            return false;
        }

        // Must be active
        if (!$this->isActive()) {
            return false;
        }

        // Must have verified email if required
        if ($this->requiresEmailVerification()) {
            return false;
        }

        return true;
    }


}
