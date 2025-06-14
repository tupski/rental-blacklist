<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\FileWatermark;
use App\Models\RentalBlacklist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Test Unit untuk FileWatermark Model
 * 
 * Menguji semua fungsi model FileWatermark termasuk:
 * - Relationships dengan model lain
 * - Attribute accessors dan mutators
 * - File size formatting
 * - File type detection
 * - Display path logic
 */
class FileWatermarkTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $blacklist;

    protected function setUp(): void
    {
        parent::setUp();
        
        Storage::fake('public');
        
        $this->user = User::factory()->create();
        $this->blacklist = RentalBlacklist::factory()->create(['user_id' => $this->user->id]);
    }

    /**
     * Test: Model dapat membuat watermark record dengan benar
     * 
     * Alur:
     * Input: Data watermark yang valid
     * Proses: Create FileWatermark
     * Output: Record tersimpan dengan data yang benar
     */
    public function test_can_create_watermark_record()
    {
        $watermarkData = [
            'original_path' => 'test-files/image.jpg',
            'watermarked_path' => 'test-files/watermarked_image.jpg',
            'file_type' => 'jpg',
            'file_size' => 1024000, // 1MB
            'watermarkable_type' => RentalBlacklist::class,
            'watermarkable_id' => $this->blacklist->id,
            'processed_at' => now()
        ];

        $watermark = FileWatermark::create($watermarkData);

        $this->assertNotNull($watermark);
        $this->assertEquals('test-files/image.jpg', $watermark->original_path);
        $this->assertEquals('test-files/watermarked_image.jpg', $watermark->watermarked_path);
        $this->assertEquals('jpg', $watermark->file_type);
        $this->assertEquals(1024000, $watermark->file_size);
        $this->assertNotNull($watermark->processed_at);
    }

    /**
     * Test: Model memiliki relasi watermarkable yang benar
     * 
     * Alur:
     * Input: FileWatermark dengan watermarkable_type dan watermarkable_id
     * Proses: Akses relasi watermarkable
     * Output: Instance model yang benar
     */
    public function test_has_correct_watermarkable_relationship()
    {
        $watermark = FileWatermark::create([
            'original_path' => 'test-files/image.jpg',
            'watermarked_path' => 'test-files/watermarked_image.jpg',
            'file_type' => 'jpg',
            'file_size' => 1024000,
            'watermarkable_type' => RentalBlacklist::class,
            'watermarkable_id' => $this->blacklist->id,
            'processed_at' => now()
        ]);

        $relatedModel = $watermark->watermarkable;

        $this->assertInstanceOf(RentalBlacklist::class, $relatedModel);
        $this->assertEquals($this->blacklist->id, $relatedModel->id);
        $this->assertEquals($this->blacklist->nama_lengkap, $relatedModel->nama_lengkap);
    }

    /**
     * Test: Model dapat memformat file size dengan benar
     * 
     * Alur:
     * Input: File size dalam bytes
     * Proses: Akses formatted_file_size attribute
     * Output: String dengan format yang readable
     */
    public function test_formats_file_size_correctly()
    {
        $testCases = [
            500 => '500 B',
            1024 => '1 KB',
            1536 => '1.5 KB', // 1.5 KB
            1048576 => '1 MB', // 1 MB
            1572864 => '1.5 MB', // 1.5 MB
            1073741824 => '1 GB', // 1 GB
        ];

        foreach ($testCases as $bytes => $expected) {
            $watermark = FileWatermark::create([
                'original_path' => 'test-files/test.jpg',
                'watermarked_path' => 'test-files/watermarked_test.jpg',
                'file_type' => 'jpg',
                'file_size' => $bytes,
                'watermarkable_type' => RentalBlacklist::class,
                'watermarkable_id' => $this->blacklist->id,
                'processed_at' => now()
            ]);

            $this->assertEquals($expected, $watermark->formatted_file_size);
        }
    }

    /**
     * Test: Model dapat mendeteksi file type image
     * 
     * Alur:
     * Input: FileWatermark dengan berbagai file types
     * Proses: Panggil isImage()
     * Output: true untuk image types, false untuk lainnya
     */
    public function test_can_detect_image_file_types()
    {
        $imageTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $nonImageTypes = ['mp4', 'avi', 'pdf', 'doc'];

        // Test image types
        foreach ($imageTypes as $type) {
            $watermark = FileWatermark::create([
                'original_path' => "test-files/test.{$type}",
                'watermarked_path' => "test-files/watermarked_test.{$type}",
                'file_type' => $type,
                'file_size' => 1024,
                'watermarkable_type' => RentalBlacklist::class,
                'watermarkable_id' => $this->blacklist->id,
                'processed_at' => now()
            ]);

            $this->assertTrue($watermark->isImage(), "File type {$type} should be detected as image");
        }

        // Test non-image types
        foreach ($nonImageTypes as $type) {
            $watermark = FileWatermark::create([
                'original_path' => "test-files/test.{$type}",
                'watermarked_path' => "test-files/watermarked_test.{$type}",
                'file_type' => $type,
                'file_size' => 1024,
                'watermarkable_type' => RentalBlacklist::class,
                'watermarkable_id' => $this->blacklist->id,
                'processed_at' => now()
            ]);

            $this->assertFalse($watermark->isImage(), "File type {$type} should not be detected as image");
        }
    }

    /**
     * Test: Model dapat mendeteksi file type video
     * 
     * Alur:
     * Input: FileWatermark dengan berbagai file types
     * Proses: Panggil isVideo()
     * Output: true untuk video types, false untuk lainnya
     */
    public function test_can_detect_video_file_types()
    {
        $videoTypes = ['mp4', 'avi', 'mov', 'mkv'];
        $nonVideoTypes = ['jpg', 'png', 'pdf', 'doc'];

        // Test video types
        foreach ($videoTypes as $type) {
            $watermark = FileWatermark::create([
                'original_path' => "test-files/test.{$type}",
                'watermarked_path' => "test-files/watermarked_test.{$type}",
                'file_type' => $type,
                'file_size' => 1024,
                'watermarkable_type' => RentalBlacklist::class,
                'watermarkable_id' => $this->blacklist->id,
                'processed_at' => now()
            ]);

            $this->assertTrue($watermark->isVideo(), "File type {$type} should be detected as video");
        }

        // Test non-video types
        foreach ($nonVideoTypes as $type) {
            $watermark = FileWatermark::create([
                'original_path' => "test-files/test.{$type}",
                'watermarked_path' => "test-files/watermarked_test.{$type}",
                'file_type' => $type,
                'file_size' => 1024,
                'watermarkable_type' => RentalBlacklist::class,
                'watermarkable_id' => $this->blacklist->id,
                'processed_at' => now()
            ]);

            $this->assertFalse($watermark->isVideo(), "File type {$type} should not be detected as video");
        }
    }

    /**
     * Test: Model dapat mengembalikan display path yang benar berdasarkan user role
     * 
     * Alur:
     * Input: User dengan role berbeda (admin, user, null)
     * Proses: Panggil getDisplayPath()
     * Output: Path yang sesuai dengan role user
     */
    public function test_returns_correct_display_path_based_on_user_role()
    {
        $watermark = FileWatermark::create([
            'original_path' => 'test-files/image.jpg',
            'watermarked_path' => 'test-files/watermarked_image.jpg',
            'file_type' => 'jpg',
            'file_size' => 1024,
            'watermarkable_type' => RentalBlacklist::class,
            'watermarkable_id' => $this->blacklist->id,
            'processed_at' => now()
        ]);

        // Admin should get original path
        $admin = User::factory()->create(['role' => 'admin']);
        $this->assertEquals('test-files/image.jpg', $watermark->getDisplayPath($admin));

        // Regular user should get watermarked path
        $user = User::factory()->create(['role' => 'user']);
        $this->assertEquals('test-files/watermarked_image.jpg', $watermark->getDisplayPath($user));

        // Null user should get watermarked path
        $this->assertEquals('test-files/watermarked_image.jpg', $watermark->getDisplayPath(null));
    }

    /**
     * Test: Model dapat handle watermarked_path yang null
     * 
     * Alur:
     * Input: FileWatermark dengan watermarked_path null (non-watermarkable file)
     * Proses: Panggil getDisplayPath()
     * Output: Original path untuk semua user
     */
    public function test_handles_null_watermarked_path()
    {
        $watermark = FileWatermark::create([
            'original_path' => 'test-files/document.pdf',
            'watermarked_path' => null, // Non-watermarkable file
            'file_type' => 'pdf',
            'file_size' => 1024,
            'watermarkable_type' => RentalBlacklist::class,
            'watermarkable_id' => $this->blacklist->id,
            'processed_at' => now()
        ]);

        // All users should get original path when watermarked_path is null
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $this->assertEquals('test-files/document.pdf', $watermark->getDisplayPath($admin));
        $this->assertEquals('test-files/document.pdf', $watermark->getDisplayPath($user));
        $this->assertEquals('test-files/document.pdf', $watermark->getDisplayPath(null));
    }

    /**
     * Test: Model casting processed_at ke Carbon instance
     * 
     * Alur:
     * Input: processed_at sebagai string
     * Proses: Save dan retrieve data
     * Output: processed_at sebagai Carbon instance
     */
    public function test_casts_processed_at_to_carbon()
    {
        $watermark = FileWatermark::create([
            'original_path' => 'test-files/image.jpg',
            'watermarked_path' => 'test-files/watermarked_image.jpg',
            'file_type' => 'jpg',
            'file_size' => 1024,
            'watermarkable_type' => RentalBlacklist::class,
            'watermarkable_id' => $this->blacklist->id,
            'processed_at' => '2024-01-15 10:30:00'
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $watermark->processed_at);
        $this->assertEquals('2024-01-15 10:30:00', $watermark->processed_at->format('Y-m-d H:i:s'));
    }

    /**
     * Test: Model mass assignment protection
     * 
     * Alur:
     * Input: Data dengan field fillable dan non-fillable
     * Proses: Create dengan mass assignment
     * Output: Hanya field fillable yang ter-assign
     */
    public function test_mass_assignment_protection()
    {
        $data = [
            'original_path' => 'test-files/image.jpg',
            'watermarked_path' => 'test-files/watermarked_image.jpg',
            'file_type' => 'jpg',
            'file_size' => 1024,
            'watermarkable_type' => RentalBlacklist::class,
            'watermarkable_id' => $this->blacklist->id,
            'processed_at' => now(),
            'id' => 999, // Should not be mass assignable
            'created_at' => '2020-01-01 00:00:00', // Should not be mass assignable
        ];

        $watermark = FileWatermark::create($data);

        // Fillable fields should be assigned
        $this->assertEquals('test-files/image.jpg', $watermark->original_path);
        $this->assertEquals('test-files/watermarked_image.jpg', $watermark->watermarked_path);
        $this->assertEquals('jpg', $watermark->file_type);

        // Non-fillable fields should not be assigned
        $this->assertNotEquals(999, $watermark->id);
        $this->assertNotEquals('2020-01-01 00:00:00', $watermark->created_at->format('Y-m-d H:i:s'));
    }
}
