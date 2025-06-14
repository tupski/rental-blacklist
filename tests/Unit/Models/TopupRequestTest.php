<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\TopupRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

/**
 * Test Unit untuk Model TopupRequest
 *
 * Menguji semua fungsi yang berkaitan dengan TopupRequest model termasuk:
 * - Invoice number generation
 * - Status management
 * - Payment validation
 * - Expiration handling
 */
class TopupRequestTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $topupRequest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->topupRequest = new TopupRequest([
            'user_id' => $this->user->id,
            'invoice_number' => TopupRequest::generateInvoiceNumber(),
            'amount' => 50000,
            'payment_method' => 'manual',
            'payment_channel' => 'BCA',
            'status' => 'pending',
            'expires_at' => now()->addHours(24)
        ]);
        $this->topupRequest->save();
    }

    /**
     * Test: Generate invoice number dengan format yang benar
     *
     * Alur:
     * Input: Tidak ada (static method)
     * Proses: Panggil TopupRequest::generateInvoiceNumber()
     * Output: Invoice dengan format INV{YYYYMMDD}{6 random chars}
     */
    public function test_can_generate_invoice_number()
    {
        $invoiceNumber = TopupRequest::generateInvoiceNumber();

        // Check format: INV + date (8 chars) + random (6 chars) = 17 chars total
        $this->assertEquals(17, strlen($invoiceNumber));
        $this->assertStringStartsWith('INV', $invoiceNumber);

        // Check date part
        $datepart = substr($invoiceNumber, 3, 8);
        $this->assertEquals(now()->format('Ymd'), $datepart);

        // Check random part is uppercase
        $randomPart = substr($invoiceNumber, 11);
        $this->assertEquals(strtoupper($randomPart), $randomPart);
        $this->assertEquals(6, strlen($randomPart));
    }

    /**
     * Test: Invoice number harus unique
     *
     * Alur:
     * Input: Generate multiple invoice numbers
     * Proses: Panggil generateInvoiceNumber() berkali-kali
     * Output: Semua invoice number berbeda
     */
    public function test_invoice_numbers_are_unique()
    {
        $invoices = [];
        for ($i = 0; $i < 10; $i++) {
            $invoices[] = TopupRequest::generateInvoiceNumber();
        }

        // All should be unique
        $this->assertEquals(count($invoices), count(array_unique($invoices)));
    }

    /**
     * Test: Formatted amount attribute
     *
     * Alur:
     * Input: TopupRequest dengan amount 50000
     * Proses: Akses formatted_amount attribute
     * Output: "Rp 50.000"
     */
    public function test_formatted_amount_attribute()
    {
        $formatted = $this->topupRequest->formatted_amount;
        $this->assertEquals('Rp 50.000', $formatted);
    }

    /**
     * Test: Status color attribute untuk berbagai status
     *
     * Alur:
     * Input: TopupRequest dengan berbagai status
     * Proses: Akses status_color attribute
     * Output: Warna yang sesuai untuk setiap status
     */
    public function test_status_color_attribute()
    {
        $statusColors = [
            'pending' => 'warning',
            'pending_confirmation' => 'info',
            'paid' => 'info',
            'confirmed' => 'success',
            'rejected' => 'danger',
            'expired' => 'secondary'
        ];

        foreach ($statusColors as $status => $expectedColor) {
            $this->topupRequest->status = $status;
            $this->assertEquals($expectedColor, $this->topupRequest->status_color);
        }
    }

    /**
     * Test: Status text attribute untuk berbagai status
     *
     * Alur:
     * Input: TopupRequest dengan berbagai status
     * Proses: Akses status_text attribute
     * Output: Text yang sesuai untuk setiap status
     */
    public function test_status_text_attribute()
    {
        $statusTexts = [
            'pending' => 'Menunggu Pembayaran',
            'pending_confirmation' => 'Menunggu Konfirmasi',
            'paid' => 'Sudah Dibayar',
            'confirmed' => 'Dikonfirmasi',
            'rejected' => 'Ditolak',
            'expired' => 'Kadaluarsa'
        ];

        foreach ($statusTexts as $status => $expectedText) {
            $this->topupRequest->status = $status;
            $this->assertEquals($expectedText, $this->topupRequest->status_text);
        }
    }

    /**
     * Test: Check if topup request is expired
     *
     * Alur:
     * Input: TopupRequest dengan expires_at di masa lalu dan masa depan
     * Proses: Panggil isExpired()
     * Output: true jika expired, false jika belum
     */
    public function test_can_check_if_expired()
    {
        // Not expired (future date)
        $this->topupRequest->expires_at = now()->addHours(1);
        $this->assertFalse($this->topupRequest->isExpired());

        // Expired (past date)
        $this->topupRequest->expires_at = now()->subHours(1);
        $this->assertTrue($this->topupRequest->isExpired());

        // No expiration date
        $this->topupRequest->expires_at = null;
        $this->assertFalse($this->topupRequest->isExpired());
    }

    /**
     * Test: Check if topup request can be paid
     *
     * Alur:
     * Input: TopupRequest dengan berbagai status dan expiration
     * Proses: Panggil canBePaid()
     * Output: true hanya jika status pending dan belum expired
     */
    public function test_can_check_if_can_be_paid()
    {
        // Pending and not expired - can be paid
        $this->topupRequest->status = 'pending';
        $this->topupRequest->expires_at = now()->addHours(1);
        $this->assertTrue($this->topupRequest->canBePaid());

        // Pending but expired - cannot be paid
        $this->topupRequest->status = 'pending';
        $this->topupRequest->expires_at = now()->subHours(1);
        $this->assertFalse($this->topupRequest->canBePaid());

        // Not pending - cannot be paid
        $this->topupRequest->status = 'confirmed';
        $this->topupRequest->expires_at = now()->addHours(1);
        $this->assertFalse($this->topupRequest->canBePaid());
    }

    /**
     * Test: User relationship
     *
     * Alur:
     * Input: TopupRequest dengan user_id
     * Proses: Akses relasi user
     * Output: Instance User yang benar
     */
    public function test_user_relationship()
    {
        $this->assertInstanceOf(User::class, $this->topupRequest->user);
        $this->assertEquals($this->user->id, $this->topupRequest->user->id);
        $this->assertEquals($this->user->name, $this->topupRequest->user->name);
    }

    /**
     * Test: Casting attributes
     *
     * Alur:
     * Input: TopupRequest dengan berbagai data types
     * Proses: Set dan get attributes
     * Output: Data types yang benar setelah casting
     */
    public function test_attribute_casting()
    {
        $topup = new TopupRequest([
            'user_id' => $this->user->id,
            'invoice_number' => TopupRequest::generateInvoiceNumber(),
            'amount' => 50000.50,
            'payment_method' => 'manual',
            'payment_channel' => 'BCA',
            'payment_details' => ['method' => 'bank_transfer'],
            'gateway_response' => ['status' => 'success'],
            'paid_at' => '2024-01-01 10:00:00',
            'confirmed_at' => '2024-01-01 11:00:00',
            'expires_at' => '2024-01-02 10:00:00'
        ]);
        $topup->save();

        // Test decimal casting - refresh from database to get proper casting
        $topup->refresh();
        $this->assertTrue(is_numeric($topup->amount));
        $this->assertEquals(50000.50, (float)$topup->amount);

        // Test array casting
        $this->assertIsArray($topup->payment_details);
        $this->assertEquals(['method' => 'bank_transfer'], $topup->payment_details);

        $this->assertIsArray($topup->gateway_response);
        $this->assertEquals(['status' => 'success'], $topup->gateway_response);

        // Test datetime casting
        $this->assertInstanceOf(Carbon::class, $topup->paid_at);
        $this->assertInstanceOf(Carbon::class, $topup->confirmed_at);
        $this->assertInstanceOf(Carbon::class, $topup->expires_at);
    }

    /**
     * Test: Mass assignment protection
     *
     * Alur:
     * Input: Data dengan field yang fillable dan non-fillable
     * Proses: Create TopupRequest dengan mass assignment
     * Output: Hanya field fillable yang ter-assign
     */
    public function test_mass_assignment_protection()
    {
        $data = [
            'user_id' => $this->user->id,
            'invoice_number' => 'INV20240101ABC123',
            'amount' => 25000,
            'payment_method' => 'manual',
            'payment_channel' => 'BCA',
            'status' => 'pending',
            'id' => 999, // This should be ignored (not fillable)
            'created_at' => '2020-01-01', // This should be ignored (not fillable)
        ];

        $topup = TopupRequest::create($data);

        // Fillable fields should be set
        $this->assertEquals($this->user->id, $topup->user_id);
        $this->assertEquals('INV20240101ABC123', $topup->invoice_number);
        $this->assertEquals(25000, $topup->amount);
        $this->assertEquals('pending', $topup->status);

        // Non-fillable fields should be ignored
        $this->assertNotEquals(999, $topup->id);
        $this->assertNotEquals('2020-01-01', $topup->created_at->format('Y-m-d'));
    }
}
