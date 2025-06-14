<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Array bulan dalam bahasa Indonesia
     */
    private static $months = [
        1 => 'Januari',
        2 => 'Februari', 
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];

    /**
     * Array hari dalam bahasa Indonesia
     */
    private static $days = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    ];

    /**
     * Format tanggal ke bahasa Indonesia
     * 
     * @param Carbon|string $date
     * @param string $format
     * @return string
     */
    public static function formatIndonesian($date, $format = 'd F Y')
    {
        if (!$date) {
            return '';
        }

        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        $formatted = $date->format($format);
        
        // Replace English month names with Indonesian
        foreach (self::$months as $monthNum => $monthName) {
            $englishMonth = $date->format('F');
            if ($date->month == $monthNum) {
                $formatted = str_replace($englishMonth, $monthName, $formatted);
                break;
            }
        }

        // Replace English day names with Indonesian
        $englishDay = $date->format('l');
        if (isset(self::$days[$englishDay])) {
            $formatted = str_replace($englishDay, self::$days[$englishDay], $formatted);
        }

        return $formatted;
    }

    /**
     * Format tanggal lengkap dengan hari
     * 
     * @param Carbon|string $date
     * @return string
     */
    public static function formatLengkap($date)
    {
        return self::formatIndonesian($date, 'l, d F Y');
    }

    /**
     * Format tanggal dengan waktu
     * 
     * @param Carbon|string $date
     * @return string
     */
    public static function formatDenganWaktu($date)
    {
        return self::formatIndonesian($date, 'd F Y H:i') . ' WIB';
    }

    /**
     * Format tanggal singkat
     * 
     * @param Carbon|string $date
     * @return string
     */
    public static function formatSingkat($date)
    {
        return self::formatIndonesian($date, 'd M Y');
    }

    /**
     * Format relatif (berapa lama yang lalu)
     * 
     * @param Carbon|string $date
     * @return string
     */
    public static function formatRelatif($date)
    {
        if (!$date) {
            return '';
        }

        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        $now = Carbon::now();
        $diffInDays = $now->diffInDays($date);
        $diffInHours = $now->diffInHours($date);
        $diffInMinutes = $now->diffInMinutes($date);

        if ($diffInMinutes < 60) {
            return $diffInMinutes . ' menit yang lalu';
        } elseif ($diffInHours < 24) {
            return $diffInHours . ' jam yang lalu';
        } elseif ($diffInDays < 7) {
            return $diffInDays . ' hari yang lalu';
        } elseif ($diffInDays < 30) {
            $weeks = floor($diffInDays / 7);
            return $weeks . ' minggu yang lalu';
        } elseif ($diffInDays < 365) {
            $months = floor($diffInDays / 30);
            return $months . ' bulan yang lalu';
        } else {
            $years = floor($diffInDays / 365);
            return $years . ' tahun yang lalu';
        }
    }
}
