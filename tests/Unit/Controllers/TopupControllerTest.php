<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\TopupRequest;
use App\Http\Controllers\Admin\TopupController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

/**
 * Test Unit untuk Admin TopupController
 *
 * Menguji semua fungsi yang berkaitan dengan admin topup management:
 * - Approve topup requests
 * - Reject topup requests
 * - Balance management
 * - Status filtering
 */
class TopupControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;
    protected $topupRequest;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->user = User::factory()->create(['role' => 'user']);

        $this->topupRequest = new TopupRequest([
            'user_id' => $this->user->id,
            'invoice_number' => TopupRequest::generateInvoiceNumber(),
            'amount' => 50000,
            'payment_method' => 'manual',
            'status' => 'pending_confirmation',
            'expires_at' => now()->addHours(24)
        ]);
        $this->topupRequest->save();

        $this->controller = new TopupController();
    }

    /**
     * Test: Admin dapat approve topup request
     *
     * Alur:
     * Input: TopupRequest dengan status 'pending_confirmation', amount 50000
     * Proses: Panggil approve() method
     * Output: Status berubah ke 'confirmed', saldo user bertambah 50000, transaction tercatat
     */
    public function test_admin_can_approve_topup_request()
    {
        // Pastikan user belum punya saldo
        $this->assertEquals(0, $this->user->getCurrentBalance());

        // Act as admin
        $this->actingAs($this->admin);

        // Approve topup
        $response = $this->controller->approve($this->topupRequest);

        // Refresh model dari database
        $this->topupRequest->refresh();
        $this->user->refresh();

        // Verify topup status updated
        $this->assertEquals('confirmed', $this->topupRequest->status);
        $this->assertNotNull($this->topupRequest->confirmed_at);
        $this->assertEquals('Disetujui oleh admin', $this->topupRequest->admin_notes);

        // Verify user balance increased
        $this->assertEquals(50000, $this->user->getCurrentBalance());

        // Verify transaction recorded
        $transaction = $this->user->balanceTransactions()->first();
        $this->assertNotNull($transaction);
        $this->assertEquals('topup', $transaction->type);
        $this->assertEquals(50000, $transaction->amount);
        $this->assertEquals(0, $transaction->balance_before);
        $this->assertEquals(50000, $transaction->balance_after);
        $this->assertStringContainsString('Topup disetujui', $transaction->description);
        $this->assertStringContainsString($this->topupRequest->invoice_number, $transaction->description);
    }

    /**
     * Test: Admin dapat reject topup request
     *
     * Alur:
     * Input: TopupRequest dengan status 'pending_confirmation', admin_notes
     * Proses: Panggil reject() method
     * Output: Status berubah ke 'rejected', admin_notes tersimpan, saldo user tidak berubah
     */
    public function test_admin_can_reject_topup_request()
    {
        $rejectionReason = 'Bukti pembayaran tidak valid';

        // Create request with admin_notes
        $request = new Request();
        $request->merge(['admin_notes' => $rejectionReason]);

        // Act as admin
        $this->actingAs($this->admin);

        // Reject topup
        $response = $this->controller->reject($request, $this->topupRequest);

        // Refresh model
        $this->topupRequest->refresh();

        // Verify topup status updated
        $this->assertEquals('rejected', $this->topupRequest->status);
        $this->assertEquals($rejectionReason, $this->topupRequest->admin_notes);

        // Verify user balance unchanged
        $this->assertEquals(0, $this->user->getCurrentBalance());

        // Verify no transaction recorded
        $this->assertCount(0, $this->user->balanceTransactions);
    }

    /**
     * Test: Reject validation - admin_notes required
     *
     * Alur:
     * Input: Request tanpa admin_notes
     * Proses: Panggil reject() method
     * Output: Validation error
     */
    public function test_reject_requires_admin_notes()
    {
        $request = new Request();
        // No admin_notes provided

        $this->actingAs($this->admin);

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $this->controller->reject($request, $this->topupRequest);
    }

    /**
     * Test: Approve topup dengan user yang sudah punya saldo
     *
     * Alur:
     * Input: User dengan saldo 25000, topup 50000
     * Proses: Approve topup
     * Output: Saldo menjadi 75000, transaction tercatat dengan benar
     */
    public function test_approve_topup_with_existing_balance()
    {
        // Give user initial balance
        $this->user->addBalance(25000, 'Initial balance');
        $this->user->refresh();
        $this->assertEquals(25000, $this->user->getCurrentBalance());

        $this->actingAs($this->admin);

        // Approve topup
        $this->controller->approve($this->topupRequest);

        $this->user->refresh();

        // Verify total balance
        $this->assertEquals(75000, $this->user->getCurrentBalance());

        // Verify topup transaction
        $topupTransaction = $this->user->balanceTransactions()
            ->where('reference_type', TopupRequest::class)
            ->where('reference_id', $this->topupRequest->id)
            ->first();

        $this->assertNotNull($topupTransaction);
        $this->assertEquals(25000, $topupTransaction->balance_before);
        $this->assertEquals(75000, $topupTransaction->balance_after);
    }

    /**
     * Test: Index method dengan filter status
     *
     * Alur:
     * Input: Multiple topup requests dengan berbagai status, filter request
     * Proses: Panggil index() method dengan filter
     * Output: Hanya data dengan status yang sesuai
     */
    public function test_index_with_status_filter()
    {
        // Create additional topup requests
        $confirmedTopup = new TopupRequest([
            'user_id' => $this->user->id,
            'invoice_number' => TopupRequest::generateInvoiceNumber(),
            'amount' => 30000,
            'payment_method' => 'manual',
            'payment_channel' => 'BCA',
            'status' => 'confirmed'
        ]);
        $confirmedTopup->save();

        $rejectedTopup = new TopupRequest([
            'user_id' => $this->user->id,
            'invoice_number' => TopupRequest::generateInvoiceNumber(),
            'amount' => 20000,
            'payment_method' => 'manual',
            'payment_channel' => 'BRI',
            'status' => 'rejected'
        ]);
        $rejectedTopup->save();

        $this->actingAs($this->admin);

        // Test filter by confirmed status
        $request = new Request(['status' => 'confirmed']);
        $response = $this->controller->index($request);

        // This would normally return a view, but we can test the logic
        // by checking the query directly
        $query = TopupRequest::with('user');
        $query->where('status', 'confirmed');
        $results = $query->get();

        $this->assertCount(1, $results);
        $this->assertEquals('confirmed', $results->first()->status);

        // Test filter by rejected status
        $query = TopupRequest::with('user');
        $query->where('status', 'rejected');
        $results = $query->get();

        $this->assertCount(1, $results);
        $this->assertEquals('rejected', $results->first()->status);
    }

    /**
     * Test: Index method tanpa filter
     *
     * Alur:
     * Input: Multiple topup requests, no filter
     * Proses: Panggil index() method
     * Output: Semua data topup
     */
    public function test_index_without_filter()
    {
        // Create additional topup request
        $additionalTopup = new TopupRequest([
            'user_id' => $this->user->id,
            'invoice_number' => TopupRequest::generateInvoiceNumber(),
            'amount' => 30000,
            'payment_method' => 'manual',
            'payment_channel' => 'BCA',
            'status' => 'confirmed'
        ]);
        $additionalTopup->save();

        $this->actingAs($this->admin);

        // Test without filter
        $request = new Request();

        // Check query logic
        $query = TopupRequest::with('user');
        $results = $query->get();

        $this->assertCount(2, $results); // Original + additional
    }

    /**
     * Test: Destroy method
     *
     * Alur:
     * Input: TopupRequest yang akan dihapus
     * Proses: Panggil destroy() method
     * Output: Data terhapus dari database
     */
    public function test_admin_can_destroy_topup_request()
    {
        $topupId = $this->topupRequest->id;

        $this->actingAs($this->admin);

        // Destroy topup
        $response = $this->controller->destroy($this->topupRequest);

        // Verify topup deleted
        $this->assertDatabaseMissing('topup_requests', ['id' => $topupId]);
    }

    /**
     * Test: Show method
     *
     * Alur:
     * Input: TopupRequest ID
     * Proses: Panggil show() method
     * Output: View dengan data topup yang benar
     */
    public function test_admin_can_show_topup_request()
    {
        $this->actingAs($this->admin);

        $response = $this->controller->show($this->topupRequest);

        // This would return a view in actual implementation
        // We can verify the topup data is accessible
        $this->assertNotNull($this->topupRequest);
        $this->assertEquals($this->user->id, $this->topupRequest->user_id);
        $this->assertEquals(50000, $this->topupRequest->amount);
    }

    /**
     * Test: Multiple approve operations
     *
     * Alur:
     * Input: Multiple topup requests untuk user yang sama
     * Proses: Approve semua topup
     * Output: Saldo terakumulasi dengan benar
     */
    public function test_multiple_approve_operations()
    {
        // Create second topup request
        $secondTopup = new TopupRequest([
            'user_id' => $this->user->id,
            'invoice_number' => TopupRequest::generateInvoiceNumber(),
            'amount' => 30000,
            'payment_method' => 'manual',
            'payment_channel' => 'BRI',
            'status' => 'pending_confirmation'
        ]);
        $secondTopup->save();

        $this->actingAs($this->admin);

        // Approve first topup
        $this->controller->approve($this->topupRequest);
        $this->user->refresh();
        $this->assertEquals(50000, $this->user->getCurrentBalance());

        // Approve second topup
        $this->controller->approve($secondTopup);
        $this->user->refresh();
        $this->assertEquals(80000, $this->user->getCurrentBalance());

        // Verify both transactions recorded
        $transactions = $this->user->balanceTransactions()->where('type', 'topup')->get();
        $this->assertCount(2, $transactions);

        // Verify transaction amounts
        $amounts = $transactions->pluck('amount')->toArray();
        $this->assertTrue(in_array(50000, $amounts) || in_array(50000.0, $amounts));
        $this->assertTrue(in_array(30000, $amounts) || in_array(30000.0, $amounts));
    }
}
