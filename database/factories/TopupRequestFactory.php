<?php

namespace Database\Factories;

use App\Models\TopupRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TopupRequest>
 */
class TopupRequestFactory extends Factory
{
    protected $model = TopupRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'invoice_number' => TopupRequest::generateInvoiceNumber(),
            'amount' => $this->faker->randomElement([10000, 25000, 50000, 75000, 100000]),
            'payment_method' => $this->faker->randomElement(['manual', 'midtrans', 'xendit']),
            'payment_channel' => $this->faker->randomElement(['BCA', 'BRI', 'BJB', 'GoPay', 'Dana', 'OVO']),
            'payment_details' => [
                'user_agent' => $this->faker->userAgent(),
                'ip_address' => $this->faker->ipv4(),
                'created_via' => 'web',
            ],
            'status' => $this->faker->randomElement(['pending', 'pending_confirmation', 'paid', 'confirmed', 'rejected', 'expired']),
            'proof_of_payment' => null,
            'notes' => $this->faker->optional()->sentence(),
            'admin_notes' => null,
            'paid_at' => null,
            'confirmed_at' => null,
            'expires_at' => $this->faker->dateTimeBetween('now', '+24 hours'),
            'gateway_response' => null,
        ];
    }

    /**
     * Indicate that the topup request is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'expires_at' => now()->addHours(24),
        ]);
    }

    /**
     * Indicate that the topup request is pending confirmation.
     */
    public function pendingConfirmation(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending_confirmation',
            'proof_of_payment' => 'topup-proofs/proof_' . $this->faker->uuid . '.jpg',
        ]);
    }

    /**
     * Indicate that the topup request is confirmed.
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
            'confirmed_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'admin_notes' => 'Disetujui oleh admin',
        ]);
    }

    /**
     * Indicate that the topup request is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'admin_notes' => $this->faker->sentence(),
        ]);
    }

    /**
     * Indicate that the topup request is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'expired',
            'expires_at' => $this->faker->dateTimeBetween('-1 week', '-1 day'),
        ]);
    }

    /**
     * Set specific amount.
     */
    public function amount(int $amount): static
    {
        return $this->state(fn (array $attributes) => [
            'amount' => $amount,
        ]);
    }

    /**
     * Set specific user.
     */
    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Set manual payment method.
     */
    public function manual(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => 'manual',
            'payment_channel' => $this->faker->randomElement(['BCA', 'BRI', 'BJB']),
        ]);
    }

    /**
     * Set with payment proof.
     */
    public function withProof(): static
    {
        return $this->state(fn (array $attributes) => [
            'proof_of_payment' => 'topup-proofs/proof_' . $this->faker->uuid . '.jpg',
            'status' => 'pending_confirmation',
        ]);
    }

    /**
     * Set with gateway response.
     */
    public function withGatewayResponse(array $response = null): static
    {
        $defaultResponse = [
            'transaction_id' => $this->faker->uuid,
            'status' => 'success',
            'payment_type' => 'bank_transfer',
            'transaction_time' => now()->toISOString(),
        ];

        return $this->state(fn (array $attributes) => [
            'gateway_response' => $response ?? $defaultResponse,
            'payment_method' => 'midtrans',
        ]);
    }

    /**
     * Set as expired.
     */
    public function expiredYesterday(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->subDay(),
            'status' => 'pending',
        ]);
    }

    /**
     * Set as valid (not expired).
     */
    public function validUntilTomorrow(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->addDay(),
            'status' => 'pending',
        ]);
    }
}
