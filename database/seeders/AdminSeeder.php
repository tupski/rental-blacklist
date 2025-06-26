<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create admin user
        User::updateOrCreate(
            ['email' => 'artupski@gmail.com'],
            [
                'name' => 'Administrator',
                'email' => 'artupski@gmail.com',
                'password' => Hash::make('Bebas2083!x'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'account_status' => 'active',
                'is_verified' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: artupski@gmail.com');
        $this->command->info('Password: Bebas2083!x');
    }
}
