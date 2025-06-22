<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SponsorPackage extends Model
{
    protected $fillable = [
        'name',
        'benefits',
        'price',
        'duration_days',
        'is_popular',
        'placement_options',
        'max_logo_size_kb',
        'recommended_logo_size',
        'is_active',
        'sort_order',
        'description'
    ];

    protected $casts = [
        'benefits' => 'array',
        'placement_options' => 'array',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2'
    ];

    // Scope untuk paket aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk paket populer
    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    // Relasi ke pembelian
    public function purchases()
    {
        return $this->hasMany(SponsorPurchase::class);
    }

    // Format harga
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    // Format durasi
    public function getFormattedDurationAttribute()
    {
        if ($this->duration_days >= 30) {
            $months = floor($this->duration_days / 30);
            $days = $this->duration_days % 30;

            if ($days > 0) {
                return $months . ' bulan ' . $days . ' hari';
            }
            return $months . ' bulan';
        }

        return $this->duration_days . ' hari';
    }
}
