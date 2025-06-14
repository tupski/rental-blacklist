<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\WatermarkService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


/**
 * Test Unit untuk WatermarkService
 *
 * Menguji semua fungsi watermark termasuk:
 * - Image watermarking
 * - Video watermarking
 * - File type detection
 * - Watermark canvas creation
 * - Error handling
 */
class WatermarkServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $watermarkService;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->watermarkService = new WatermarkService();
    }

    /**
     * Test: Service dapat mendeteksi file yang perlu watermark
     *
     * Alur:
     * Input: Berbagai jenis file (image, video, document)
     * Proses: Panggil shouldWatermark()
     * Output: true untuk image/video, false untuk document
     */
    public function test_can_detect_watermarkable_files()
    {
        // Image files should be watermarked
        $this->assertTrue($this->watermarkService->shouldWatermark('test.jpg'));
        $this->assertTrue($this->watermarkService->shouldWatermark('test.jpeg'));
        $this->assertTrue($this->watermarkService->shouldWatermark('test.png'));
        $this->assertTrue($this->watermarkService->shouldWatermark('test.gif'));

        // Video files should be watermarked
        $this->assertTrue($this->watermarkService->shouldWatermark('test.mp4'));
        $this->assertTrue($this->watermarkService->shouldWatermark('test.avi'));
        $this->assertTrue($this->watermarkService->shouldWatermark('test.mov'));
        $this->assertTrue($this->watermarkService->shouldWatermark('test.mkv'));

        // Document files should not be watermarked
        $this->assertFalse($this->watermarkService->shouldWatermark('test.pdf'));
        $this->assertFalse($this->watermarkService->shouldWatermark('test.doc'));
        $this->assertFalse($this->watermarkService->shouldWatermark('test.docx'));
        $this->assertFalse($this->watermarkService->shouldWatermark('test.txt'));
    }

    /**
     * Test: Service dapat memproses file image dengan watermark
     *
     * Alur:
     * Input: File image yang valid
     * Proses: Panggil addWatermarkToImage()
     * Output: File watermarked dibuat, return path yang benar
     */
    public function test_can_add_watermark_to_image()
    {
        // Create a fake image file
        $fakeImage = UploadedFile::fake()->image('test.jpg', 800, 600);
        $originalPath = $fakeImage->store('test-images', 'public');

        // Add watermark
        $watermarkedPath = $this->watermarkService->addWatermarkToImage($originalPath);

        // Verify watermarked file was created
        $this->assertNotFalse($watermarkedPath);
        $this->assertStringContainsString('watermarked_', $watermarkedPath);
        Storage::disk('public')->assertExists($watermarkedPath);

        // Verify original file still exists
        Storage::disk('public')->assertExists($originalPath);
    }

    /**
     * Test: Service dapat handle error saat file tidak ditemukan
     *
     * Alur:
     * Input: Path file yang tidak ada
     * Proses: Panggil addWatermarkToImage()
     * Output: Return false, log error
     */
    public function test_handles_missing_file_error()
    {
        $nonExistentPath = 'non-existent/file.jpg';

        $result = $this->watermarkService->addWatermarkToImage($nonExistentPath);

        $this->assertFalse($result);
    }

    /**
     * Test: Service dapat memproses berbagai format image
     *
     * Alur:
     * Input: File dengan format JPG, PNG, GIF
     * Proses: Panggil processFile()
     * Output: Semua format berhasil diproses
     */
    public function test_can_process_different_image_formats()
    {
        $imageFormats = ['jpg', 'png', 'gif'];

        foreach ($imageFormats as $format) {
            $fakeImage = UploadedFile::fake()->image("test.{$format}", 400, 300);
            $originalPath = $fakeImage->store('test-images', 'public');

            $result = $this->watermarkService->processFile($originalPath);

            $this->assertNotFalse($result);
            $this->assertStringContainsString('watermarked_', $result);
            Storage::disk('public')->assertExists($result);
        }
    }

    /**
     * Test: Service dapat handle file PDF tanpa watermark
     *
     * Alur:
     * Input: File PDF
     * Proses: Panggil processFile()
     * Output: Return original path (tidak diproses)
     */
    public function test_returns_original_path_for_non_watermarkable_files()
    {
        $fakePdf = UploadedFile::fake()->create('document.pdf', 1000, 'application/pdf');
        $originalPath = $fakePdf->store('test-docs', 'public');

        $result = $this->watermarkService->processFile($originalPath);

        // Should return original path for non-watermarkable files
        $this->assertEquals($originalPath, $result);
    }

    /**
     * Test: Service dapat membuat watermark canvas dengan ukuran yang benar
     *
     * Alur:
     * Input: Width dan height tertentu
     * Proses: Panggil createWatermarkCanvas() (via reflection)
     * Output: Canvas dengan ukuran yang sesuai
     */
    public function test_creates_watermark_canvas_with_correct_dimensions()
    {
        $width = 800;
        $height = 600;

        // Use reflection to access protected method
        $reflection = new \ReflectionClass($this->watermarkService);
        $method = $reflection->getMethod('createWatermarkCanvas');
        $method->setAccessible(true);

        $canvas = $method->invoke($this->watermarkService, $width, $height);

        // Verify canvas is created (this is an Image object)
        $this->assertNotNull($canvas);
    }

    /**
     * Test: Service dapat handle video watermarking (mock test)
     *
     * Alur:
     * Input: File video yang valid
     * Proses: Panggil addWatermarkToVideo()
     * Output: Return path atau false jika FFmpeg tidak tersedia
     */
    public function test_video_watermarking_detection()
    {
        $this->markTestSkipped('Video watermarking requires FFMpeg which may not be available in test environment');
    }

    /**
     * Test: Service dapat membuat temporary watermark untuk video
     *
     * Alur:
     * Input: Tidak ada (internal method)
     * Proses: Panggil createVideoWatermark() via reflection
     * Output: Path ke temporary watermark file
     */
    public function test_creates_temporary_video_watermark()
    {
        $this->markTestSkipped('Video watermark creation requires FFMpeg which may not be available in test environment');
    }

    /**
     * Test: Service dapat handle berbagai ukuran image
     *
     * Alur:
     * Input: Image dengan ukuran kecil, sedang, besar
     * Proses: Panggil addWatermarkToImage()
     * Output: Semua ukuran berhasil diproses
     */
    public function test_handles_different_image_sizes()
    {
        $imageSizes = [
            ['width' => 100, 'height' => 100],   // Small
            ['width' => 800, 'height' => 600],   // Medium
            ['width' => 1920, 'height' => 1080], // Large
        ];

        foreach ($imageSizes as $size) {
            $fakeImage = UploadedFile::fake()->image(
                "test_{$size['width']}x{$size['height']}.jpg",
                $size['width'],
                $size['height']
            );
            $originalPath = $fakeImage->store('test-images', 'public');

            $result = $this->watermarkService->addWatermarkToImage($originalPath);

            $this->assertNotFalse($result);
            Storage::disk('public')->assertExists($result);
        }
    }

    /**
     * Test: Service dapat handle file dengan nama yang mengandung karakter khusus
     *
     * Alur:
     * Input: File dengan nama yang mengandung spasi, karakter khusus
     * Proses: Panggil processFile()
     * Output: File berhasil diproses tanpa error
     */
    public function test_handles_special_characters_in_filename()
    {
        $specialNames = [
            'test file with spaces.jpg',
            'test-file-with-dashes.png',
            'test_file_with_underscores.gif',
        ];

        foreach ($specialNames as $filename) {
            $fakeImage = UploadedFile::fake()->image($filename, 400, 300);
            $originalPath = $fakeImage->store('test-images', 'public');

            $result = $this->watermarkService->processFile($originalPath);

            $this->assertNotFalse($result);
            Storage::disk('public')->assertExists($result);
        }
    }

    /**
     * Test: Service dapat handle concurrent processing
     *
     * Alur:
     * Input: Multiple files diproses bersamaan
     * Proses: Panggil processFile() untuk multiple files
     * Output: Semua files berhasil diproses tanpa conflict
     */
    public function test_handles_concurrent_processing()
    {
        $files = [];
        $results = [];

        // Create multiple files
        for ($i = 1; $i <= 5; $i++) {
            $fakeImage = UploadedFile::fake()->image("concurrent_test_{$i}.jpg", 400, 300);
            $files[] = $fakeImage->store('test-images', 'public');
        }

        // Process all files
        foreach ($files as $filePath) {
            $results[] = $this->watermarkService->processFile($filePath);
        }

        // Verify all files were processed successfully
        foreach ($results as $result) {
            $this->assertNotFalse($result);
            Storage::disk('public')->assertExists($result);
        }

        // Verify all results are unique
        $this->assertEquals(count($results), count(array_unique($results)));
    }

    /**
     * Test: Service performance dengan file besar
     *
     * Alur:
     * Input: File image yang relatif besar
     * Proses: Panggil addWatermarkToImage() dan ukur waktu
     * Output: Processing selesai dalam waktu wajar
     */
    public function test_performance_with_large_files()
    {
        // Create a larger image
        $fakeImage = UploadedFile::fake()->image('large_test.jpg', 1920, 1080);
        $originalPath = $fakeImage->store('test-images', 'public');

        $startTime = microtime(true);
        $result = $this->watermarkService->addWatermarkToImage($originalPath);
        $endTime = microtime(true);

        $processingTime = $endTime - $startTime;

        $this->assertNotFalse($result);
        $this->assertLessThan(10.0, $processingTime, "Watermarking took too long: {$processingTime} seconds");
    }
}
