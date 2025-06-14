<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Helpers\PhoneHelper;

class RentalBlacklist extends Model
{
    use HasFactory;

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

    public function fileWatermarks(): MorphMany
    {
        return $this->morphMany(FileWatermark::class, 'watermarkable');
    }

    // Mutator untuk normalisasi nomor HP
    public function setNoHpAttribute($value)
    {
        $this->attributes['no_hp'] = PhoneHelper::normalize($value);
    }

    // Scope untuk pencarian
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nik', 'like', "%{$search}%")
              ->orWhere('nama_lengkap', 'like', "%{$search}%")
              ->orWhere('no_hp', 'like', "%{$search}%");

            // Jika search berupa nomor HP, coba normalisasi
            $normalizedPhone = PhoneHelper::normalize($search);
            if ($normalizedPhone !== $search && strlen($normalizedPhone) >= 8) {
                $q->orWhere('no_hp', 'like', "%{$normalizedPhone}%");
            }
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

    public function getSensoredAlamatAttribute()
    {
        $alamat = $this->alamat;
        $words = explode(' ', $alamat);
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

    // Get bukti files with watermark consideration
    public function getBuktiFilesForUser($user = null)
    {
        if (!$this->bukti || !is_array($this->bukti)) {
            return [];
        }

        $files = [];
        foreach ($this->bukti as $filePath) {
            // Check if watermarked version exists
            $watermark = $this->fileWatermarks()->where('original_path', $filePath)->first();

            if ($watermark) {
                $files[] = [
                    'path' => $watermark->getDisplayPath($user),
                    'original_path' => $watermark->original_path,
                    'is_watermarked' => $user && $user->role !== 'admin',
                    'type' => $watermark->file_type,
                    'size' => $watermark->formatted_file_size
                ];
            } else {
                // Fallback to original file if no watermark record
                $files[] = [
                    'path' => $filePath,
                    'original_path' => $filePath,
                    'is_watermarked' => false,
                    'type' => pathinfo($filePath, PATHINFO_EXTENSION),
                    'size' => null
                ];
            }
        }

        return $files;
    }
}
