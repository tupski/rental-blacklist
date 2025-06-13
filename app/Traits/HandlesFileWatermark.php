<?php

namespace App\Traits;

use App\Services\WatermarkService;
use App\Models\FileWatermark;
use Illuminate\Support\Facades\Storage;

trait HandlesFileWatermark
{
    /**
     * Process uploaded files and create watermarked versions
     */
    public function processUploadedFiles($files, $model)
    {
        if (!$files || !is_array($files)) {
            return [];
        }

        $watermarkService = new WatermarkService();
        $processedFiles = [];

        foreach ($files as $filePath) {
            $processedFiles[] = $filePath;

            // Check if file should be watermarked
            if ($watermarkService->shouldWatermark($filePath)) {
                $this->createWatermarkedFile($filePath, $model, $watermarkService);
            } else {
                // For non-watermarkable files, still create a record
                $this->createFileRecord($filePath, $model);
            }
        }

        return $processedFiles;
    }

    /**
     * Create watermarked file and record
     */
    protected function createWatermarkedFile($originalPath, $model, $watermarkService)
    {
        try {
            // Create watermarked version
            $watermarkedPath = $watermarkService->processFile($originalPath);
            
            // Get file info
            $fullPath = Storage::disk('public')->path($originalPath);
            $fileSize = file_exists($fullPath) ? filesize($fullPath) : 0;
            $fileType = pathinfo($originalPath, PATHINFO_EXTENSION);

            // Create database record
            FileWatermark::create([
                'original_path' => $originalPath,
                'watermarked_path' => $watermarkedPath,
                'file_type' => $fileType,
                'file_size' => $fileSize,
                'watermarkable_type' => get_class($model),
                'watermarkable_id' => $model->id,
                'processed_at' => now()
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to create watermarked file: ' . $e->getMessage());
            // Create record without watermark as fallback
            $this->createFileRecord($originalPath, $model);
        }
    }

    /**
     * Create file record without watermark
     */
    protected function createFileRecord($originalPath, $model)
    {
        try {
            $fullPath = Storage::disk('public')->path($originalPath);
            $fileSize = file_exists($fullPath) ? filesize($fullPath) : 0;
            $fileType = pathinfo($originalPath, PATHINFO_EXTENSION);

            FileWatermark::create([
                'original_path' => $originalPath,
                'watermarked_path' => null,
                'file_type' => $fileType,
                'file_size' => $fileSize,
                'watermarkable_type' => get_class($model),
                'watermarkable_id' => $model->id,
                'processed_at' => now()
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to create file record: ' . $e->getMessage());
        }
    }

    /**
     * Handle file removal with watermark cleanup
     */
    public function removeFilesWithWatermark($filePaths, $model)
    {
        if (!$filePaths || !is_array($filePaths)) {
            return;
        }

        foreach ($filePaths as $filePath) {
            // Find watermark record
            $watermark = FileWatermark::where('original_path', $filePath)
                                   ->where('watermarkable_type', get_class($model))
                                   ->where('watermarkable_id', $model->id)
                                   ->first();

            if ($watermark) {
                // Delete watermarked file if exists
                if ($watermark->watermarked_path && Storage::disk('public')->exists($watermark->watermarked_path)) {
                    Storage::disk('public')->delete($watermark->watermarked_path);
                }

                // Delete original file
                if (Storage::disk('public')->exists($watermark->original_path)) {
                    Storage::disk('public')->delete($watermark->original_path);
                }

                // Delete record
                $watermark->delete();
            } else {
                // Fallback: just delete the file
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }
        }
    }

    /**
     * Get files for display based on user role
     */
    public function getFilesForDisplay($model, $user = null)
    {
        if (!$model->bukti || !is_array($model->bukti)) {
            return [];
        }

        $files = [];
        foreach ($model->bukti as $filePath) {
            $watermark = FileWatermark::where('original_path', $filePath)
                                    ->where('watermarkable_type', get_class($model))
                                    ->where('watermarkable_id', $model->id)
                                    ->first();

            if ($watermark) {
                $files[] = [
                    'path' => $watermark->getDisplayPath($user),
                    'original_path' => $watermark->original_path,
                    'is_watermarked' => $user && $user->role !== 'admin',
                    'type' => $watermark->file_type,
                    'size' => $watermark->formatted_file_size,
                    'is_image' => $watermark->isImage(),
                    'is_video' => $watermark->isVideo()
                ];
            } else {
                // Fallback for files without watermark records
                $files[] = [
                    'path' => $filePath,
                    'original_path' => $filePath,
                    'is_watermarked' => false,
                    'type' => pathinfo($filePath, PATHINFO_EXTENSION),
                    'size' => null,
                    'is_image' => in_array(strtolower(pathinfo($filePath, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']),
                    'is_video' => in_array(strtolower(pathinfo($filePath, PATHINFO_EXTENSION)), ['mp4', 'avi', 'mov', 'mkv'])
                ];
            }
        }

        return $files;
    }

    /**
     * Reprocess watermarks for existing files
     */
    public function reprocessWatermarks($model)
    {
        if (!$model->bukti || !is_array($model->bukti)) {
            return;
        }

        $watermarkService = new WatermarkService();

        foreach ($model->bukti as $filePath) {
            if ($watermarkService->shouldWatermark($filePath)) {
                // Check if watermark record exists
                $existingWatermark = FileWatermark::where('original_path', $filePath)
                                                ->where('watermarkable_type', get_class($model))
                                                ->where('watermarkable_id', $model->id)
                                                ->first();

                if (!$existingWatermark) {
                    $this->createWatermarkedFile($filePath, $model, $watermarkService);
                }
            }
        }
    }
}
