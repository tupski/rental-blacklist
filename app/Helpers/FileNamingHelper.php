<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Carbon\Carbon;

class FileNamingHelper
{
    /**
     * Generate filename with format: {jenis}-{nama-terlapor}-{tanggal}-{jam}-cekpenyewa.com.{ext}
     * 
     * @param string $jenis - foto-penyewa, foto-ktp-sim, video-bukti, dll
     * @param string $namaLengkap - nama terlapor
     * @param string $extension - jpg, png, mp4, dll
     * @return string
     */
    public static function generateFilename($jenis, $namaLengkap, $extension)
    {
        // Clean nama lengkap - remove special characters and spaces
        $cleanName = self::cleanName($namaLengkap);
        
        // Get current date and time
        $now = Carbon::now();
        $tanggal = $now->format('dmY'); // 15062025
        $jam = $now->format('His'); // 080234
        
        // Build filename
        $filename = sprintf(
            '%s-%s-%s-%s-cekpenyewa.com.%s',
            $jenis,
            $cleanName,
            $tanggal,
            $jam,
            $extension
        );
        
        return $filename;
    }
    
    /**
     * Clean name for filename usage
     * 
     * @param string $name
     * @return string
     */
    public static function cleanName($name)
    {
        // Convert to lowercase
        $clean = strtolower($name);
        
        // Replace spaces with hyphens
        $clean = str_replace(' ', '-', $clean);
        
        // Remove special characters, keep only alphanumeric and hyphens
        $clean = preg_replace('/[^a-z0-9\-]/', '', $clean);
        
        // Remove multiple consecutive hyphens
        $clean = preg_replace('/-+/', '-', $clean);
        
        // Remove leading/trailing hyphens
        $clean = trim($clean, '-');
        
        // Limit length to 50 characters
        if (strlen($clean) > 50) {
            $clean = substr($clean, 0, 50);
            $clean = rtrim($clean, '-');
        }
        
        return $clean;
    }
    
    /**
     * Generate filename for foto penyewa
     */
    public static function generateFotoPenyewaFilename($namaLengkap, $extension)
    {
        return self::generateFilename('foto-penyewa', $namaLengkap, $extension);
    }
    
    /**
     * Generate filename for foto KTP/SIM
     */
    public static function generateFotoKtpSimFilename($namaLengkap, $extension)
    {
        return self::generateFilename('foto-ktp-sim', $namaLengkap, $extension);
    }
    
    /**
     * Generate filename for bukti pendukung
     */
    public static function generateBuktiFilename($namaLengkap, $extension)
    {
        // Determine jenis based on extension
        $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'mkv'];
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array(strtolower($extension), $videoExtensions)) {
            $jenis = 'video-bukti';
        } elseif (in_array(strtolower($extension), $imageExtensions)) {
            $jenis = 'foto-bukti';
        } else {
            $jenis = 'dokumen-bukti';
        }
        
        return self::generateFilename($jenis, $namaLengkap, $extension);
    }
    
    /**
     * Generate filename for rental registration documents
     */
    public static function generateRentalDocFilename($namaPerusahaan, $extension, $jenis = 'dokumen')
    {
        return self::generateFilename($jenis . '-rental', $namaPerusahaan, $extension);
    }
    
    /**
     * Generate filename for rental photos
     */
    public static function generateRentalPhotoFilename($namaPerusahaan, $extension)
    {
        return self::generateFilename('foto-rental', $namaPerusahaan, $extension);
    }
    
    /**
     * Generate filename for topup proof
     */
    public static function generateTopupProofFilename($userName, $invoice, $extension)
    {
        $cleanName = self::cleanName($userName);
        $now = Carbon::now();
        $tanggal = $now->format('dmY');
        $jam = $now->format('His');
        
        return sprintf(
            'bukti-topup-%s-%s-%s-%s-cekpenyewa.com.%s',
            $cleanName,
            $invoice,
            $tanggal,
            $jam,
            $extension
        );
    }
}
