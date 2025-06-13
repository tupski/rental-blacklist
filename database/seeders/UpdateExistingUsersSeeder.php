<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserBalance;

class UpdateExistingUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userUpdates = [
            'ahmad.rizki@example.com' => [
                'nik' => '3201234567890123',
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 123, Bandung, Jawa Barat',
            ],
            'siti.nurhaliza@example.com' => [
                'nik' => '3301234567890124',
                'no_hp' => '082345678901',
                'alamat' => 'Jl. Sudirman No. 456, Jakarta Pusat, DKI Jakarta',
            ],
            'budi.santoso@example.com' => [
                'nik' => '3501234567890125',
                'no_hp' => '083456789012',
                'alamat' => 'Jl. Diponegoro No. 789, Surabaya, Jawa Timur',
            ],
            'dewi.lestari@example.com' => [
                'nik' => '5101234567890126',
                'no_hp' => '084567890123',
                'alamat' => 'Jl. Gajah Mada No. 321, Denpasar, Bali',
            ],
            'eko.prasetyo@example.com' => [
                'nik' => '3401234567890127',
                'no_hp' => '085678901234',
                'alamat' => 'Jl. Malioboro No. 654, Yogyakarta, DIY',
            ],
            'fitri.handayani@example.com' => [
                'nik' => '1201234567890128',
                'no_hp' => '086789012345',
                'alamat' => 'Jl. Asia Afrika No. 987, Medan, Sumatera Utara',
            ],
            'gunawan.wijaya@example.com' => [
                'nik' => '6101234567890129',
                'no_hp' => '087890123456',
                'alamat' => 'Jl. Thamrin No. 147, Pontianak, Kalimantan Barat',
            ],
            'hani.kusuma@example.com' => [
                'nik' => '7301234567890130',
                'no_hp' => '088901234567',
                'alamat' => 'Jl. Sam Ratulangi No. 258, Makassar, Sulawesi Selatan',
            ],
            'indra.kurniawan@example.com' => [
                'nik' => '1401234567890131',
                'no_hp' => '089012345678',
                'alamat' => 'Jl. Jenderal Sudirman No. 369, Pekanbaru, Riau',
            ],
            'julia.ramadhani@example.com' => [
                'nik' => '8101234567890132',
                'no_hp' => '081123456789',
                'alamat' => 'Jl. Cendrawasih No. 741, Jayapura, Papua',
            ],
            'budi@rental.com' => [
                'nik' => '3201234567890133',
                'no_hp' => '081234567891',
                'alamat' => 'Jl. Kebon Jeruk No. 88, Jakarta Barat',
            ],
            'siti@rental.com' => [
                'nik' => '3301234567890134',
                'no_hp' => '082345678902',
                'alamat' => 'Jl. Cikini Raya No. 99, Jakarta Pusat',
            ],
            'demo1@rental.com' => [
                'nik' => '3501234567890135',
                'no_hp' => '083456789013',
                'alamat' => 'Jl. Kemang Selatan No. 77, Jakarta Selatan',
            ],
            'demo2@rental.com' => [
                'nik' => '3201234567890136',
                'no_hp' => '084567890124',
                'alamat' => 'Jl. Kelapa Gading No. 66, Jakarta Utara',
            ],
            'rental@example.com' => [
                'nik' => '3301234567890137',
                'no_hp' => '085678901235',
                'alamat' => 'Jl. Rawamangun No. 55, Jakarta Timur',
            ],
            'user@example.com' => [
                'nik' => '3501234567890138',
                'no_hp' => '086789012346',
                'alamat' => 'Jl. Dago No. 44, Bandung, Jawa Barat',
            ],
        ];

        foreach ($userUpdates as $email => $data) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->update($data);

                // Create balance if not exists
                if (!$user->balance) {
                    UserBalance::create([
                        'user_id' => $user->id,
                        'balance' => rand(10000, 100000),
                    ]);
                }

                echo "Updated user: {$email}\n";
            }
        }
    }
}
