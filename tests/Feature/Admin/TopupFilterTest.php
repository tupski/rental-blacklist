<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\TopupRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test Feature untuk Filter Admin Topup
 * 
 * Menguji semua fungsi filter di halaman admin topup termasuk:
 * - Filter berdasarkan status
 * - Filter berdasarkan nomor invoice
 * - Filter berdasarkan nama/email user
 * - Filter berdasarkan tanggal
 * - Filter berdasarkan jumlah
 */
class TopupFilterTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user1;
    protected $user2;
    protected $topup1;
    protected $topup2;
    protected $topup3;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->admin = User::factory()->create(['role' => 'admin']);
        
        // Create regular users
        $this->user1 = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'user'
        ]);
        
        $this->user2 = User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'role' => 'user'
        ]);

        // Create topup requests with different statuses and amounts
        $this->topup1 = TopupRequest::factory()->create([
            'user_id' => $this->user1->id,
            'invoice_number' => 'INV20241201ABC123',
            'amount' => 50000,
            'status' => 'pending',
            'payment_method' => 'manual',
            'payment_channel' => 'BCA',
            'created_at' => now()->subDays(2)
        ]);

        $this->topup2 = TopupRequest::factory()->create([
            'user_id' => $this->user2->id,
            'invoice_number' => 'INV20241202DEF456',
            'amount' => 100000,
            'status' => 'confirmed',
            'payment_method' => 'manual',
            'payment_channel' => 'BRI',
            'created_at' => now()->subDay()
        ]);

        $this->topup3 = TopupRequest::factory()->create([
            'user_id' => $this->user1->id,
            'invoice_number' => 'INV20241203GHI789',
            'amount' => 25000,
            'status' => 'rejected',
            'payment_method' => 'manual',
            'payment_channel' => 'BJB',
            'created_at' => now()
        ]);
    }

    /**
     * Test: Filter berdasarkan status
     * 
     * Alur:
     * Input: Request dengan parameter status
     * Proses: Filter topup berdasarkan status
     * Output: Hanya topup dengan status yang sesuai
     */
    public function test_filter_by_status()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.isi-saldo.indeks', ['status' => 'pending']));

        $response->assertStatus(200);
        $response->assertSee($this->topup1->invoice_number);
        $response->assertDontSee($this->topup2->invoice_number);
        $response->assertDontSee($this->topup3->invoice_number);
    }

    /**
     * Test: Filter berdasarkan nomor invoice
     * 
     * Alur:
     * Input: Request dengan parameter invoice
     * Proses: Filter topup berdasarkan nomor invoice (partial match)
     * Output: Hanya topup dengan invoice yang mengandung keyword
     */
    public function test_filter_by_invoice_number()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.isi-saldo.indeks', ['invoice' => 'ABC123']));

        $response->assertStatus(200);
        $response->assertSee($this->topup1->invoice_number);
        $response->assertDontSee($this->topup2->invoice_number);
        $response->assertDontSee($this->topup3->invoice_number);
    }

    /**
     * Test: Filter berdasarkan nama user
     * 
     * Alur:
     * Input: Request dengan parameter user (nama)
     * Proses: Filter topup berdasarkan nama user
     * Output: Hanya topup dari user dengan nama yang sesuai
     */
    public function test_filter_by_user_name()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.isi-saldo.indeks', ['user' => 'John']));

        $response->assertStatus(200);
        $response->assertSee($this->topup1->invoice_number);
        $response->assertSee($this->topup3->invoice_number);
        $response->assertDontSee($this->topup2->invoice_number);
    }

    /**
     * Test: Filter berdasarkan email user
     * 
     * Alur:
     * Input: Request dengan parameter user (email)
     * Proses: Filter topup berdasarkan email user
     * Output: Hanya topup dari user dengan email yang sesuai
     */
    public function test_filter_by_user_email()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.isi-saldo.indeks', ['user' => 'jane@example.com']));

        $response->assertStatus(200);
        $response->assertSee($this->topup2->invoice_number);
        $response->assertDontSee($this->topup1->invoice_number);
        $response->assertDontSee($this->topup3->invoice_number);
    }

    /**
     * Test: Filter berdasarkan tanggal
     * 
     * Alur:
     * Input: Request dengan parameter tanggal_dari dan tanggal_sampai
     * Proses: Filter topup berdasarkan rentang tanggal
     * Output: Hanya topup dalam rentang tanggal yang ditentukan
     */
    public function test_filter_by_date_range()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.isi-saldo.indeks', [
                'tanggal_dari' => now()->subDay()->format('Y-m-d'),
                'tanggal_sampai' => now()->format('Y-m-d')
            ]));

        $response->assertStatus(200);
        $response->assertSee($this->topup2->invoice_number);
        $response->assertSee($this->topup3->invoice_number);
        $response->assertDontSee($this->topup1->invoice_number);
    }

    /**
     * Test: Filter berdasarkan jumlah minimum
     * 
     * Alur:
     * Input: Request dengan parameter jumlah_min
     * Proses: Filter topup berdasarkan jumlah minimum
     * Output: Hanya topup dengan jumlah >= minimum
     */
    public function test_filter_by_minimum_amount()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.isi-saldo.indeks', ['jumlah_min' => 50000]));

        $response->assertStatus(200);
        $response->assertSee($this->topup1->invoice_number);
        $response->assertSee($this->topup2->invoice_number);
        $response->assertDontSee($this->topup3->invoice_number);
    }

    /**
     * Test: Filter berdasarkan jumlah maksimum
     * 
     * Alur:
     * Input: Request dengan parameter jumlah_max
     * Proses: Filter topup berdasarkan jumlah maksimum
     * Output: Hanya topup dengan jumlah <= maksimum
     */
    public function test_filter_by_maximum_amount()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.isi-saldo.indeks', ['jumlah_max' => 50000]));

        $response->assertStatus(200);
        $response->assertSee($this->topup1->invoice_number);
        $response->assertSee($this->topup3->invoice_number);
        $response->assertDontSee($this->topup2->invoice_number);
    }

    /**
     * Test: Filter kombinasi multiple parameters
     * 
     * Alur:
     * Input: Request dengan multiple filter parameters
     * Proses: Filter topup berdasarkan kombinasi filter
     * Output: Hanya topup yang memenuhi semua kriteria
     */
    public function test_filter_by_multiple_parameters()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.isi-saldo.indeks', [
                'status' => 'pending',
                'user' => 'John',
                'jumlah_min' => 40000
            ]));

        $response->assertStatus(200);
        $response->assertSee($this->topup1->invoice_number);
        $response->assertDontSee($this->topup2->invoice_number);
        $response->assertDontSee($this->topup3->invoice_number);
    }

    /**
     * Test: Tampilan nomor invoice di tabel
     * 
     * Alur:
     * Input: Request ke halaman admin topup
     * Proses: Render halaman dengan data topup
     * Output: Nomor invoice ditampilkan di tabel
     */
    public function test_invoice_number_displayed_in_table()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.isi-saldo.indeks'));

        $response->assertStatus(200);
        $response->assertSee('Invoice'); // Header kolom
        $response->assertSee($this->topup1->invoice_number);
        $response->assertSee($this->topup2->invoice_number);
        $response->assertSee($this->topup3->invoice_number);
    }

    /**
     * Test: Statistik cards menampilkan data yang benar
     * 
     * Alur:
     * Input: Request ke halaman admin topup
     * Proses: Hitung statistik dari semua topup
     * Output: Statistik cards menampilkan angka yang benar
     */
    public function test_statistics_cards_display_correct_data()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.isi-saldo.indeks'));

        $response->assertStatus(200);
        
        // Check pending count (1 pending)
        $response->assertSee('1'); // Menunggu Persetujuan
        
        // Check confirmed count (1 confirmed)
        $response->assertSee('1'); // Disetujui
        
        // Check rejected count (1 rejected)
        $response->assertSee('1'); // Ditolak
        
        // Check total confirmed amount (Rp 100,000)
        $response->assertSee('100.000'); // Total Disetujui
    }

    /**
     * Test: Filter form tetap menampilkan nilai yang dipilih
     * 
     * Alur:
     * Input: Request dengan filter parameters
     * Proses: Render halaman dengan filter aktif
     * Output: Form filter menampilkan nilai yang dipilih
     */
    public function test_filter_form_retains_selected_values()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.isi-saldo.indeks', [
                'status' => 'pending',
                'invoice' => 'ABC',
                'user' => 'John'
            ]));

        $response->assertStatus(200);
        $response->assertSee('value="ABC"', false);
        $response->assertSee('value="John"', false);
        $response->assertSee('selected', false); // Status pending selected
    }
}
