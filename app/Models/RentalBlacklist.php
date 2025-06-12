<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentalBlacklist extends Model
{
    protected $table = 'rental_blacklist';

    protected $fillable = [
        'nik',
        'nama_lengkap',
        'jenis_kelamin',
        'no_hp',
        'alamat',
        'jenis_rental',
        'jenis_laporan',
        'status_validitas',
        'kronologi',
        'bukti',
        'tanggal_kejadian',
        'user_id'
    ];

    protected $casts = [
        'jenis_laporan' => 'array',
        'bukti' => 'array',
        'tanggal_kejadian' => 'date'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scope untuk pencarian
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nik', 'like', "%{$search}%")
              ->orWhere('nama_lengkap', 'like', "%{$search}%");
        });
    }

    // Method untuk sensor data
    public function getSensoredNamaAttribute()
    {
        $nama = $this->nama_lengkap;
        $words = explode(' ', $nama);
        $sensoredWords = [];

        foreach ($words as $word) {
            if (strlen($word) <= 2) {
                $sensoredWords[] = $word;
            } else {
                $first = substr($word, 0, 1);
                $last = substr($word, -1);
                $middle = str_repeat('*', strlen($word) - 2);
                $sensoredWords[] = $first . $middle . $last;
            }
        }

        return implode(' ', $sensoredWords);
    }

    public function getSensoredNikAttribute()
    {
        $nik = $this->nik;
        if (strlen($nik) >= 8) {
            $start = substr($nik, 0, 4);
            $end = substr($nik, -4);
            $middle = str_repeat('*', strlen($nik) - 8);
            return $start . $middle . $end;
        }
        return $nik;
    }

    public function getSensoredNoHpAttribute()
    {
        $hp = $this->no_hp;
        if (strlen($hp) >= 6) {
            $start = substr($hp, 0, 4);
            $end = substr($hp, -2);
            $middle = str_repeat('*', strlen($hp) - 6);
            return $start . $middle . $end;
        }
        return $hp;
    }

    // Method untuk menghitung jumlah laporan per NIK
    public static function countReportsByNik($nik)
    {
        return self::where('nik', $nik)->count();
    }

    // Method untuk menghitung laporan dari user berbeda
    public static function countUniqueUserReportsByNik($nik)
    {
        return self::where('nik', $nik)->distinct('user_id')->count('user_id');
    }
}
