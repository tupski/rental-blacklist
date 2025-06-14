<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\RentalBlacklist;
use App\Models\FileWatermark;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Test Feature untuk File Upload dengan Watermark
 * 
 * Menguji semua fungsi upload file dengan watermark termasuk:
 * - Upload image dengan watermark otomatis
 * - Upload video dengan watermark otomatis
 * - Validasi file types
 * - File cleanup saat delete
 * - Performance dengan multiple files
 */
class FileUploadWatermarkTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $rentalOwner;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create(['role' => 'user']);
        $this->rentalOwner = User::factory()->create(['role' => 'pengusaha_rental']);
        
        Storage::fake('public');
    }

    /**
     * Test: Upload image bukti kronologi dengan watermark otomatis
     * 
     * Alur:
     * Input: Form blacklist dengan image files
     * Proses: POST ke /blacklist dengan files
     * Output: Files ter-upload, watermark dibuat, database record tersimpan
     */
    public function test_image_upload_creates_watermark_automatically()
    {
        $imageFile = UploadedFile::fake()->image('bukti_kronologi.jpg', 800, 600);
        
        $blacklistData = [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'John Doe',
            'jenis_kelamin' => 'L',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Test No. 123',
            'jenis_rental' => 'Rental Mobil',
            'jenis_laporan' => ['Tidak Mengembalikan'],
            'kronologi' => 'Customer tidak mengembalikan mobil sesuai jadwal.',
            'tanggal_kejadian' => '2024-01-15',
            'bukti' => [$imageFile],
        ];

        $response = $this->actingAs($this->rentalOwner)
            ->post('/blacklist', $blacklistData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify blacklist created
        $blacklist = RentalBlacklist::where('nik', '1234567890123456')->first();
        $this->assertNotNull($blacklist);
        $this->assertNotNull($blacklist->bukti);
        $this->assertIsArray($blacklist->bukti);
        $this->assertCount(1, $blacklist->bukti);

        // Verify original file exists
        $originalPath = $blacklist->bukti[0];
        Storage::disk('public')->assertExists($originalPath);

        // Verify watermark record created
        $watermarkRecord = FileWatermark::where('original_path', $originalPath)
                                       ->where('watermarkable_type', RentalBlacklist::class)
                                       ->where('watermarkable_id', $blacklist->id)
                                       ->first();
        
        $this->assertNotNull($watermarkRecord);
        $this->assertEquals('jpg', $watermarkRecord->file_type);
        $this->assertGreaterThan(0, $watermarkRecord->file_size);
        $this->assertNotNull($watermarkRecord->processed_at);
        
        // Verify watermarked file exists
        if ($watermarkRecord->watermarked_path !== $originalPath) {
            Storage::disk('public')->assertExists($watermarkRecord->watermarked_path);
        }
    }

    /**
     * Test: Upload video bukti kronologi dengan watermark otomatis
     * 
     * Alur:
     * Input: Form blacklist dengan video files
     * Proses: POST ke /blacklist dengan video files
     * Output: Video ter-upload, watermark record dibuat (processing mungkin skip jika FFmpeg tidak ada)
     */
    public function test_video_upload_creates_watermark_record()
    {
        $videoFile = UploadedFile::fake()->create('bukti_video.mp4', 5000, 'video/mp4');
        
        $blacklistData = [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'John Doe',
            'jenis_kelamin' => 'L',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Test No. 123',
            'jenis_rental' => 'Rental Mobil',
            'jenis_laporan' => ['Tidak Mengembalikan'],
            'kronologi' => 'Video bukti customer merusak mobil.',
            'tanggal_kejadian' => '2024-01-15',
            'bukti' => [$videoFile],
        ];

        $response = $this->actingAs($this->rentalOwner)
            ->post('/blacklist', $blacklistData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify blacklist created
        $blacklist = RentalBlacklist::where('nik', '1234567890123456')->first();
        $this->assertNotNull($blacklist);

        // Verify original video file exists
        $originalPath = $blacklist->bukti[0];
        Storage::disk('public')->assertExists($originalPath);

        // Verify watermark record created (even if actual watermarking failed due to FFmpeg)
        $watermarkRecord = FileWatermark::where('original_path', $originalPath)
                                       ->where('watermarkable_type', RentalBlacklist::class)
                                       ->where('watermarkable_id', $blacklist->id)
                                       ->first();
        
        $this->assertNotNull($watermarkRecord);
        $this->assertEquals('mp4', $watermarkRecord->file_type);
        $this->assertGreaterThan(0, $watermarkRecord->file_size);
    }

    /**
     * Test: Upload multiple files dengan berbagai format
     * 
     * Alur:
     * Input: Form dengan image, video, dan PDF files
     * Proses: POST ke /blacklist dengan multiple files
     * Output: Semua files ter-upload, watermark dibuat untuk image/video, PDF tidak di-watermark
     */
    public function test_multiple_files_upload_with_mixed_formats()
    {
        $files = [
            UploadedFile::fake()->image('foto_bukti.jpg', 600, 400),
            UploadedFile::fake()->create('video_bukti.mp4', 3000, 'video/mp4'),
            UploadedFile::fake()->create('dokumen_kontrak.pdf', 1000, 'application/pdf'),
        ];
        
        $blacklistData = [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'John Doe',
            'jenis_kelamin' => 'L',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Test No. 123',
            'jenis_rental' => 'Rental Mobil',
            'jenis_laporan' => ['Tidak Mengembalikan', 'Merusak Barang'],
            'kronologi' => 'Customer tidak mengembalikan dan merusak mobil.',
            'tanggal_kejadian' => '2024-01-15',
            'bukti' => $files,
        ];

        $response = $this->actingAs($this->rentalOwner)
            ->post('/blacklist', $blacklistData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify blacklist created with all files
        $blacklist = RentalBlacklist::where('nik', '1234567890123456')->first();
        $this->assertCount(3, $blacklist->bukti);

        // Verify all files exist
        foreach ($blacklist->bukti as $filePath) {
            Storage::disk('public')->assertExists($filePath);
        }

        // Verify watermark records created
        $watermarkRecords = FileWatermark::where('watermarkable_type', RentalBlacklist::class)
                                        ->where('watermarkable_id', $blacklist->id)
                                        ->get();
        
        $this->assertCount(3, $watermarkRecords);

        // Check specific file types
        $imageRecord = $watermarkRecords->where('file_type', 'jpg')->first();
        $videoRecord = $watermarkRecords->where('file_type', 'mp4')->first();
        $pdfRecord = $watermarkRecords->where('file_type', 'pdf')->first();

        $this->assertNotNull($imageRecord);
        $this->assertNotNull($videoRecord);
        $this->assertNotNull($pdfRecord);

        // PDF should have same original and watermarked path (not watermarked)
        $this->assertEquals($pdfRecord->original_path, $pdfRecord->watermarked_path);
    }

    /**
     * Test: Validasi file types untuk upload bukti
     * 
     * Alur:
     * Input: Files dengan format yang tidak diizinkan
     * Proses: POST ke /blacklist dengan invalid files
     * Output: Validation error
     */
    public function test_file_type_validation_for_bukti_upload()
    {
        $invalidFile = UploadedFile::fake()->create('malicious.exe', 1000, 'application/octet-stream');
        
        $blacklistData = [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'John Doe',
            'jenis_kelamin' => 'L',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Test No. 123',
            'jenis_rental' => 'Rental Mobil',
            'jenis_laporan' => ['Tidak Mengembalikan'],
            'kronologi' => 'Test kronologi',
            'tanggal_kejadian' => '2024-01-15',
            'bukti' => [$invalidFile],
        ];

        $response = $this->actingAs($this->rentalOwner)
            ->post('/blacklist', $blacklistData);

        $response->assertSessionHasErrors(['bukti.0']);
    }

    /**
     * Test: File size validation untuk upload bukti
     * 
     * Alur:
     * Input: File yang melebihi ukuran maksimum (10MB)
     * Proses: POST ke /blacklist dengan large file
     * Output: Validation error
     */
    public function test_file_size_validation_for_bukti_upload()
    {
        // Create file larger than 10MB (10240 KB)
        $largeFile = UploadedFile::fake()->create('large_video.mp4', 15000, 'video/mp4');
        
        $blacklistData = [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'John Doe',
            'jenis_kelamin' => 'L',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Test No. 123',
            'jenis_rental' => 'Rental Mobil',
            'jenis_laporan' => ['Tidak Mengembalikan'],
            'kronologi' => 'Test kronologi',
            'tanggal_kejadian' => '2024-01-15',
            'bukti' => [$largeFile],
        ];

        $response = $this->actingAs($this->rentalOwner)
            ->post('/blacklist', $blacklistData);

        $response->assertSessionHasErrors(['bukti.0']);
    }

    /**
     * Test: File cleanup saat delete blacklist
     * 
     * Alur:
     * Input: Blacklist dengan files yang sudah ada watermark
     * Proses: DELETE blacklist
     * Output: Files dan watermark records terhapus
     */
    public function test_file_cleanup_when_deleting_blacklist()
    {
        // Create blacklist with files
        $imageFile = UploadedFile::fake()->image('test.jpg', 400, 300);
        
        $blacklistData = [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'John Doe',
            'jenis_kelamin' => 'L',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Test No. 123',
            'jenis_rental' => 'Rental Mobil',
            'jenis_laporan' => ['Tidak Mengembalikan'],
            'kronologi' => 'Test kronologi',
            'tanggal_kejadian' => '2024-01-15',
            'bukti' => [$imageFile],
        ];

        $this->actingAs($this->rentalOwner)
            ->post('/blacklist', $blacklistData);

        $blacklist = RentalBlacklist::where('nik', '1234567890123456')->first();
        $originalPath = $blacklist->bukti[0];

        // Verify file and record exist
        Storage::disk('public')->assertExists($originalPath);
        $this->assertDatabaseHas('file_watermarks', [
            'original_path' => $originalPath,
            'watermarkable_type' => RentalBlacklist::class,
            'watermarkable_id' => $blacklist->id
        ]);

        // Delete blacklist
        $response = $this->actingAs($this->rentalOwner)
            ->delete("/blacklist/{$blacklist->id}");

        $response->assertRedirect();

        // Verify file and record are deleted
        Storage::disk('public')->assertMissing($originalPath);
        $this->assertDatabaseMissing('file_watermarks', [
            'original_path' => $originalPath,
            'watermarkable_type' => RentalBlacklist::class,
            'watermarkable_id' => $blacklist->id
        ]);
    }

    /**
     * Test: Update blacklist dengan menambah dan menghapus files
     * 
     * Alur:
     * Input: Update blacklist dengan new files dan removed files
     * Proses: PUT ke /blacklist/{id} dengan file changes
     * Output: New files ter-upload dengan watermark, removed files terhapus
     */
    public function test_update_blacklist_with_file_changes()
    {
        // Create initial blacklist with one file
        $initialFile = UploadedFile::fake()->image('initial.jpg', 400, 300);
        
        $blacklistData = [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'John Doe',
            'jenis_kelamin' => 'L',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Test No. 123',
            'jenis_rental' => 'Rental Mobil',
            'jenis_laporan' => ['Tidak Mengembalikan'],
            'kronologi' => 'Initial kronologi',
            'tanggal_kejadian' => '2024-01-15',
            'bukti' => [$initialFile],
        ];

        $this->actingAs($this->rentalOwner)
            ->post('/blacklist', $blacklistData);

        $blacklist = RentalBlacklist::where('nik', '1234567890123456')->first();
        $initialPath = $blacklist->bukti[0];

        // Update with new file and remove old file
        $newFile = UploadedFile::fake()->image('new.jpg', 600, 400);
        
        $updateData = [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'John Doe Updated',
            'jenis_kelamin' => 'L',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Test No. 123',
            'jenis_rental' => 'Rental Mobil',
            'jenis_laporan' => ['Tidak Mengembalikan'],
            'kronologi' => 'Updated kronologi',
            'tanggal_kejadian' => '2024-01-15',
            'bukti' => [$newFile],
            'removed_files' => json_encode([$initialPath]),
        ];

        $response = $this->actingAs($this->rentalOwner)
            ->put("/blacklist/{$blacklist->id}", $updateData);

        $response->assertRedirect();

        // Verify old file is removed
        Storage::disk('public')->assertMissing($initialPath);
        $this->assertDatabaseMissing('file_watermarks', [
            'original_path' => $initialPath
        ]);

        // Verify new file is added with watermark
        $blacklist->refresh();
        $this->assertCount(1, $blacklist->bukti);
        
        $newPath = $blacklist->bukti[0];
        Storage::disk('public')->assertExists($newPath);
        $this->assertDatabaseHas('file_watermarks', [
            'original_path' => $newPath,
            'watermarkable_type' => RentalBlacklist::class,
            'watermarkable_id' => $blacklist->id
        ]);
    }

    /**
     * Test: Performance dengan multiple large files
     * 
     * Alur:
     * Input: Multiple files dengan ukuran besar
     * Proses: POST ke /blacklist dengan multiple large files
     * Output: Semua files diproses dalam waktu wajar
     */
    public function test_performance_with_multiple_large_files()
    {
        $files = [
            UploadedFile::fake()->image('large1.jpg', 1920, 1080),
            UploadedFile::fake()->image('large2.png', 1600, 1200),
            UploadedFile::fake()->create('large_video.mp4', 8000, 'video/mp4'), // 8MB
        ];
        
        $blacklistData = [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'John Doe',
            'jenis_kelamin' => 'L',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Test No. 123',
            'jenis_rental' => 'Rental Mobil',
            'jenis_laporan' => ['Tidak Mengembalikan'],
            'kronologi' => 'Performance test dengan multiple large files',
            'tanggal_kejadian' => '2024-01-15',
            'bukti' => $files,
        ];

        $startTime = microtime(true);
        
        $response = $this->actingAs($this->rentalOwner)
            ->post('/blacklist', $blacklistData);
        
        $endTime = microtime(true);
        $processingTime = $endTime - $startTime;

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify all files processed
        $blacklist = RentalBlacklist::where('nik', '1234567890123456')->first();
        $this->assertCount(3, $blacklist->bukti);

        // Verify watermark records created
        $watermarkCount = FileWatermark::where('watermarkable_type', RentalBlacklist::class)
                                     ->where('watermarkable_id', $blacklist->id)
                                     ->count();
        $this->assertEquals(3, $watermarkCount);

        // Processing should complete within reasonable time (30 seconds)
        $this->assertLessThan(30.0, $processingTime, "File processing took too long: {$processingTime} seconds");
    }
}
