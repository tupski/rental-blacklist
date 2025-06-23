<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\UserBalance;
use App\Models\BalanceTransaction;
use App\Models\TopupRequest;
use App\Models\UserUnlock;
use App\Models\RentalBlacklist;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Test Unit untuk Model User
 *
 * Menguji semua fungsi yang berkaitan dengan User model termasuk:
 * - Balance management (pengelolaan saldo)
 * - User unlock functionality (fitur buka data)
 * - Relasi dengan model lain
 */
class UserTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'user'
        ]);

        // Clean up any existing balance to avoid unique constraint issues
        $this->user->balance()?->delete();
    }

    /**
     * Test: User dapat membuat balance baru jika belum ada
     *
     * Alur:
     * Input: User tanpa balance
     * Proses: Panggil getCurrentBalance()
     * Output: Return 0 dan buat UserBalance baru
     */
    public function test_user_can_get_current_balance_when_no_balance_exists()
    {
        // Pastikan user belum punya balance
        $this->assertNull($this->user->balance);

        // Test getCurrentBalance
        $balance = $this->user->getCurrentBalance();

        // Harus return 0
        $this->assertEquals(0, $balance);
    }

    /**
     * Test: User dapat menambah saldo dengan benar
     *
     * Alur:
     * Input: User, amount (10000), description
     * Proses: Panggil addBalance()
     * Output: Saldo bertambah, transaction tercatat
     */
    public function test_user_can_add_balance()
    {
        $amount = 10000;
        $description = 'Test topup';

        // Add balance
        $newBalance = $this->user->addBalance($amount, $description);

        // Refresh user to get updated balance
        $this->user->refresh();

        // Verify balance updated
        $this->assertEquals($amount, $newBalance);
        $this->assertEquals($amount, $this->user->getCurrentBalance());

        // Verify transaction recorded
        $transaction = $this->user->balanceTransactions()->first();
        $this->assertNotNull($transaction);
        $this->assertEquals('topup', $transaction->type);
        $this->assertEquals($amount, $transaction->amount);
        $this->assertEquals(0, $transaction->balance_before);
        $this->assertEquals($amount, $transaction->balance_after);
        $this->assertEquals($description, $transaction->description);
    }

    /**
     * Test: User dapat mengurangi saldo dengan benar
     *
     * Alur:
     * Input: User dengan saldo 10000, deduct 5000
     * Proses: Panggil deductBalance()
     * Output: Saldo berkurang menjadi 5000, transaction tercatat
     */
    public function test_user_can_deduct_balance()
    {
        // Setup: Add initial balance
        $this->user->addBalance(10000, 'Initial balance');
        $this->user->refresh();

        $deductAmount = 5000;
        $description = 'Test deduction';

        // Deduct balance
        $newBalance = $this->user->deductBalance($deductAmount, $description);
        $this->user->refresh();

        // Verify balance updated
        $this->assertEquals(5000, $newBalance);
        $this->assertEquals(5000, $this->user->getCurrentBalance());

        // Verify transaction recorded
        $transaction = $this->user->balanceTransactions()->where('type', 'usage')->first();
        $this->assertNotNull($transaction);
        $this->assertEquals('usage', $transaction->type);
        $this->assertEquals($deductAmount, $transaction->amount);
        $this->assertEquals(10000, $transaction->balance_before);
        $this->assertEquals(5000, $transaction->balance_after);
    }

    /**
     * Test: User tidak dapat mengurangi saldo jika tidak mencukupi
     *
     * Alur:
     * Input: User dengan saldo 1000, deduct 5000
     * Proses: Panggil deductBalance()
     * Output: Exception thrown, saldo tidak berubah
     */
    public function test_user_cannot_deduct_balance_when_insufficient()
    {
        // Setup: Add small balance
        $this->user->addBalance(1000, 'Small balance');
        $this->user->refresh();

        // Try to deduct more than available
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Saldo tidak mencukupi');

        $this->user->deductBalance(5000, 'Test deduction');
    }

    /**
     * Test: User dapat mengecek apakah saldo mencukupi
     *
     * Alur:
     * Input: User dengan saldo 10000, check amount 5000 dan 15000
     * Proses: Panggil hasEnoughBalance()
     * Output: true untuk 5000, false untuk 15000
     */
    public function test_user_can_check_sufficient_balance()
    {
        $this->user->addBalance(10000, 'Test balance');
        $this->user->refresh();

        $this->assertTrue($this->user->hasEnoughBalance(5000));
        $this->assertTrue($this->user->hasEnoughBalance(10000));
        $this->assertFalse($this->user->hasEnoughBalance(15000));
    }

    /**
     * Test: User dapat unlock data blacklist
     *
     * Alur:
     * Input: User dengan saldo, blacklist data, amount
     * Proses: Panggil unlockBlacklistData()
     * Output: Saldo berkurang, unlock record dibuat
     */
    public function test_user_can_unlock_blacklist_data()
    {
        // Setup
        $this->user->addBalance(10000, 'Initial balance');
        $this->user->refresh();
        $blacklist = RentalBlacklist::factory()->create();
        $amount = 1500;
        $description = 'Unlock blacklist data';

        // Unlock data
        $this->user->unlockData($blacklist->id, $amount, $description);

        // Verify balance deducted
        $this->assertEquals(8500, $this->user->getCurrentBalance());

        // Verify unlock record created
        $unlock = UserUnlock::where('user_id', $this->user->id)
                           ->where('blacklist_id', $blacklist->id)
                           ->first();
        $this->assertNotNull($unlock);
        $this->assertEquals($amount, $unlock->amount_paid);

        // Verify user has unlocked the data
        $this->assertTrue($this->user->hasUnlockedData($blacklist->id));
    }

    /**
     * Test: User tidak dapat unlock data yang sudah di-unlock
     *
     * Alur:
     * Input: User yang sudah unlock data sebelumnya
     * Proses: Panggil unlockBlacklistData() lagi
     * Output: Exception thrown
     */
    public function test_user_cannot_unlock_already_unlocked_data()
    {
        // Setup
        $this->user->addBalance(10000, 'Initial balance');
        $this->user->refresh();
        $blacklist = RentalBlacklist::factory()->create();

        // First unlock
        $this->user->unlockData($blacklist->id, 1500, 'First unlock');

        // Try to unlock again
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Data sudah dibuka sebelumnya');

        $this->user->unlockData($blacklist->id, 1500, 'Second unlock');
    }

    /**
     * Test: User tidak dapat unlock jika saldo tidak mencukupi
     *
     * Alur:
     * Input: User dengan saldo kecil, unlock amount besar
     * Proses: Panggil unlockBlacklistData()
     * Output: Exception thrown
     */
    public function test_user_cannot_unlock_with_insufficient_balance()
    {
        // Setup
        $this->user->addBalance(1000, 'Small balance');
        $blacklist = RentalBlacklist::factory()->create();

        // Try to unlock with insufficient balance
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Saldo tidak mencukupi');

        $this->user->unlockData($blacklist->id, 5000, 'Unlock attempt');
    }



    /**
     * Test: User relationships
     *
     * Alur:
     * Input: User dengan berbagai relasi
     * Proses: Test semua relasi
     * Output: Relasi berfungsi dengan benar
     */
    public function test_user_relationships()
    {
        // Test balance relationship
        $this->user->addBalance(5000, 'Test');
        $this->user->refresh();
        $this->assertInstanceOf(UserBalance::class, $this->user->balance);

        // Test balance transactions relationship
        $transactions = $this->user->balanceTransactions;
        $this->assertCount(1, $transactions);
        $this->assertInstanceOf(BalanceTransaction::class, $transactions->first());

        // Test topup requests relationship
        $topup = TopupRequest::factory()->create(['user_id' => $this->user->id]);
        $this->assertCount(1, $this->user->topupRequests);
        $this->assertInstanceOf(TopupRequest::class, $this->user->topupRequests->first());

        // Test user unlocks relationship
        $blacklist = RentalBlacklist::factory()->create();
        $this->user->unlockData($blacklist->id, 1500, 'Test unlock');
        $this->assertCount(1, $this->user->userUnlocks);
        $this->assertInstanceOf(UserUnlock::class, $this->user->userUnlocks->first());
    }
}
