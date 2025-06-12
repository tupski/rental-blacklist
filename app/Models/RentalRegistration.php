<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalRegistration extends Model
{
    protected $table = 'rental_registrations';

    protected $fillable = [
        'nama_rental',
        'jenis_rental',
        'alamat',
        'kota',
        'provinsi',
        'no_hp',
        'email',
        'nama_pemilik',
        'nik_pemilik',
        'no_hp_pemilik',
        'deskripsi',
        'website',
        'sosial_media',
        'dokumen_legalitas',
        'foto_tempat',
        'status',
        'catatan_admin',
        'user_id'
    ];

    protected $casts = [
        'jenis_rental' => 'array',
        'sosial_media' => 'array',
        'dokumen_legalitas' => 'array',
        'foto_tempat' => 'array'
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

    public static function getJenisRentalOptions()
    {
        return [
            'Motor' => 'Motor',
            'Mobil' => 'Mobil',
            'Alat Berat' => 'Alat Berat',
            'Elektronik' => 'Elektronik',
            'Peralatan' => 'Peralatan',
            'Lainnya' => 'Lainnya'
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
