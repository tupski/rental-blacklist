<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\UserBalance;
use App\Models\BalanceTransaction;
use App\Models\TopupRequest;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function apiKeys()
    {
        return $this->hasMany(ApiKey::class);
    }

    public function getActiveApiKey()
    {
        return $this->apiKeys()->where('is_active', true)->first();
    }

    public function createApiKey($name = 'Default API Key')
    {
        // Deactivate existing keys
        $this->apiKeys()->update(['is_active' => false]);

        // Create new key
        return $this->apiKeys()->create([
            'name' => $name,
            'key' => ApiKey::generateKey(),
            'is_active' => true
        ]);
    }

    // Relasi saldo
    public function balance(): HasOne
    {
        return $this->hasOne(UserBalance::class);
    }

    // Relasi transaksi saldo
    public function balanceTransactions(): HasMany
    {
        return $this->hasMany(BalanceTransaction::class);
    }

    // Relasi topup requests
    public function topupRequests(): HasMany
    {
        return $this->hasMany(TopupRequest::class);
    }

    // Get current balance
    public function getCurrentBalance()
    {
        $balance = $this->balance;
        return $balance ? $balance->balance : 0;
    }

    // Format balance for display
    public function getFormattedBalance()
    {
        return 'Rp ' . number_format($this->getCurrentBalance(), 0, ',', '.');
    }

    // Check if user has enough balance
    public function hasEnoughBalance($amount)
    {
        return $this->getCurrentBalance() >= $amount;
    }

    // Deduct balance
    public function deductBalance($amount, $description, $referenceType = null, $referenceId = null)
    {
        $balance = $this->balance ?? $this->balance()->create(['balance' => 0]);

        if (!$this->hasEnoughBalance($amount)) {
            throw new \Exception('Saldo tidak mencukupi');
        }

        $balanceBefore = $balance->balance;
        $balanceAfter = $balanceBefore - $amount;

        $balance->update(['balance' => $balanceAfter]);

        // Record transaction
        $this->balanceTransactions()->create([
            'type' => 'usage',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'description' => $description,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId
        ]);

        return $balanceAfter;
    }

    // Add balance
    public function addBalance($amount, $description, $referenceType = null, $referenceId = null)
    {
        $balance = $this->balance ?? $this->balance()->create(['balance' => 0]);

        $balanceBefore = $balance->balance;
        $balanceAfter = $balanceBefore + $amount;

        $balance->update(['balance' => $balanceAfter]);

        // Record transaction
        $this->balanceTransactions()->create([
            'type' => 'topup',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'description' => $description,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId
        ]);

        return $balanceAfter;
    }
}
