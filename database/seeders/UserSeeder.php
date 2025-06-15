<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin Account
        \App\Models\User::create([
            'name' => 'Administrator',
            'email' => 'admin@rentalguard.id',
            'password' => bcrypt('admin123'),
            'email_verified_at' => now(),
            'role' => 'admin',
        ]);

        // Pengusaha Rental Account
        \App\Models\User::create([
            'name' => 'Budi Santoso (Rental Motor Jakarta)',
            'email' => 'rental@example.com',
            'password' => bcrypt('rental123'),
            'email_verified_at' => now(),
            'role' => 'pengusaha_rental',
        ]);

        // User Biasa Account
        \App\Models\User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'user@example.com',
            'password' => bcrypt('user123'),
            'email_verified_at' => now(),
            'role' => 'user',
        ]);

        // Demo Accounts untuk testing
        \App\Models\User::create([
            'name' => 'Demo Rental Bandung',
            'email' => 'demo1@rental.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'role' => 'pengusaha_rental',
        ]);

        \App\Models\User::create([
            'name' => 'Demo Rental Surabaya',
            'email' => 'demo2@rental.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'role' => 'pengusaha_rental',
        ]);
    }
}
