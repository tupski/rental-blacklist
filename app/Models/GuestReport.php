<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestReport extends Model
{
    protected $table = 'guest_reports';

    protected $fillable = [
        'nik',
        'nama_lengkap',
        'jenis_kelamin',
        'no_hp',
        'alamat',
        'jenis_rental',
        'jenis_laporan',
        'kronologi',
        'bukti',
        'tanggal_kejadian',
        'email_pelapor',
        'nama_pelapor',
        'no_hp_pelapor',
        'status',
        'catatan_admin'
    ];

    protected $casts = [
        'jenis_laporan' => 'array',
        'bukti' => 'array',
        'tanggal_kejadian' => 'date'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => 'Menunggu Verifikasi',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_REJECTED => 'Ditolak'
        ];
    }

    public function getStatusLabelAttribute()
    {
        $statuses = self::getStatusOptions();
        return $statuses[$this->status] ?? 'Unknown';
    }

    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case self::STATUS_PENDING:
                return 'warning';
            case self::STATUS_APPROVED:
                return 'success';
            case self::STATUS_REJECTED:
                return 'danger';
            default:
                return 'secondary';
        }
    }
}
