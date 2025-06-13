<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FileWatermark extends Model
{
    protected $fillable = [
        'original_path',
        'watermarked_path',
        'file_type',
        'file_size',
        'watermarkable_type',
        'watermarkable_id',
        'processed_at'
    ];

    protected $casts = [
        'processed_at' => 'datetime'
    ];

    /**
     * Get the parent watermarkable model (RentalBlacklist, GuestReport, etc.)
     */
    public function watermarkable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Check if file is an image
     */
    public function isImage()
    {
        return in_array(strtolower($this->file_type), ['jpg', 'jpeg', 'png', 'gif']);
    }

    /**
     * Check if file is a video
     */
    public function isVideo()
    {
        return in_array(strtolower($this->file_type), ['mp4', 'avi', 'mov', 'mkv']);
    }

    /**
     * Get display path based on user role
     */
    public function getDisplayPath($user = null)
    {
        // Admin always sees original
        if ($user && $user->role === 'admin') {
            return $this->original_path;
        }
        
        // Others see watermarked version
        return $this->watermarked_path ?: $this->original_path;
    }
}
