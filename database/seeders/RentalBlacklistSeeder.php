<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RentalBlacklist;
use Illuminate\Support\Facades\Hash;

class RentalBlacklistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test users
        $user1 = User::firstOrCreate(
            ['email' => 'budi@rental.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'pengusaha_rental'
            ]
        );

        $user2 = User::firstOrCreate(
            ['email' => 'siti@rental.com'],
            [
                'name' => 'Siti Aminah',
                'password' => Hash::make('password'),
                'role' => 'pengusaha_rental'
            ]
        );

        // Create blacklist data
        $blacklists = [
            [
                'nik' => '3674012345670001',
                'nama_lengkap' => 'Angga Artupas',
                'jenis_kelamin' => 'L',
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta Pusat',
                'jenis_rental' => 'Mobil',
                'jenis_laporan' => ['Tidak Mengembalikan', 'Kabur'],
                'status_validitas' => 'Valid',
                'kronologi' => 'Pelanggan menyewa mobil Avanza dengan identitas palsu. Setelah 3 hari masa sewa berakhir, pelanggan tidak mengembalikan mobil dan tidak dapat dihubungi. Nomor HP yang diberikan tidak aktif.',
                'bukti' => [],
                'tanggal_kejadian' => '2024-01-15',
                'user_id' => $user1->id
            ],
            [
                'nik' => '3674012345670001', // Same NIK to test validation
                'nama_lengkap' => 'Angga Artupas',
                'jenis_kelamin' => 'L',
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta Pusat',
                'jenis_rental' => 'Motor',
                'jenis_laporan' => ['Lainnya'],
                'status_validitas' => 'Valid',
                'kronologi' => 'Pelanggan yang sama mencoba menyewa motor dengan menggunakan KTP palsu. Setelah dicek ternyata alamat tidak sesuai dan foto di KTP berbeda dengan orangnya.',
                'bukti' => [],
                'tanggal_kejadian' => '2024-02-10',
                'user_id' => $user2->id
            ],
            [
                'nik' => '3201987654321002',
                'nama_lengkap' => 'Rina Kusuma',
                'jenis_kelamin' => 'P',
                'no_hp' => '087654321098',
                'alamat' => 'Jl. Sudirman No. 456, Bandung',
                'jenis_rental' => 'Kamera',
                'jenis_laporan' => ['Merusak Barang'],
                'status_validitas' => 'Pending',
                'kronologi' => 'Pelanggan menyewa kamera DSLR untuk acara pernikahan. Setelah dikembalikan, kamera dalam kondisi rusak parah dengan lensa yang retak.',
                'bukti' => [],
                'tanggal_kejadian' => '2024-03-05',
                'user_id' => $user1->id
            ],
            [
                'nik' => '3301123456789003',
                'nama_lengkap' => 'Dedi Kurniawan',
                'jenis_kelamin' => 'L',
                'no_hp' => '089876543210',
                'alamat' => 'Jl. Diponegoro No. 789, Semarang',
                'jenis_rental' => 'Alat Elektronik',
                'jenis_laporan' => ['Lainnya'],
                'status_validitas' => 'Pending',
                'kronologi' => 'Pelanggan mencoba menyewa sound system dengan memberikan uang muka palsu (uang mainan). Untungnya staff kami teliti dan menolak transaksi tersebut.',
                'bukti' => [],
                'tanggal_kejadian' => '2024-03-20',
                'user_id' => $user2->id
            ]
        ];

        foreach ($blacklists as $blacklist) {
            RentalBlacklist::create($blacklist);
        }

        // Update status validation for NIK with multiple reports
        $this->updateValidationStatus();
    }

    private function updateValidationStatus()
    {
        $nikCounts = RentalBlacklist::selectRaw('nik, COUNT(DISTINCT user_id) as unique_users')
            ->groupBy('nik')
            ->having('unique_users', '>=', 2)
            ->get();

        foreach ($nikCounts as $nikCount) {
            RentalBlacklist::where('nik', $nikCount->nik)
                ->update(['status_validitas' => 'Valid']);
        }
    }
}
