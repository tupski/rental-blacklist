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
        'suspension_reason',
        'suspension_type',
        'suspension_days',
        'suspended_at',
        'suspension_ends_at',
        'suspended_by',
        'revision_notes',
        'revision_requested_at',
        'revision_requested_by',
        'activation_email_sent_at',
        'activation_email_count',
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
            'suspended_at' => 'datetime',
            'suspension_ends_at' => 'datetime',
            'revision_requested_at' => 'datetime',
            'activation_email_sent_at' => 'datetime',
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

    // Relasi rental blacklists (laporan yang dibuat user)
    public function rentalBlacklists()
    {
        return $this->hasMany(RentalBlacklist::class);
    }

    // Relasi sponsors (untuk badge sponsor)
    public function sponsors()
    {
        return $this->hasMany(\App\Models\Sponsor::class);
    }

    // Relasi donations (untuk badge donatur)
    public function donations()
    {
        return $this->hasMany(\App\Models\Donation::class, 'donor_email', 'email');
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

    // Unlock blacklist data (free for rental owners)
    public function unlockData($blacklistId)
    {
        // Check if already unlocked
        if ($this->hasUnlockedData($blacklistId)) {
            throw new \Exception('Data sudah dibuka sebelumnya');
        }

        // Create unlock record (free for rental owners)
        $unlock = $this->userUnlocks()->create([
            'blacklist_id' => $blacklistId,
            'amount_paid' => 0, // Free for rental owners
            'unlocked_at' => now()
        ]);

        return $unlock;
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
     * Check if account needs revision
     */
    public function needsRevision()
    {
        return $this->account_status === 'needs_revision';
    }

    /**
     * Check if account is banned
     */
    public function isBanned()
    {
        return $this->is_banned;
    }

    /**
     * Check if suspension is temporary and still active
     */
    public function isSuspensionActive()
    {
        if ($this->account_status !== 'suspended') {
            return false;
        }

        if ($this->suspension_type === 'permanent') {
            return true;
        }

        if ($this->suspension_type === 'temporary' && $this->suspension_ends_at) {
            return now()->lt($this->suspension_ends_at);
        }

        return true;
    }

    /**
     * Approve account
     */
    public function approve($approvedBy = null)
    {
        $wasNotActive = $this->account_status !== 'active';

        $this->update([
            'account_status' => 'active',
            'approved_at' => now(),
            'approved_by' => $approvedBy
        ]);

        // Send activation email only if account was not active before
        if ($wasNotActive) {
            $this->sendActivationEmailIfNeeded();
        }
    }

    /**
     * Send activation email with duplication check
     */
    public function sendActivationEmailIfNeeded($forceResend = false)
    {
        // Check if email was already sent recently (within 24 hours) unless forced
        if (!$forceResend && $this->activation_email_sent_at &&
            $this->activation_email_sent_at->gt(now()->subHours(24))) {
            return false; // Email already sent recently
        }

        try {
            $this->notify(new \App\Notifications\AccountApprovedNotification());

            // Update tracking fields
            $this->update([
                'activation_email_sent_at' => now(),
                'activation_email_count' => $this->activation_email_count + 1
            ]);

            return true;
        } catch (\Exception $e) {
            \Log::warning('Failed to send activation email to user ' . $this->id . ': ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Suspend account
     */
    public function suspend($reason = null, $type = 'permanent', $days = null, $suspendedBy = null)
    {
        $data = [
            'account_status' => 'suspended',
            'suspension_reason' => $reason,
            'suspension_type' => $type,
            'suspended_at' => now(),
            'suspended_by' => $suspendedBy
        ];

        if ($type === 'temporary' && $days) {
            $data['suspension_days'] = $days;
            $data['suspension_ends_at'] = now()->addDays($days);
        }

        $this->update($data);
    }

    /**
     * Request revision for account
     */
    public function requestRevision($notes, $requestedBy = null)
    {
        $this->update([
            'account_status' => 'needs_revision',
            'revision_notes' => $notes,
            'revision_requested_at' => now(),
            'revision_requested_by' => $requestedBy
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
     * Check if rental owner can access media files and print/download
     * (only for active rental owners)
     */
    public function canAccessMediaAndPrint()
    {
        // Admin always can access
        if ($this->role === 'admin') {
            return true;
        }

        // Only active rental owners can access media and print
        if ($this->role === 'pengusaha_rental' && $this->isActive()) {
            // Must have verified email if required
            if ($this->requiresEmailVerification()) {
                return false;
            }
            return true;
        }

        return false;
    }

    /**
     * Check if rental owner has limited access (can search but with restrictions)
     */
    public function hasLimitedAccess()
    {
        // Rental owners who are not active but not banned
        return $this->role === 'pengusaha_rental' &&
               !$this->isActive() &&
               !$this->isBanned();
    }

    /**
     * Check if user can search data
     */
    public function canSearchData()
    {
        // Admin can always search
        if ($this->role === 'admin') {
            return true;
        }

        // Rental owners can search even if not active (limited access)
        if ($this->role === 'pengusaha_rental') {
            // Must not be banned
            if ($this->isBanned()) {
                return false;
            }
            // Must have verified email if required
            if ($this->requiresEmailVerification()) {
                return false;
            }
            return true;
        }

        // For other roles, must have full features access
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
