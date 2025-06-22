<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SponsorPurchase extends Model
{
    protected $fillable = [
        'user_id',
        'sponsor_package_id',
        'invoice_number',
        'amount',
        'payment_status',
        'payment_deadline',
        'paid_at',
        'confirmed_at',
        'expires_at',
        'payment_proof',
        'payment_notes',
        'admin_notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_deadline' => 'datetime',
        'paid_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    // Relasi ke user (rental owner)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke paket sponsor
    public function sponsorPackage()
    {
        return $this->belongsTo(SponsorPackage::class);
    }

    // Relasi ke pengaturan sponsor
    public function sponsorSetting()
    {
        return $this->hasOne(SponsorSetting::class);
    }

    // Scope untuk status tertentu
    public function scopeStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    // Scope untuk yang sudah expired
    public function scopeExpired($query)
    {
        return $query->where('payment_deadline', '<', now())
                    ->where('payment_status', 'pending');
    }

    // Scope untuk yang aktif
    public function scopeActive($query)
    {
        return $query->where('payment_status', 'confirmed')
                    ->where('expires_at', '>', now());
    }

    // Cek apakah pembayaran sudah expired
    public function isExpired()
    {
        return $this->payment_status === 'pending' && $this->payment_deadline < now();
    }

    // Cek apakah sponsor masih aktif
    public function isActive()
    {
        return $this->payment_status === 'confirmed' &&
               $this->expires_at &&
               $this->expires_at > now();
    }

    // Generate nomor invoice
    public static function generateInvoiceNumber()
    {
        $prefix = 'SPR';
        $date = now()->format('Ymd');
        $lastInvoice = self::where('invoice_number', 'like', $prefix . $date . '%')
                          ->orderBy('invoice_number', 'desc')
                          ->first();

        if ($lastInvoice) {
            $lastNumber = intval(substr($lastInvoice->invoice_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $date . $newNumber;
    }

    // Format amount
    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    // Status badge
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge badge-warning">Belum Dibayar</span>',
            'paid' => '<span class="badge badge-info">Menunggu Konfirmasi</span>',
            'confirmed' => '<span class="badge badge-success">Lunas</span>',
            'failed' => '<span class="badge badge-danger">Gagal</span>',
            'expired' => '<span class="badge badge-secondary">Expired</span>'
        ];

        return $badges[$this->payment_status] ?? '<span class="badge badge-secondary">Unknown</span>';
    }
}
