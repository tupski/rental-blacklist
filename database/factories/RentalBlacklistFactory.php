<?php

namespace Database\Factories;

use App\Models\RentalBlacklist;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RentalBlacklist>
 */
class RentalBlacklistFactory extends Factory
{
    protected $model = RentalBlacklist::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nik' => $this->faker->numerify('################'), // 16 digit NIK
            'nama_lengkap' => $this->faker->name(),
            'jenis_kelamin' => $this->faker->randomElement(['L', 'P']),
            'no_hp' => '08' . $this->faker->numerify('##########'), // Indonesian phone format
            'alamat' => $this->faker->address(),
            'jenis_rental' => $this->faker->randomElement([
                'Rental Mobil',
                'Rental Motor',
                'Rental Kamera',
                'Rental Lainnya'
            ]),
            'jenis_laporan' => $this->faker->randomElements([
                'Tidak Mengembalikan',
                'Merusak Barang',
                'Tidak Bayar',
                'Kabur',
                'Lainnya'
            ], $this->faker->numberBetween(1, 3)),
            'status_validitas' => 'Valid', // Default to Valid for tests
            'kronologi' => $this->faker->paragraph(3),
            'bukti' => null, // Will be set separately if needed
            'tanggal_kejadian' => $this->faker->dateTimeBetween('-2 years', '-1 day'),
        ];
    }

    /**
     * Indicate that the blacklist entry is valid.
     */
    public function valid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_validitas' => 'Valid',
        ]);
    }

    /**
     * Indicate that the blacklist entry is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_validitas' => 'Pending',
        ]);
    }

    /**
     * Indicate that the blacklist entry is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_validitas' => 'Ditolak',
        ]);
    }

    /**
     * Set specific rental type.
     */
    public function rentalType(string $type): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis_rental' => $type,
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
     * Set with bukti files.
     */
    public function withBukti(array $files = null): static
    {
        $defaultFiles = [
            'bukti/sample1.jpg',
            'bukti/sample2.pdf',
        ];

        return $this->state(fn (array $attributes) => [
            'bukti' => $files ?? $defaultFiles,
        ]);
    }

    /**
     * Set specific NIK.
     */
    public function withNik(string $nik): static
    {
        return $this->state(fn (array $attributes) => [
            'nik' => $nik,
        ]);
    }

    /**
     * Set specific phone number.
     */
    public function withPhone(string $phone): static
    {
        return $this->state(fn (array $attributes) => [
            'no_hp' => $phone,
        ]);
    }

    /**
     * Set specific name.
     */
    public function withName(string $name): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_lengkap' => $name,
        ]);
    }
}
