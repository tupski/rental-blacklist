<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

/**
 * Custom validation rule untuk bukti files (kombinasi image, video, dan document)
 * 
 * Validasi yang dilakukan:
 * - File type sesuai dengan kategori yang diizinkan
 * - Ukuran file sesuai dengan type
 * - Format yang diizinkan untuk setiap kategori
 * - Validasi content untuk memastikan file tidak corrupt
 */
class BuktiFileRule implements ValidationRule
{
    protected $allowedTypes;
    protected $maxSizes;
    protected $allowedMimes;

    public function __construct()
    {
        $this->allowedTypes = [
            'image' => ['jpg', 'jpeg', 'png', 'gif'],
            'video' => ['mp4', 'avi', 'mov', 'mkv'],
            'document' => ['pdf']
        ];

        // Max sizes in KB
        $this->maxSizes = [
            'image' => 5120,    // 5MB
            'video' => 51200,   // 50MB
            'document' => 10240 // 10MB
        ];

        $this->allowedMimes = [
            'image' => [
                'image/jpeg',
                'image/jpg', 
                'image/png',
                'image/gif'
            ],
            'video' => [
                'video/mp4',
                'video/avi',
                'video/x-msvideo',
                'video/quicktime',
                'video/x-matroska'
            ],
            'document' => [
                'application/pdf'
            ]
        ];
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

        // Determine file type category
        $extension = strtolower($value->getClientOriginalExtension());
        $fileType = $this->getFileTypeCategory($extension);

        if (!$fileType) {
            $allAllowed = [];
            foreach ($this->allowedTypes as $type => $extensions) {
                $allAllowed = array_merge($allAllowed, $extensions);
            }
            $allowedFormats = implode(', ', $allAllowed);
            $fail("Format file harus berupa: {$allowedFormats}.");
            return;
        }

        // Check file size based on type
        $maxSize = $this->maxSizes[$fileType] * 1024; // Convert to bytes
        if ($value->getSize() > $maxSize) {
            $sizeInMB = round($this->maxSizes[$fileType] / 1024, 1);
            $fail("Ukuran file {$fileType} tidak boleh lebih dari {$sizeInMB}MB.");
            return;
        }

        // Check MIME type
        $mimeType = $value->getMimeType();
        if (!in_array($mimeType, $this->allowedMimes[$fileType])) {
            $fail("MIME type file tidak valid untuk {$fileType}.");
            return;
        }

        // Validate file content based on type
        $this->validateFileContent($value, $fileType, $fail);
    }

    /**
     * Get file type category based on extension
     */
    protected function getFileTypeCategory(string $extension): ?string
    {
        foreach ($this->allowedTypes as $type => $extensions) {
            if (in_array($extension, $extensions)) {
                return $type;
            }
        }
        return null;
    }

    /**
     * Validate file content based on type
     */
    protected function validateFileContent(UploadedFile $file, string $fileType, Closure $fail): void
    {
        try {
            switch ($fileType) {
                case 'image':
                    $this->validateImageContent($file, $fail);
                    break;
                case 'video':
                    $this->validateVideoContent($file, $fail);
                    break;
                case 'document':
                    $this->validateDocumentContent($file, $fail);
                    break;
            }
        } catch (\Exception $e) {
            $fail("Tidak dapat memvalidasi konten file {$fileType}.");
        }
    }

    /**
     * Validate image content
     */
    protected function validateImageContent(UploadedFile $file, Closure $fail): void
    {
        $imageInfo = getimagesize($file->getRealPath());
        
        if ($imageInfo === false) {
            $fail('File gambar tidak valid atau rusak.');
            return;
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];

        // Check minimum dimensions (at least 50x50)
        if ($width < 50 || $height < 50) {
            $fail('Ukuran gambar terlalu kecil (minimal 50x50 pixel).');
            return;
        }

        // Check maximum dimensions (max 5000x5000)
        if ($width > 5000 || $height > 5000) {
            $fail('Ukuran gambar terlalu besar (maksimal 5000x5000 pixel).');
            return;
        }
    }

    /**
     * Validate video content
     */
    protected function validateVideoContent(UploadedFile $file, Closure $fail): void
    {
        // Basic validation - check if file has video-like characteristics
        $fileSize = $file->getSize();
        
        // Video files should be at least 1KB
        if ($fileSize < 1024) {
            $fail('File video terlalu kecil untuk menjadi video yang valid.');
            return;
        }

        // Optional: Check video duration if FFProbe is available
        $this->checkVideoDuration($file, $fail);
    }

    /**
     * Check video duration if FFProbe is available
     */
    protected function checkVideoDuration(UploadedFile $file, Closure $fail): void
    {
        try {
            $ffprobePath = env('FFPROBE_BINARIES', '/usr/bin/ffprobe');
            
            if (!file_exists($ffprobePath)) {
                return; // Skip if FFProbe not available
            }

            $tempPath = $file->getRealPath();
            $command = "{$ffprobePath} -v quiet -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 \"{$tempPath}\" 2>/dev/null";
            $duration = shell_exec($command);
            
            if ($duration !== null && trim($duration) !== '') {
                $durationInSeconds = (float) trim($duration);
                
                // Maximum 15 minutes for bukti video
                if ($durationInSeconds > 900) {
                    $fail('Durasi video tidak boleh lebih dari 15 menit.');
                }
                
                // Minimum 1 second
                if ($durationInSeconds < 1) {
                    $fail('Durasi video terlalu pendek.');
                }
            }
        } catch (\Exception $e) {
            // If duration check fails, just log and continue
            \Log::info('Video duration check failed: ' . $e->getMessage());
        }
    }

    /**
     * Validate document content
     */
    protected function validateDocumentContent(UploadedFile $file, Closure $fail): void
    {
        // Check PDF header
        $handle = fopen($file->getRealPath(), 'rb');
        if ($handle) {
            $header = fread($handle, 4);
            fclose($handle);
            
            if ($header !== '%PDF') {
                $fail('File PDF tidak valid atau rusak.');
                return;
            }
        } else {
            $fail('Tidak dapat membaca file PDF.');
        }
    }

    /**
     * Get validation error message
     */
    public function message(): string
    {
        return 'File bukti harus berupa gambar (jpg, png, gif), video (mp4, avi, mov), atau dokumen (pdf) yang valid.';
    }
}
