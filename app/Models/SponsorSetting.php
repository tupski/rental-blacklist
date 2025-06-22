<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SponsorSetting extends Model
{
    protected $fillable = [
        'sponsor_purchase_id',
        'company_name',
        'logo',
        'website_url',
        'social_media',
        'address',
        'phone',
        'email',
        'placement_positions',
        'is_active'
    ];

    protected $casts = [
        'social_media' => 'array',
        'placement_positions' => 'array',
        'is_active' => 'boolean'
    ];

    // Relasi ke pembelian sponsor
    public function sponsorPurchase()
    {
        return $this->belongsTo(SponsorPurchase::class);
    }

    // Relasi ke user melalui sponsor purchase
    public function user()
    {
        return $this->hasOneThrough(User::class, SponsorPurchase::class, 'id', 'id', 'sponsor_purchase_id', 'user_id');
    }

    // Accessor untuk URL logo
    public function getLogoUrlAttribute()
    {
        // Jika logo adalah URL eksternal (dimulai dengan http)
        if ($this->logo && (str_starts_with($this->logo, 'http://') || str_starts_with($this->logo, 'https://'))) {
            return $this->logo;
        }

        // Jika logo adalah file lokal
        if ($this->logo && Storage::disk('public')->exists($this->logo)) {
            return Storage::disk('public')->url($this->logo);
        }

        // Default placeholder
        return 'https://placehold.co/300x150/6c757d/ffffff?text=No+Logo';
    }

    // Scope untuk yang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope berdasarkan posisi
    public function scopePosition($query, $position)
    {
        return $query->whereJsonContains('placement_positions', $position);
    }

    // Cek apakah sponsor masih aktif berdasarkan purchase
    public function isCurrentlyActive()
    {
        if (!$this->is_active) {
            return false;
        }

        return $this->sponsorPurchase && $this->sponsorPurchase->isActive();
    }

    // Get formatted social media links
    public function getFormattedSocialMediaAttribute()
    {
        if (!$this->social_media) {
            return [];
        }

        $formatted = [];
        foreach ($this->social_media as $platform => $url) {
            if (!empty($url)) {
                $formatted[$platform] = [
                    'url' => $url,
                    'icon' => $this->getSocialMediaIcon($platform)
                ];
            }
        }

        return $formatted;
    }

    // Get social media icon class
    private function getSocialMediaIcon($platform)
    {
        $icons = [
            'facebook' => 'fab fa-facebook',
            'instagram' => 'fab fa-instagram',
            'twitter' => 'fab fa-twitter',
            'youtube' => 'fab fa-youtube',
            'linkedin' => 'fab fa-linkedin',
            'tiktok' => 'fab fa-tiktok',
            'whatsapp' => 'fab fa-whatsapp'
        ];

        return $icons[$platform] ?? 'fas fa-link';
    }
}
