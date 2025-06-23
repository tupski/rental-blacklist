<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_name',
        'donor_email',
        'donor_phone',
        'donor_province',
        'donor_city',
        'donor_type',
        'company_name',
        'amount',
        'status',
        'payment_method',
        'payment_reference',
        'message',
        'admin_notes',
        'paid_at',
        'confirmed_at',
        'confirmed_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    /**
     * Get the admin who confirmed this donation
     */
    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    /**
     * Scope for pending donations
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for paid donations
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope for confirmed donations
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-warning',
            'paid' => 'bg-info',
            'confirmed' => 'bg-success',
            'cancelled' => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Sudah Dibayar',
            'confirmed' => 'Dikonfirmasi',
            'cancelled' => 'Dibatalkan',
            default => 'Tidak Diketahui'
        };
    }
}
