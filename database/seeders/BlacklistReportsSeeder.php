<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RentalBlacklist;
use App\Models\User;
use Carbon\Carbon;

class BlacklistReportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', '!=', 'admin')->pluck('id')->toArray();

        $jenisRental = ['Rental Mobil', 'Rental Motor', 'Rental Kamera', 'Rental Alat Musik', 'Rental Elektronik'];
        $jenisLaporan = [
            ['Tidak mengembalikan barang'],
            ['Merusak barang rental'],
            ['Tidak membayar'],
            ['Kabur dengan barang'],
            ['Tidak mengembalikan barang', 'Tidak membayar'],
            ['Merusak barang rental', 'Tidak membayar'],
            ['Kabur dengan barang', 'Merusak barang rental'],
        ];

        $namaLengkap = [
            'Agus Setiawan', 'Bambang Wijaya', 'Citra Dewi', 'Dedi Kurniawan', 'Eka Pratama',
            'Fajar Nugroho', 'Gita Sari', 'Hendra Gunawan', 'Indah Permata', 'Joko Susilo',
            'Kartika Sari', 'Lukman Hakim', 'Maya Sari', 'Nanda Pratama', 'Oki Setiawan',
            'Putri Handayani', 'Qori Rahman', 'Rina Wati', 'Sandi Wijaya', 'Tina Marlina',
            'Udin Setiawan', 'Vina Sari', 'Wawan Kurniawan', 'Xenia Putri', 'Yudi Pratama',
            'Zaki Rahman', 'Andi Saputra', 'Budi Hartono', 'Cici Lestari', 'Doni Setiawan'
        ];

        $alamatList = [
            'Jl. Kebon Jeruk No. 12, Jakarta Barat',
            'Jl. Cikini Raya No. 45, Jakarta Pusat',
            'Jl. Kemang Selatan No. 78, Jakarta Selatan',
            'Jl. Kelapa Gading No. 23, Jakarta Utara',
            'Jl. Rawamangun No. 56, Jakarta Timur',
            'Jl. Dago No. 89, Bandung',
            'Jl. Braga No. 34, Bandung',
            'Jl. Malioboro No. 67, Yogyakarta',
            'Jl. Tugu No. 12, Yogyakarta',
            'Jl. Pemuda No. 45, Semarang'
        ];

        $kronologiTemplates = [
            'Pelanggan menyewa {jenis} pada tanggal {tanggal_sewa} dengan jangka waktu 3 hari. Namun hingga batas waktu pengembalian, pelanggan tidak mengembalikan barang dan tidak dapat dihubungi melalui nomor telepon yang diberikan.',
            'Pada tanggal {tanggal_sewa}, pelanggan menyewa {jenis} dalam kondisi baik. Saat pengembalian, barang ditemukan dalam kondisi rusak parah dengan kerusakan pada bagian {bagian_rusak}. Pelanggan menolak bertanggung jawab atas kerusakan.',
            'Pelanggan telah menyewa {jenis} dan menggunakan selama periode yang disepakati. Namun saat jatuh tempo pembayaran, pelanggan tidak melakukan pembayaran dan menghindari komunikasi dengan pihak rental.',
            'Setelah menyewa {jenis}, pelanggan menghilang bersama barang rental tanpa memberikan kabar. Upaya pencarian dan komunikasi telah dilakukan namun tidak membuahkan hasil.',
            'Pelanggan menyewa {jenis} dengan pembayaran sistem DP. Setelah masa sewa berakhir, pelanggan tidak mengembalikan barang dan tidak melunasi sisa pembayaran yang telah disepakati.'
        ];

        for ($i = 0; $i < 30; $i++) {
            $tanggalKejadian = Carbon::now()->subDays(rand(1, 365));
            $jenisRentalSelected = $jenisRental[array_rand($jenisRental)];
            $jenisLaporanSelected = $jenisLaporan[array_rand($jenisLaporan)];
            $kronologiTemplate = $kronologiTemplates[array_rand($kronologiTemplates)];

            // Generate NIK
            $nik = '32' . str_pad(rand(1, 99), 2, '0', STR_PAD_LEFT) .
                   str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) .
                   str_pad(rand(1, 31), 2, '0', STR_PAD_LEFT) .
                   str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) .
                   str_pad(rand(1, 99), 2, '0', STR_PAD_LEFT);

            // Generate phone number
            $noHp = '08' . rand(1, 9) . str_pad(rand(1, 99999999), 8, '0', STR_PAD_LEFT);

            $kronologi = str_replace(
                ['{jenis}', '{tanggal_sewa}', '{bagian_rusak}'],
                [strtolower($jenisRentalSelected), $tanggalKejadian->format('d/m/Y'), 'mesin/body/interior'],
                $kronologiTemplate
            );

            // Determine status - 60% valid, 30% pending, 10% invalid
            $rand = rand(1, 100);
            if ($rand <= 60) {
                $status = 'Valid';
            } elseif ($rand <= 90) {
                $status = 'Pending';
            } else {
                $status = 'Invalid';
            }

            RentalBlacklist::create([
                'nik' => $nik,
                'nama_lengkap' => $namaLengkap[$i % count($namaLengkap)],
                'jenis_kelamin' => rand(0, 1) ? 'L' : 'P',
                'no_hp' => $noHp,
                'alamat' => $alamatList[array_rand($alamatList)],
                'jenis_rental' => $jenisRentalSelected,
                'jenis_laporan' => $jenisLaporanSelected,
                'status_validitas' => $status,
                'kronologi' => $kronologi,
                'bukti' => $this->generateDummyBukti(),
                'tanggal_kejadian' => $tanggalKejadian,
                'user_id' => $users[array_rand($users)],
                'created_at' => $tanggalKejadian->addDays(rand(1, 7)),
                'updated_at' => now(),
            ]);
        }
    }

    private function generateDummyBukti()
    {
        $buktiTypes = [
            'foto_ktp.jpg',
            'foto_barang_rusak.jpg',
            'screenshot_chat.jpg',
            'nota_sewa.pdf',
            'foto_identitas.jpg'
        ];

        $buktiCount = rand(1, 3);
        $bukti = [];

        for ($i = 0; $i < $buktiCount; $i++) {
            $bukti[] = 'dummy_bukti/' . $buktiTypes[array_rand($buktiTypes)];
        }

        return $bukti;
    }
}
