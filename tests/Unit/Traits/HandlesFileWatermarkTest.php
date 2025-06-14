<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use App\Models\RentalBlacklist;
use App\Models\FileWatermark;
use App\Models\User;
use App\Traits\HandlesFileWatermark;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Test Unit untuk HandlesFileWatermark Trait
 *
 * Menguji semua fungsi trait watermark termasuk:
 * - File processing dengan watermark
 * - Database record creation
 * - File cleanup
 * - Reprocessing functionality
 */
class HandlesFileWatermarkTest extends TestCase
{
    use RefreshDatabase;

    protected $testModel;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->user = User::factory()->create();
        $this->testModel = new class {
            use HandlesFileWatermark;

            public $id = 1;

            public function processUploadedFilesPublic($files, $model)
            {
                return $this->processUploadedFiles($files, $model);
            }

            public function createWatermarkedFilePublic($originalPath, $model, $watermarkService)
            {
                return $this->createWatermarkedFile($originalPath, $model, $watermarkService);
            }

            public function removeFilesWithWatermarkPublic($files, $model)
            {
                return $this->removeFilesWithWatermark($files, $model);
            }
        };
    }

    /**
     * Test: Trait dapat memproses uploaded files dengan watermark
     *
     * Alur:
     * Input: Array file paths, model instance
     * Proses: Panggil processUploadedFiles()
     * Output: Files diproses, watermark records dibuat
     */
    public function test_can_process_uploaded_files_with_watermark()
    {
        // Create test blacklist
        $blacklist = RentalBlacklist::factory()->create(['user_id' => $this->user->id]);

        // Create fake files
        $imageFile = UploadedFile::fake()->image('test.jpg', 800, 600);
        $videoFile = UploadedFile::fake()->create('test.mp4', 5000, 'video/mp4');
        $pdfFile = UploadedFile::fake()->create('test.pdf', 1000, 'application/pdf');

        $imagePath = $imageFile->store('test-files', 'public');
        $videoPath = $videoFile->store('test-files', 'public');
        $pdfPath = $pdfFile->store('test-files', 'public');

        $files = [$imagePath, $videoPath, $pdfPath];

        // Process files
        $result = $this->testModel->processUploadedFilesPublic($files, $blacklist);

        // Verify all files are returned
        $this->assertCount(3, $result);
        $this->assertEquals($files, $result);

        // Verify watermark records are created for watermarkable files
        $watermarkRecords = FileWatermark::where('watermarkable_type', get_class($blacklist))
                                        ->where('watermarkable_id', $blacklist->id)
                                        ->get();

        // Should have records for image and video (PDF should also have a record but not watermarked)
        $this->assertGreaterThanOrEqual(2, $watermarkRecords->count());

        // Verify image watermark record
        $imageWatermark = $watermarkRecords->where('file_type', 'jpg')->first();
        $this->assertNotNull($imageWatermark);
        $this->assertEquals($imagePath, $imageWatermark->original_path);
        $this->assertNotNull($imageWatermark->watermarked_path);
        $this->assertNotNull($imageWatermark->processed_at);
    }

    /**
     * Test: Trait dapat handle null atau empty files
     *
     * Alur:
     * Input: null, empty array, non-array
     * Proses: Panggil processUploadedFiles()
     * Output: Return empty array tanpa error
     */
    public function test_handles_null_and_empty_files()
    {
        $blacklist = RentalBlacklist::factory()->create(['user_id' => $this->user->id]);

        // Test null
        $result = $this->testModel->processUploadedFilesPublic(null, $blacklist);
        $this->assertEquals([], $result);

        // Test empty array
        $result = $this->testModel->processUploadedFilesPublic([], $blacklist);
        $this->assertEquals([], $result);

        // Test non-array
        $result = $this->testModel->processUploadedFilesPublic('not-an-array', $blacklist);
        $this->assertEquals([], $result);
    }

    /**
     * Test: Trait dapat membuat file record untuk non-watermarkable files
     *
     * Alur:
     * Input: PDF file yang tidak bisa di-watermark
     * Proses: Panggil processUploadedFiles()
     * Output: File record dibuat tanpa watermark
     */
    public function test_creates_file_record_for_non_watermarkable_files()
    {
        $blacklist = RentalBlacklist::factory()->create(['user_id' => $this->user->id]);

        $pdfFile = UploadedFile::fake()->create('document.pdf', 1000, 'application/pdf');
        $pdfPath = $pdfFile->store('test-files', 'public');

        $this->testModel->processUploadedFilesPublic([$pdfPath], $blacklist);

        // Verify file record is created
        $fileRecord = FileWatermark::where('original_path', $pdfPath)->first();
        $this->assertNotNull($fileRecord);
        $this->assertEquals($pdfPath, $fileRecord->original_path);
        $this->assertNull($fileRecord->watermarked_path); // Null for non-watermarkable files
        $this->assertEquals('pdf', $fileRecord->file_type);
    }

    /**
     * Test: Trait dapat menghapus files dengan cleanup watermark
     *
     * Alur:
     * Input: Array file paths yang akan dihapus
     * Proses: Panggil removeFilesWithWatermark()
     * Output: Files dan watermark records dihapus
     */
    public function test_can_remove_files_with_watermark_cleanup()
    {
        $blacklist = RentalBlacklist::factory()->create(['user_id' => $this->user->id]);

        // Create and process files
        $imageFile = UploadedFile::fake()->image('test.jpg', 400, 300);
        $imagePath = $imageFile->store('test-files', 'public');

        $this->testModel->processUploadedFilesPublic([$imagePath], $blacklist);

        // Verify file and record exist
        Storage::disk('public')->assertExists($imagePath);
        $this->assertDatabaseHas('file_watermarks', [
            'original_path' => $imagePath,
            'watermarkable_type' => get_class($blacklist),
            'watermarkable_id' => $blacklist->id
        ]);

        // Remove files
        $this->testModel->removeFilesWithWatermarkPublic([$imagePath], $blacklist);

        // Verify file and record are deleted
        Storage::disk('public')->assertMissing($imagePath);
        $this->assertDatabaseMissing('file_watermarks', [
            'original_path' => $imagePath,
            'watermarkable_type' => get_class($blacklist),
            'watermarkable_id' => $blacklist->id
        ]);
    }

    /**
     * Test: Trait dapat reprocess watermarks untuk existing files
     *
     * Alur:
     * Input: Model dengan bukti files yang belum ada watermark record
     * Proses: Panggil reprocessWatermarks()
     * Output: Watermark records dibuat untuk files yang belum ada
     */
    public function test_can_reprocess_watermarks_for_existing_files()
    {
        // Create blacklist with bukti files
        $imageFile = UploadedFile::fake()->image('existing.jpg', 400, 300);
        $imagePath = $imageFile->store('test-files', 'public');

        $blacklist = RentalBlacklist::factory()->create([
            'user_id' => $this->user->id,
            'bukti' => [$imagePath]
        ]);

        // Verify no watermark record exists initially
        $this->assertDatabaseMissing('file_watermarks', [
            'original_path' => $imagePath,
            'watermarkable_type' => get_class($blacklist),
            'watermarkable_id' => $blacklist->id
        ]);

        // Reprocess watermarks
        $this->testModel->reprocessWatermarks($blacklist);

        // Verify watermark record is created
        $this->assertDatabaseHas('file_watermarks', [
            'original_path' => $imagePath,
            'watermarkable_type' => get_class($blacklist),
            'watermarkable_id' => $blacklist->id
        ]);
    }

    /**
     * Test: Trait tidak membuat duplicate watermark records
     *
     * Alur:
     * Input: File yang sudah ada watermark record
     * Proses: Panggil reprocessWatermarks() lagi
     * Output: Tidak ada duplicate record dibuat
     */
    public function test_does_not_create_duplicate_watermark_records()
    {
        $blacklist = RentalBlacklist::factory()->create(['user_id' => $this->user->id]);

        $imageFile = UploadedFile::fake()->image('test.jpg', 400, 300);
        $imagePath = $imageFile->store('test-files', 'public');

        // Process files first time
        $this->testModel->processUploadedFilesPublic([$imagePath], $blacklist);

        $initialCount = FileWatermark::where('original_path', $imagePath)->count();
        $this->assertEquals(1, $initialCount);

        // Update blacklist bukti and reprocess
        $blacklist->update(['bukti' => [$imagePath]]);
        $this->testModel->reprocessWatermarks($blacklist);

        // Verify no duplicate record is created
        $finalCount = FileWatermark::where('original_path', $imagePath)->count();
        $this->assertEquals(1, $finalCount);
    }

    /**
     * Test: Trait dapat handle model tanpa bukti field
     *
     * Alur:
     * Input: Model yang tidak memiliki bukti field atau bukti kosong
     * Proses: Panggil reprocessWatermarks()
     * Output: Tidak ada error, method return tanpa processing
     */
    public function test_handles_model_without_bukti_field()
    {
        $blacklist = RentalBlacklist::factory()->create([
            'user_id' => $this->user->id,
            'bukti' => null
        ]);

        // Should not throw error
        $this->testModel->reprocessWatermarks($blacklist);

        // Test with empty array
        $blacklist->update(['bukti' => []]);
        $this->testModel->reprocessWatermarks($blacklist);

        // Verify no watermark records created
        $this->assertEquals(0, FileWatermark::where('watermarkable_id', $blacklist->id)->count());
    }

    /**
     * Test: Trait dapat handle file size calculation
     *
     * Alur:
     * Input: Files dengan berbagai ukuran
     * Proses: Panggil processUploadedFiles()
     * Output: File size tercatat dengan benar di database
     */
    public function test_records_correct_file_sizes()
    {
        $blacklist = RentalBlacklist::factory()->create(['user_id' => $this->user->id]);

        // Create files with different sizes
        $smallFile = UploadedFile::fake()->image('small.jpg', 100, 100);
        $largeFile = UploadedFile::fake()->image('large.jpg', 1920, 1080);

        $smallPath = $smallFile->store('test-files', 'public');
        $largePath = $largeFile->store('test-files', 'public');

        $this->testModel->processUploadedFilesPublic([$smallPath, $largePath], $blacklist);

        // Verify file sizes are recorded
        $smallRecord = FileWatermark::where('original_path', $smallPath)->first();
        $largeRecord = FileWatermark::where('original_path', $largePath)->first();

        $this->assertNotNull($smallRecord);
        $this->assertNotNull($largeRecord);
        $this->assertGreaterThan(0, $smallRecord->file_size);
        $this->assertGreaterThan(0, $largeRecord->file_size);
        $this->assertGreaterThan($smallRecord->file_size, $largeRecord->file_size);
    }

    /**
     * Test: Trait dapat handle error saat watermark processing gagal
     *
     * Alur:
     * Input: File yang menyebabkan error saat watermark
     * Proses: Panggil processUploadedFiles()
     * Output: Error di-handle gracefully, file tetap diproses
     */
    public function test_handles_watermark_processing_errors_gracefully()
    {
        $blacklist = RentalBlacklist::factory()->create(['user_id' => $this->user->id]);

        // Create a file that might cause watermark error (non-existent path)
        $invalidPath = 'non-existent/file.jpg';

        // Should not throw exception
        $result = $this->testModel->processUploadedFilesPublic([$invalidPath], $blacklist);

        // File should still be in result even if watermark failed
        $this->assertContains($invalidPath, $result);
    }
}
