<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\RentalBlacklist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Test Feature untuk Blacklist Management
 * 
 * Menguji semua fungsi pengelolaan blacklist termasuk:
 * - Create blacklist entry
 * - Search functionality
 * - Data censoring
 * - File upload
 * - User access control
 */
class BlacklistManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $rentalOwner;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create(['role' => 'user']);
        $this->rentalOwner = User::factory()->create(['role' => 'pengusaha_rental']);
        $this->admin = User::factory()->create(['role' => 'admin']);
        
        Storage::fake('public');
    }

    /**
     * Test: Rental owner dapat membuat blacklist entry
     * 
     * Alur:
     * Input: Form blacklist dengan data lengkap dan file bukti
     * Proses: POST ke /blacklist
     * Output: Blacklist entry dibuat, file ter-upload, redirect dengan success
     */
    public function test_rental_owner_can_create_blacklist_entry()
    {
        $file = UploadedFile::fake()->image('bukti.jpg');
        
        $blacklistData = [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'John Doe',
            'jenis_kelamin' => 'L',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Test No. 123',
            'jenis_rental' => 'Rental Mobil',
            'jenis_laporan' => ['Tidak Mengembalikan', 'Merusak Barang'],
            'kronologi' => 'Customer tidak mengembalikan mobil sesuai jadwal dan merusak interior.',
            'tanggal_kejadian' => '2024-01-15',
            'bukti' => [$file],
        ];

        $response = $this->actingAs($this->rentalOwner)
            ->post('/blacklist', $blacklistData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify blacklist created
        $this->assertDatabaseHas('rental_blacklist', [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'John Doe',
            'no_hp' => '081234567890',
            'user_id' => $this->rentalOwner->id,
            'status_validitas' => 'Pending',
        ]);

        // Verify file uploaded
        $blacklist = RentalBlacklist::where('nik', '1234567890123456')->first();
        $this->assertNotNull($blacklist->bukti);
        $this->assertIsArray($blacklist->bukti);
        
        foreach ($blacklist->bukti as $filePath) {
            Storage::disk('public')->assertExists($filePath);
        }
    }

    /**
     * Test: Regular user tidak dapat membuat blacklist entry
     * 
     * Alur:
     * Input: Regular user mencoba POST ke /blacklist
     * Proses: POST ke /blacklist
     * Output: Forbidden atau redirect
     */
    public function test_regular_user_cannot_create_blacklist_entry()
    {
        $blacklistData = [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'John Doe',
            'jenis_kelamin' => 'L',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Test No. 123',
            'jenis_rental' => 'Rental Mobil',
            'jenis_laporan' => ['Tidak Mengembalikan'],
            'kronologi' => 'Test',
            'tanggal_kejadian' => '2024-01-15',
        ];

        $response = $this->actingAs($this->user)
            ->post('/blacklist', $blacklistData);

        // Should be forbidden or redirected
        $this->assertTrue(in_array($response->getStatusCode(), [403, 302]));
        
        $this->assertDatabaseMissing('rental_blacklist', [
            'nik' => '1234567890123456',
        ]);
    }

    /**
     * Test: Validation error untuk data yang tidak lengkap
     * 
     * Alur:
     * Input: Form blacklist dengan data tidak lengkap
     * Proses: POST ke /blacklist
     * Output: Validation errors
     */
    public function test_blacklist_creation_validation()
    {
        $incompleteData = [
            'nik' => '123', // Too short
            'nama_lengkap' => '', // Required
            'jenis_kelamin' => 'X', // Invalid
            'no_hp' => '', // Required
            'jenis_laporan' => [], // Required array min:1
            'tanggal_kejadian' => '2025-12-31', // Future date
        ];

        $response = $this->actingAs($this->rentalOwner)
            ->post('/blacklist', $incompleteData);

        $response->assertSessionHasErrors([
            'nik',
            'nama_lengkap',
            'jenis_kelamin',
            'no_hp',
            'alamat',
            'jenis_rental',
            'jenis_laporan',
            'kronologi',
            'tanggal_kejadian',
        ]);
    }

    /**
     * Test: Public search dengan data censoring
     * 
     * Alur:
     * Input: Search query dari public user
     * Proses: GET ke /search dengan query
     * Output: Data ter-sensor untuk non-rental owner
     */
    public function test_public_search_with_data_censoring()
    {
        // Create blacklist entry
        RentalBlacklist::factory()->create([
            'user_id' => $this->rentalOwner->id,
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Angga Dwi Saputra',
            'no_hp' => '081234567890',
            'status_validitas' => 'Valid',
        ]);

        // Search as guest (should be censored)
        $response = $this->get('/search?q=Angga');

        $response->assertStatus(200);
        $response->assertSee('Angga D****a'); // Censored name
        $response->assertDontSee('Angga Dwi Saputra'); // Full name should not be visible
        $response->assertDontSee('1234567890123456'); // NIK should be censored
        $response->assertDontSee('081234567890'); // Phone should be censored
    }

    /**
     * Test: Rental owner melihat data tanpa sensor
     * 
     * Alur:
     * Input: Search query dari rental owner
     * Proses: GET ke /search dengan query sebagai rental owner
     * Output: Data tidak ter-sensor
     */
    public function test_rental_owner_sees_uncensored_data()
    {
        // Create blacklist entry
        RentalBlacklist::factory()->create([
            'user_id' => $this->rentalOwner->id,
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Angga Dwi Saputra',
            'no_hp' => '081234567890',
            'status_validitas' => 'Valid',
        ]);

        // Search as rental owner (should not be censored)
        $response = $this->actingAs($this->rentalOwner)
            ->get('/search?q=Angga');

        $response->assertStatus(200);
        $response->assertSee('Angga Dwi Saputra'); // Full name visible
        $response->assertSee('1234567890123456'); // NIK visible
        $response->assertSee('081234567890'); // Phone visible
    }

    /**
     * Test: User dapat unlock data dengan saldo
     * 
     * Alur:
     * Input: User dengan saldo cukup, blacklist data
     * Proses: POST ke /blacklist/{id}/unlock
     * Output: Saldo berkurang, data ter-unlock, dapat melihat full data
     */
    public function test_user_can_unlock_data_with_sufficient_balance()
    {
        // Give user balance
        $this->user->addBalance(10000, 'Test balance');
        
        // Create blacklist entry
        $blacklist = RentalBlacklist::factory()->create([
            'user_id' => $this->rentalOwner->id,
            'jenis_rental' => 'Rental Mobil',
            'status_validitas' => 'Valid',
        ]);

        $response = $this->actingAs($this->user)
            ->post("/blacklist/{$blacklist->id}/unlock");

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify balance deducted (Rental Mobil = 1500)
        $this->assertEquals(8500, $this->user->getCurrentBalance());

        // Verify unlock record created
        $this->assertDatabaseHas('user_unlocks', [
            'user_id' => $this->user->id,
            'blacklist_id' => $blacklist->id,
            'amount_paid' => 1500,
        ]);

        // Verify user can now see full data
        $this->assertTrue($this->user->hasUnlockedData($blacklist->id));
    }

    /**
     * Test: User tidak dapat unlock tanpa saldo cukup
     * 
     * Alur:
     * Input: User dengan saldo tidak cukup
     * Proses: POST ke /blacklist/{id}/unlock
     * Output: Error message, saldo tidak berubah
     */
    public function test_user_cannot_unlock_without_sufficient_balance()
    {
        // Give user small balance
        $this->user->addBalance(1000, 'Small balance');
        
        $blacklist = RentalBlacklist::factory()->create([
            'jenis_rental' => 'Rental Mobil', // Costs 1500
            'status_validitas' => 'Valid',
        ]);

        $response = $this->actingAs($this->user)
            ->post("/blacklist/{$blacklist->id}/unlock");

        $response->assertRedirect();
        $response->assertSessionHas('error');

        // Balance should remain unchanged
        $this->assertEquals(1000, $this->user->getCurrentBalance());

        // No unlock record should be created
        $this->assertDatabaseMissing('user_unlocks', [
            'user_id' => $this->user->id,
            'blacklist_id' => $blacklist->id,
        ]);
    }

    /**
     * Test: Search dengan phone number normalization
     * 
     * Alur:
     * Input: Search query dengan berbagai format nomor HP
     * Proses: GET ke /search dengan query nomor HP
     * Output: Data ditemukan meskipun format berbeda
     */
    public function test_search_with_phone_normalization()
    {
        RentalBlacklist::factory()->create([
            'no_hp' => '081234567890',
            'status_validitas' => 'Valid',
        ]);

        $phoneFormats = [
            '081234567890',
            '+6281234567890',
            '6281234567890',
            '81234567890',
        ];

        foreach ($phoneFormats as $searchPhone) {
            $response = $this->get("/search?q={$searchPhone}");
            $response->assertStatus(200);
            $response->assertSee('081234567890'); // Should find the record
        }
    }

    /**
     * Test: Admin dapat melihat semua data tanpa sensor
     * 
     * Alur:
     * Input: Admin user
     * Proses: GET ke admin blacklist pages
     * Output: Semua data visible tanpa sensor
     */
    public function test_admin_sees_all_uncensored_data()
    {
        RentalBlacklist::factory()->create([
            'nik' => '1234567890123456',
            'nama_lengkap' => 'Angga Dwi Saputra',
            'no_hp' => '081234567890',
            'status_validitas' => 'Valid',
        ]);

        $response = $this->actingAs($this->admin)
            ->get('/admin/blacklist');

        $response->assertStatus(200);
        $response->assertSee('Angga Dwi Saputra');
        $response->assertSee('1234567890123456');
        $response->assertSee('081234567890');
    }

    /**
     * Test: File upload dengan berbagai format
     * 
     * Alur:
     * Input: Upload file dengan format jpg, png, pdf
     * Proses: POST blacklist dengan multiple files
     * Output: Semua file ter-upload dengan benar
     */
    public function test_multiple_file_upload()
    {
        $files = [
            UploadedFile::fake()->image('bukti1.jpg'),
            UploadedFile::fake()->image('bukti2.png'),
            UploadedFile::fake()->create('bukti3.pdf', 1000, 'application/pdf'),
        ];

        $blacklistData = [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'John Doe',
            'jenis_kelamin' => 'L',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Test No. 123',
            'jenis_rental' => 'Rental Mobil',
            'jenis_laporan' => ['Tidak Mengembalikan'],
            'kronologi' => 'Test',
            'tanggal_kejadian' => '2024-01-15',
            'bukti' => $files,
        ];

        $response = $this->actingAs($this->rentalOwner)
            ->post('/blacklist', $blacklistData);

        $response->assertRedirect();

        $blacklist = RentalBlacklist::where('nik', '1234567890123456')->first();
        $this->assertCount(3, $blacklist->bukti);

        foreach ($blacklist->bukti as $filePath) {
            Storage::disk('public')->assertExists($filePath);
        }
    }

    /**
     * Test: Search pagination
     * 
     * Alur:
     * Input: Banyak blacklist entries, search query
     * Proses: GET ke /search dengan pagination
     * Output: Results ter-paginate dengan benar
     */
    public function test_search_pagination()
    {
        // Create 25 blacklist entries
        for ($i = 1; $i <= 25; $i++) {
            RentalBlacklist::factory()->create([
                'nama_lengkap' => "Test User {$i}",
                'status_validitas' => 'Valid',
            ]);
        }

        $response = $this->get('/search?q=Test User');

        $response->assertStatus(200);
        // Should show pagination links
        $response->assertSee('Next');
        
        // Test second page
        $response = $this->get('/search?q=Test User&page=2');
        $response->assertStatus(200);
    }
}
