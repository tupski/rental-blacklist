<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

/**
 * Custom validation rule untuk video files
 * 
 * Validasi yang dilakukan:
 * - File type harus video
 * - Format yang diizinkan: mp4, avi, mov, mkv
 * - Ukuran maksimal 50MB untuk video
 * - Durasi maksimal 10 menit (jika bisa dideteksi)
 */
class VideoFileRule implements ValidationRule
{
    protected $maxSizeInKB;
    protected $allowedMimes;
    protected $maxDurationInSeconds;

    public function __construct(
        int $maxSizeInKB = 51200, // 50MB default
        array $allowedMimes = ['mp4', 'avi', 'mov', 'mkv'],
        int $maxDurationInSeconds = 600 // 10 minutes default
    ) {
        $this->maxSizeInKB = $maxSizeInKB;
        $this->allowedMimes = $allowedMimes;
        $this->maxDurationInSeconds = $maxDurationInSeconds;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$value instanceof UploadedFile) {
            $fail('File harus berupa upload yang valid.');
            return;
        }

        // Check if file is actually uploaded
        if (!$value->isValid()) {
            $fail('File upload tidak valid.');
            return;
        }

        // Check file size
        if ($value->getSize() > ($this->maxSizeInKB * 1024)) {
            $sizeInMB = round($this->maxSizeInKB / 1024, 1);
            $fail("Ukuran file video tidak boleh lebih dari {$sizeInMB}MB.");
            return;
        }

        // Check MIME type
        $extension = strtolower($value->getClientOriginalExtension());
        if (!in_array($extension, $this->allowedMimes)) {
            $allowedFormats = implode(', ', $this->allowedMimes);
            $fail("Format video harus berupa: {$allowedFormats}.");
            return;
        }

        // Check actual MIME type from file content
        $mimeType = $value->getMimeType();
        $validMimeTypes = [
            'video/mp4',
            'video/avi',
            'video/x-msvideo',
            'video/quicktime',
            'video/x-matroska'
        ];

        if (!in_array($mimeType, $validMimeTypes)) {
            $fail('File bukan video yang valid.');
            return;
        }

        // Optional: Check video duration if FFProbe is available
        $this->validateVideoDuration($value, $fail);
    }

    /**
     * Validate video duration using FFProbe if available
     */
    protected function validateVideoDuration(UploadedFile $file, Closure $fail): void
    {
        try {
            // Check if FFProbe is available
            $ffprobePath = env('FFPROBE_BINARIES', '/usr/bin/ffprobe');
            
            if (!file_exists($ffprobePath)) {
                // FFProbe not available, skip duration check
                return;
            }

            $tempPath = $file->getRealPath();
            
            // Get video duration using FFProbe
            $command = "{$ffprobePath} -v quiet -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 \"{$tempPath}\"";
            $duration = shell_exec($command);
            
            if ($duration !== null) {
                $durationInSeconds = (float) trim($duration);
                
                if ($durationInSeconds > $this->maxDurationInSeconds) {
                    $maxMinutes = round($this->maxDurationInSeconds / 60, 1);
                    $fail("Durasi video tidak boleh lebih dari {$maxMinutes} menit.");
                }
            }
        } catch (\Exception $e) {
            // If duration check fails, just log and continue
            \Log::info('Video duration check failed: ' . $e->getMessage());
        }
    }

    /**
     * Get validation error message
     */
    public function message(): string
    {
        return 'File harus berupa video yang valid dengan format yang diizinkan.';
    }
}
