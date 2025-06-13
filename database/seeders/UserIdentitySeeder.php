<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserBalance;
use Illuminate\Support\Facades\Hash;

class UserIdentitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Ahmad Rizki Pratama',
                'email' => 'ahmad.rizki@example.com',
                'password' => Hash::make('password123'),
                'role' => 'pengusaha_rental',
                'nik' => '3201234567890123',
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 123, Bandung, Jawa Barat',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'nik' => '3301234567890124',
                'no_hp' => '082345678901',
                'alamat' => 'Jl. Sudirman No. 456, Jakarta Pusat, DKI Jakarta',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'password' => Hash::make('password123'),
                'role' => 'pengusaha_rental',
                'nik' => '3501234567890125',
                'no_hp' => '083456789012',
                'alamat' => 'Jl. Diponegoro No. 789, Surabaya, Jawa Timur',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'nik' => '5101234567890126',
                'no_hp' => '084567890123',
                'alamat' => 'Jl. Gajah Mada No. 321, Denpasar, Bali',
                'email_verified_at' => null,
            ],
            [
                'name' => 'Eko Prasetyo',
                'email' => 'eko.prasetyo@example.com',
                'password' => Hash::make('password123'),
                'role' => 'pengusaha_rental',
                'nik' => '3401234567890127',
                'no_hp' => '085678901234',
                'alamat' => 'Jl. Malioboro No. 654, Yogyakarta, DIY',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Fitri Handayani',
                'email' => 'fitri.handayani@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'nik' => '1201234567890128',
                'no_hp' => '086789012345',
                'alamat' => 'Jl. Asia Afrika No. 987, Medan, Sumatera Utara',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Gunawan Wijaya',
                'email' => 'gunawan.wijaya@example.com',
                'password' => Hash::make('password123'),
                'role' => 'pengusaha_rental',
                'nik' => '6101234567890129',
                'no_hp' => '087890123456',
                'alamat' => 'Jl. Thamrin No. 147, Pontianak, Kalimantan Barat',
                'email_verified_at' => null,
            ],
            [
                'name' => 'Hani Kusuma',
                'email' => 'hani.kusuma@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'nik' => '7301234567890130',
                'no_hp' => '088901234567',
                'alamat' => 'Jl. Sam Ratulangi No. 258, Makassar, Sulawesi Selatan',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Indra Kurniawan',
                'email' => 'indra.kurniawan@example.com',
                'password' => Hash::make('password123'),
                'role' => 'pengusaha_rental',
                'nik' => '1401234567890131',
                'no_hp' => '089012345678',
                'alamat' => 'Jl. Jenderal Sudirman No. 369, Pekanbaru, Riau',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Julia Ramadhani',
                'email' => 'julia.ramadhani@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'nik' => '8101234567890132',
                'no_hp' => '081123456789',
                'alamat' => 'Jl. Cendrawasih No. 741, Jayapura, Papua',
                'email_verified_at' => null,
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);

            // Create balance for each user
            UserBalance::create([
                'user_id' => $user->id,
                'balance' => rand(10000, 100000), // Random balance between 10k-100k
            ]);
        }
    }
}
