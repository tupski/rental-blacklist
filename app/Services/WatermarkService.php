<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
// FFMpeg imports are loaded dynamically to handle missing package

class WatermarkService
{
    protected $imageManager;
    protected $watermarkPath;
    protected $websiteName;
    protected $websiteUrl;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
        $this->watermarkPath = public_path('images/logo.png'); // Logo watermark
        $this->websiteName = config('app.name', 'Rental Blacklist Indonesia');
        $this->websiteUrl = config('app.url', 'https://rentalblacklist.id');
    }

    /**
     * Add watermark to image
     */
    public function addWatermarkToImage($originalPath, $outputPath = null)
    {
        try {
            $fullOriginalPath = Storage::disk('public')->path($originalPath);

            if (!file_exists($fullOriginalPath)) {
                throw new \Exception("Original file not found: {$fullOriginalPath}");
            }

            // Create output path if not provided
            if (!$outputPath) {
                $pathInfo = pathinfo($originalPath);
                $outputPath = $pathInfo['dirname'] . '/watermarked_' . $pathInfo['basename'];
            }

            $fullOutputPath = Storage::disk('public')->path($outputPath);

            // Ensure output directory exists
            $outputDir = dirname($fullOutputPath);
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }

            // Load the original image
            $image = $this->imageManager->read($fullOriginalPath);

            // Get image dimensions
            $width = $image->width();
            $height = $image->height();

            // Create watermark canvas
            $watermarkCanvas = $this->createWatermarkCanvas($width, $height);

            // Apply watermark to image
            $image->place($watermarkCanvas, 'center', 0, 0, 30); // 30% opacity

            // Save watermarked image
            $image->save($fullOutputPath);

            return $outputPath;
        } catch (\Exception $e) {
            \Log::error('Watermark error for image: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Add watermark to video
     */
    public function addWatermarkToVideo($originalPath, $outputPath = null)
    {
        try {
            $fullOriginalPath = Storage::disk('public')->path($originalPath);

            if (!file_exists($fullOriginalPath)) {
                throw new \Exception("Original file not found: {$fullOriginalPath}");
            }

            // Create output path if not provided
            if (!$outputPath) {
                $pathInfo = pathinfo($originalPath);
                $outputPath = $pathInfo['dirname'] . '/watermarked_' . $pathInfo['filename'] . '.mp4';
            }

            $fullOutputPath = Storage::disk('public')->path($outputPath);

            // Ensure output directory exists
            $outputDir = dirname($fullOutputPath);
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0755, true);
            }

            // Create temporary watermark image for video
            $tempWatermarkPath = $this->createVideoWatermark();

            // Check if FFMpeg class exists
            if (!class_exists('FFMpeg\FFMpeg')) {
                \Log::info('FFMpeg not available, skipping video watermark for: ' . $originalPath);
                return false;
            }

            // Use FFMpeg to add watermark
            $ffmpeg = \FFMpeg\FFMpeg::create([
                'ffmpeg.binaries'  => env('FFMPEG_BINARIES', '/usr/bin/ffmpeg'),
                'ffprobe.binaries' => env('FFPROBE_BINARIES', '/usr/bin/ffprobe'),
                'timeout'          => 3600,
                'ffmpeg.threads'   => 12,
            ]);

            $video = $ffmpeg->open($fullOriginalPath);

            // Add watermark filter
            $video->filters()
                ->custom("overlay=(main_w-overlay_w)/2:(main_h-overlay_h)/2:format=auto,format=yuv420p");

            // Save with watermark
            $format = new \FFMpeg\Format\Video\X264();
            $format->setKiloBitrate(1000);

            $video->save($format, $fullOutputPath);

            // Clean up temporary watermark
            if (file_exists($tempWatermarkPath)) {
                unlink($tempWatermarkPath);
            }

            return $outputPath;
        } catch (\Exception $e) {
            \Log::error('Watermark error for video: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create watermark canvas for images
     */
    protected function createWatermarkCanvas($width, $height)
    {
        // Calculate watermark size (max 25% of image width)
        $maxWatermarkWidth = $width * 0.25;

        // Create canvas
        $canvas = $this->imageManager->create($width, $height);

        // Add website name text
        $canvas->text($this->websiteName, $width/2, $height/2 - 20, function($font) use ($maxWatermarkWidth) {
            $font->size(min(24, $maxWatermarkWidth / 10));
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('middle');
        });

        // Add website URL text
        $canvas->text($this->websiteUrl, $width/2, $height/2 + 20, function($font) use ($maxWatermarkWidth) {
            $font->size(min(16, $maxWatermarkWidth / 15));
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('middle');
        });

        // Add logo if exists
        if (file_exists($this->watermarkPath)) {
            $logo = $this->imageManager->read($this->watermarkPath);
            $logoSize = min(60, $maxWatermarkWidth / 4);
            $logo->resize($logoSize, $logoSize, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $canvas->place($logo, 'center', 0, -60);
        }

        return $canvas;
    }

    /**
     * Create watermark image for video
     */
    protected function createVideoWatermark()
    {
        $tempPath = storage_path('app/temp_watermark.png');

        // Create a transparent canvas
        $canvas = $this->imageManager->create(400, 200);
        $canvas->fill('rgba(0,0,0,0)'); // Transparent background

        // Add website name
        $canvas->text($this->websiteName, 200, 80, function($font) {
            $font->size(24);
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('middle');
        });

        // Add website URL
        $canvas->text($this->websiteUrl, 200, 120, function($font) {
            $font->size(16);
            $font->color('#ffffff');
            $font->align('center');
            $font->valign('middle');
        });

        // Add logo if exists
        if (file_exists($this->watermarkPath)) {
            $logo = $this->imageManager->read($this->watermarkPath);
            $logo->resize(50, 50, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $canvas->place($logo, 'center', 0, -40);
        }

        $canvas->save($tempPath);

        return $tempPath;
    }

    /**
     * Process file and create watermarked version
     */
    public function processFile($originalPath)
    {
        $extension = strtolower(pathinfo($originalPath, PATHINFO_EXTENSION));

        // Image formats
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
            return $this->addWatermarkToImage($originalPath);
        }

        // Video formats
        if (in_array($extension, ['mp4', 'avi', 'mov', 'mkv'])) {
            return $this->addWatermarkToVideo($originalPath);
        }

        // For other formats (like PDF), return original path
        return $originalPath;
    }

    /**
     * Check if file should be watermarked
     */
    public function shouldWatermark($filePath)
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'avi', 'mov', 'mkv']);
    }
}
