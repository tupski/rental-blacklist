<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TopupRequest extends Model
{
    protected $fillable = [
        'user_id',
        'invoice_number',
        'amount',
        'payment_method',
        'payment_channel',
        'payment_details',
        'status',
        'proof_of_payment',
        'notes',
        'admin_notes',
        'paid_at',
        'confirmed_at',
        'expires_at',
        'gateway_response'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_details' => 'array',
        'gateway_response' => 'array',
        'paid_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function generateInvoiceNumber()
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(6));

        return $prefix . $date . $random;
    }

    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'pending_confirmation' => 'info',
            'paid' => 'info',
            'confirmed' => 'success',
            'rejected' => 'danger',
            'expired' => 'secondary',
            default => 'secondary'
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'Menunggu Pembayaran',
            'pending_confirmation' => 'Menunggu Konfirmasi',
            'paid' => 'Sudah Dibayar',
            'confirmed' => 'Dikonfirmasi',
            'rejected' => 'Ditolak',
            'expired' => 'Kadaluarsa',
            default => 'Unknown'
        };
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at < now();
    }

    public function canBePaid()
    {
        return $this->status === 'pending' && !$this->isExpired();
    }
}
