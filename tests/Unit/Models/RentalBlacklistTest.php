<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\RentalBlacklist;
use App\Models\User;
use App\Helpers\PhoneHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Test Unit untuk Model RentalBlacklist
 * 
 * Menguji semua fungsi yang berkaitan dengan RentalBlacklist model termasuk:
 * - Phone number normalization
 * - Search functionality
 * - Data relationships
 * - Attribute casting
 */
class RentalBlacklistTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $blacklist;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->blacklist = RentalBlacklist::factory()->create([
            'user_id' => $this->user->id,
            'nik' => '1234567890123456',
            'nama_lengkap' => 'John Doe',
            'no_hp' => '081234567890',
            'jenis_rental' => 'Rental Mobil',
            'status_validitas' => 'Valid'
        ]);
    }

    /**
     * Test: Phone number normalization saat create/update
     * 
     * Alur:
     * Input: Nomor HP dalam berbagai format (08xx, +62xx, 62xx, 8xx)
     * Proses: Create/update RentalBlacklist
     * Output: Nomor HP dinormalisasi ke format 08xx
     */
    public function test_phone_number_normalization_on_create()
    {
        $phoneFormats = [
            '081234567890' => '081234567890', // Already normalized
            '+6281234567890' => '081234567890', // +62 format
            '6281234567890' => '081234567890',  // 62 format
            '81234567890' => '081234567890',    // 8 format
        ];

        foreach ($phoneFormats as $input => $expected) {
            $blacklist = RentalBlacklist::create([
                'user_id' => $this->user->id,
                'nik' => '1234567890123456',
                'nama_lengkap' => 'Test User',
                'jenis_kelamin' => 'L',
                'no_hp' => $input,
                'alamat' => 'Test Address',
                'jenis_rental' => 'Rental Mobil',
                'jenis_laporan' => ['Tidak Mengembalikan'],
                'status_validitas' => 'Valid',
                'kronologi' => 'Test kronologi',
                'tanggal_kejadian' => now()->subDays(1)
            ]);

            $this->assertEquals($expected, $blacklist->no_hp);
        }
    }

    /**
     * Test: Phone number normalization saat update
     * 
     * Alur:
     * Input: Update nomor HP dengan format berbeda
     * Proses: Update RentalBlacklist
     * Output: Nomor HP dinormalisasi
     */
    public function test_phone_number_normalization_on_update()
    {
        $this->blacklist->update(['no_hp' => '+6287654321098']);
        
        $this->assertEquals('087654321098', $this->blacklist->fresh()->no_hp);
    }

    /**
     * Test: Search by NIK
     * 
     * Alur:
     * Input: Search query berupa NIK (partial atau full)
     * Proses: Panggil scope search()
     * Output: Data yang sesuai dengan NIK
     */
    public function test_search_by_nik()
    {
        // Full NIK search
        $results = RentalBlacklist::search('1234567890123456')->get();
        $this->assertCount(1, $results);
        $this->assertEquals($this->blacklist->id, $results->first()->id);
        
        // Partial NIK search
        $results = RentalBlacklist::search('123456')->get();
        $this->assertCount(1, $results);
        
        // Non-matching NIK
        $results = RentalBlacklist::search('9999999999999999')->get();
        $this->assertCount(0, $results);
    }

    /**
     * Test: Search by nama lengkap
     * 
     * Alur:
     * Input: Search query berupa nama (partial atau full)
     * Proses: Panggil scope search()
     * Output: Data yang sesuai dengan nama
     */
    public function test_search_by_name()
    {
        // Full name search
        $results = RentalBlacklist::search('John Doe')->get();
        $this->assertCount(1, $results);
        
        // Partial name search
        $results = RentalBlacklist::search('John')->get();
        $this->assertCount(1, $results);
        
        // Case insensitive search
        $results = RentalBlacklist::search('john')->get();
        $this->assertCount(1, $results);
        
        // Non-matching name
        $results = RentalBlacklist::search('Jane Smith')->get();
        $this->assertCount(0, $results);
    }

    /**
     * Test: Search by phone number
     * 
     * Alur:
     * Input: Search query berupa nomor HP dalam berbagai format
     * Proses: Panggil scope search()
     * Output: Data yang sesuai dengan nomor HP
     */
    public function test_search_by_phone_number()
    {
        // Exact match
        $results = RentalBlacklist::search('081234567890')->get();
        $this->assertCount(1, $results);
        
        // Partial match
        $results = RentalBlacklist::search('08123')->get();
        $this->assertCount(1, $results);
        
        // Different format but same number
        $results = RentalBlacklist::search('+6281234567890')->get();
        $this->assertCount(1, $results);
        
        $results = RentalBlacklist::search('6281234567890')->get();
        $this->assertCount(1, $results);
        
        // Non-matching phone
        $results = RentalBlacklist::search('089999999999')->get();
        $this->assertCount(0, $results);
    }

    /**
     * Test: Search dengan multiple criteria
     * 
     * Alur:
     * Input: Multiple blacklist records, search query
     * Proses: Panggil scope search()
     * Output: Semua data yang match dengan criteria
     */
    public function test_search_multiple_criteria()
    {
        // Create additional records
        RentalBlacklist::factory()->create([
            'user_id' => $this->user->id,
            'nik' => '9876543210987654',
            'nama_lengkap' => 'Jane Smith',
            'no_hp' => '087654321098'
        ]);
        
        RentalBlacklist::factory()->create([
            'user_id' => $this->user->id,
            'nik' => '1111222233334444',
            'nama_lengkap' => 'John Smith',
            'no_hp' => '085555666677'
        ]);

        // Search should find both Johns
        $results = RentalBlacklist::search('John')->get();
        $this->assertCount(2, $results);
        
        // Search by partial phone should find specific record
        $results = RentalBlacklist::search('0876')->get();
        $this->assertCount(1, $results);
        $this->assertEquals('Jane Smith', $results->first()->nama_lengkap);
    }

    /**
     * Test: User relationship
     * 
     * Alur:
     * Input: RentalBlacklist dengan user_id
     * Proses: Akses relasi user
     * Output: Instance User yang benar
     */
    public function test_user_relationship()
    {
        $this->assertInstanceOf(User::class, $this->blacklist->user);
        $this->assertEquals($this->user->id, $this->blacklist->user->id);
        $this->assertEquals($this->user->name, $this->blacklist->user->name);
    }

    /**
     * Test: Attribute casting untuk jenis_laporan
     * 
     * Alur:
     * Input: jenis_laporan sebagai array
     * Proses: Save dan retrieve data
     * Output: Data tetap dalam format array
     */
    public function test_jenis_laporan_casting()
    {
        $jenisLaporan = ['Tidak Mengembalikan', 'Merusak Barang'];
        
        $blacklist = RentalBlacklist::create([
            'user_id' => $this->user->id,
            'nik' => '9999888877776666',
            'nama_lengkap' => 'Test User',
            'jenis_kelamin' => 'P',
            'no_hp' => '081111222233',
            'alamat' => 'Test Address',
            'jenis_rental' => 'Rental Motor',
            'jenis_laporan' => $jenisLaporan,
            'status_validitas' => 'Valid',
            'kronologi' => 'Test kronologi',
            'tanggal_kejadian' => now()->subDays(1)
        ]);

        $this->assertIsArray($blacklist->jenis_laporan);
        $this->assertEquals($jenisLaporan, $blacklist->jenis_laporan);
    }

    /**
     * Test: Attribute casting untuk bukti
     * 
     * Alur:
     * Input: bukti sebagai array file paths
     * Proses: Save dan retrieve data
     * Output: Data tetap dalam format array
     */
    public function test_bukti_casting()
    {
        $buktiFiles = ['bukti/file1.jpg', 'bukti/file2.pdf'];
        
        $this->blacklist->update(['bukti' => $buktiFiles]);
        
        $this->assertIsArray($this->blacklist->fresh()->bukti);
        $this->assertEquals($buktiFiles, $this->blacklist->fresh()->bukti);
    }

    /**
     * Test: Date casting untuk tanggal_kejadian
     * 
     * Alur:
     * Input: tanggal_kejadian sebagai string
     * Proses: Save dan retrieve data
     * Output: Data dalam format Carbon date
     */
    public function test_tanggal_kejadian_casting()
    {
        $tanggal = '2024-01-15';
        
        $this->blacklist->update(['tanggal_kejadian' => $tanggal]);
        
        $this->assertInstanceOf(\Carbon\Carbon::class, $this->blacklist->fresh()->tanggal_kejadian);
        $this->assertEquals('2024-01-15', $this->blacklist->fresh()->tanggal_kejadian->format('Y-m-d'));
    }

    /**
     * Test: Mass assignment protection
     * 
     * Alur:
     * Input: Data dengan field fillable dan non-fillable
     * Proses: Create RentalBlacklist dengan mass assignment
     * Output: Hanya field fillable yang ter-assign
     */
    public function test_mass_assignment_protection()
    {
        $data = [
            'user_id' => $this->user->id,
            'nik' => '5555666677778888',
            'nama_lengkap' => 'Test User',
            'jenis_kelamin' => 'L',
            'no_hp' => '081234567890',
            'alamat' => 'Test Address',
            'jenis_rental' => 'Rental Mobil',
            'jenis_laporan' => ['Tidak Bayar'],
            'status_validitas' => 'Valid',
            'kronologi' => 'Test',
            'tanggal_kejadian' => now()->subDays(1),
            'id' => 999, // Should be ignored
            'created_at' => '2020-01-01', // Should be ignored
        ];

        $blacklist = RentalBlacklist::create($data);
        
        // Fillable fields should be set
        $this->assertEquals('5555666677778888', $blacklist->nik);
        $this->assertEquals('Test User', $blacklist->nama_lengkap);
        
        // Non-fillable fields should be ignored
        $this->assertNotEquals(999, $blacklist->id);
        $this->assertNotEquals('2020-01-01', $blacklist->created_at->format('Y-m-d'));
    }
}
