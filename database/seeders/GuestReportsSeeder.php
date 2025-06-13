<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GuestReport;
use Carbon\Carbon;

class GuestReportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisRental = ['Rental Mobil', 'Rental Motor', 'Rental Kamera', 'Rental Alat Musik', 'Rental Elektronik'];
        $jenisLaporan = [
            ['Tidak mengembalikan barang'],
            ['Merusak barang rental'],
            ['Tidak membayar'],
            ['Kabur dengan barang'],
            ['Tidak mengembalikan barang', 'Tidak membayar'],
            ['Merusak barang rental', 'Tidak membayar'],
        ];

        $namaLengkap = [
            'Andi Wijaya', 'Budi Setiawan', 'Citra Lestari', 'Doni Pratama', 'Eka Sari',
            'Farid Rahman', 'Gina Handayani', 'Hadi Kurniawan', 'Ika Permata', 'Jaka Susilo'
        ];

        $alamatList = [
            'Jl. Sudirman No. 123, Jakarta',
            'Jl. Thamrin No. 456, Jakarta',
            'Jl. Gatot Subroto No. 789, Jakarta',
            'Jl. Kuningan No. 321, Jakarta',
            'Jl. Senayan No. 654, Jakarta'
        ];

        $namaPelapor = [
            'PT. Rental Jaya Abadi',
            'CV. Sewa Berkah',
            'Rental Mandiri',
            'PT. Persada Rental',
            'Toko Rental Sejahtera'
        ];

        $kronologiTemplates = [
            'Pelanggan menyewa {jenis} pada tanggal {tanggal_sewa} namun tidak mengembalikan sesuai waktu yang disepakati. Sudah dilakukan komunikasi berkali-kali namun tidak ada respon.',
            'Barang {jenis} yang disewa dikembalikan dalam kondisi rusak berat. Pelanggan menolak bertanggung jawab atas kerusakan yang terjadi.',
            'Pelanggan telah menggunakan layanan rental namun tidak melakukan pembayaran sesuai kesepakatan. Berbagai upaya penagihan telah dilakukan.',
            'Setelah masa sewa berakhir, pelanggan menghilang bersama barang rental dan tidak dapat dihubungi melalui kontak yang diberikan.'
        ];

        for ($i = 0; $i < 15; $i++) {
            $tanggalKejadian = Carbon::now()->subDays(rand(1, 180));
            $jenisRentalSelected = $jenisRental[array_rand($jenisRental)];
            $jenisLaporanSelected = $jenisLaporan[array_rand($jenisLaporan)];
            $kronologiTemplate = $kronologiTemplates[array_rand($kronologiTemplates)];

            // Generate NIK
            $nik = '31' . str_pad(rand(1, 99), 2, '0', STR_PAD_LEFT) .
                   str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) .
                   str_pad(rand(1, 31), 2, '0', STR_PAD_LEFT) .
                   str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) .
                   str_pad(rand(1, 99), 2, '0', STR_PAD_LEFT);

            // Generate phone numbers
            $noHp = '08' . rand(1, 9) . str_pad(rand(1, 99999999), 8, '0', STR_PAD_LEFT);
            $noHpPelapor = '08' . rand(1, 9) . str_pad(rand(1, 99999999), 8, '0', STR_PAD_LEFT);

            $kronologi = str_replace(
                ['{jenis}', '{tanggal_sewa}'],
                [strtolower($jenisRentalSelected), $tanggalKejadian->format('d/m/Y')],
                $kronologiTemplate
            );

            // Determine status - 40% pending, 40% approved, 20% rejected
            $rand = rand(1, 100);
            if ($rand <= 40) {
                $status = 'pending';
            } elseif ($rand <= 80) {
                $status = 'approved';
            } else {
                $status = 'rejected';
            }

            GuestReport::create([
                'nik' => $nik,
                'nama_lengkap' => $namaLengkap[$i % count($namaLengkap)],
                'jenis_kelamin' => rand(0, 1) ? 'Laki-laki' : 'Perempuan',
                'no_hp' => $noHp,
                'alamat' => $alamatList[array_rand($alamatList)],
                'jenis_rental' => $jenisRentalSelected,
                'jenis_laporan' => $jenisLaporanSelected,
                'kronologi' => $kronologi,
                'bukti' => $this->generateDummyBukti(),
                'tanggal_kejadian' => $tanggalKejadian,
                'email_pelapor' => strtolower(str_replace([' ', '.'], ['', ''], $namaPelapor[$i % count($namaPelapor)])) . '@example.com',
                'nama_pelapor' => $namaPelapor[$i % count($namaPelapor)],
                'no_hp_pelapor' => $noHpPelapor,
                'status' => $status,
                'catatan_admin' => $status === 'rejected' ? 'Data tidak lengkap atau tidak valid' : null,
                'created_at' => $tanggalKejadian->addDays(rand(1, 5)),
                'updated_at' => now(),
            ]);
        }
    }

    private function generateDummyBukti()
    {
        $buktiTypes = [
            'foto_ktp_pelanggan.jpg',
            'foto_barang_rusak.jpg',
            'screenshot_percakapan.jpg',
            'nota_rental.pdf',
            'foto_identitas_pelanggan.jpg'
        ];

        $buktiCount = rand(1, 3);
        $bukti = [];

        for ($i = 0; $i < $buktiCount; $i++) {
            $bukti[] = 'dummy_bukti_guest/' . $buktiTypes[array_rand($buktiTypes)];
        }

        return $bukti;
    }
}
