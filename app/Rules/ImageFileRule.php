<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

/**
 * Custom validation rule untuk image files
 * 
 * Validasi yang dilakukan:
 * - File type harus image
 * - Format yang diizinkan: jpg, jpeg, png, gif
 * - Ukuran maksimal 5MB untuk image
 * - Dimensi minimal dan maksimal
 * - Validasi actual image content
 */
class ImageFileRule implements ValidationRule
{
    protected $maxSizeInKB;
    protected $allowedMimes;
    protected $minWidth;
    protected $minHeight;
    protected $maxWidth;
    protected $maxHeight;

    public function __construct(
        int $maxSizeInKB = 5120, // 5MB default
        array $allowedMimes = ['jpg', 'jpeg', 'png', 'gif'],
        int $minWidth = 100,
        int $minHeight = 100,
        int $maxWidth = 4000,
        int $maxHeight = 4000
    ) {
        $this->maxSizeInKB = $maxSizeInKB;
        $this->allowedMimes = $allowedMimes;
        $this->minWidth = $minWidth;
        $this->minHeight = $minHeight;
        $this->maxWidth = $maxWidth;
        $this->maxHeight = $maxHeight;
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
            $fail("Ukuran file gambar tidak boleh lebih dari {$sizeInMB}MB.");
            return;
        }

        // Check file extension
        $extension = strtolower($value->getClientOriginalExtension());
        if (!in_array($extension, $this->allowedMimes)) {
            $allowedFormats = implode(', ', $this->allowedMimes);
            $fail("Format gambar harus berupa: {$allowedFormats}.");
            return;
        }

        // Check actual MIME type from file content
        $mimeType = $value->getMimeType();
        $validMimeTypes = [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif'
        ];

        if (!in_array($mimeType, $validMimeTypes)) {
            $fail('File bukan gambar yang valid.');
            return;
        }

        // Validate image dimensions
        $this->validateImageDimensions($value, $fail);
    }

    /**
     * Validate image dimensions
     */
    protected function validateImageDimensions(UploadedFile $file, Closure $fail): void
    {
        try {
            $imageInfo = getimagesize($file->getRealPath());
            
            if ($imageInfo === false) {
                $fail('File bukan gambar yang valid atau rusak.');
                return;
            }

            $width = $imageInfo[0];
            $height = $imageInfo[1];

            // Check minimum dimensions
            if ($width < $this->minWidth || $height < $this->minHeight) {
                $fail("Ukuran gambar minimal {$this->minWidth}x{$this->minHeight} pixel.");
                return;
            }

            // Check maximum dimensions
            if ($width > $this->maxWidth || $height > $this->maxHeight) {
                $fail("Ukuran gambar maksimal {$this->maxWidth}x{$this->maxHeight} pixel.");
                return;
            }

        } catch (\Exception $e) {
            $fail('Tidak dapat memvalidasi dimensi gambar.');
        }
    }

    /**
     * Get validation error message
     */
    public function message(): string
    {
        return 'File harus berupa gambar yang valid dengan format yang diizinkan.';
    }
}
