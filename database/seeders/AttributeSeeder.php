<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attribute;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Jenis Rental
        $jenisRental = [
            ['name' => 'Rental Mobil', 'value' => 'rental_mobil', 'order' => 1],
            ['name' => 'Rental Motor', 'value' => 'rental_motor', 'order' => 2],
            ['name' => 'Rental Kamera', 'value' => 'rental_kamera', 'order' => 3],
            ['name' => 'Rental Alat Musik', 'value' => 'rental_alat_musik', 'order' => 4],
            ['name' => 'Rental Elektronik', 'value' => 'rental_elektronik', 'order' => 5],
            ['name' => 'Rental Alat Berat', 'value' => 'rental_alat_berat', 'order' => 6],
            ['name' => 'Lainnya', 'value' => 'lainnya', 'order' => 7],
        ];

        foreach ($jenisRental as $item) {
            Attribute::updateOrCreate(
                ['type' => 'jenis_rental', 'value' => $item['value']],
                [
                    'name' => $item['name'],
                    'description' => 'Kategori jenis rental: ' . $item['name'],
                    'order' => $item['order'],
                    'is_active' => true,
                    'is_default' => false,
                ]
            );
        }

        // Kategori Masalah
        $kategoriMasalah = [
            ['name' => 'Tidak Mengembalikan', 'value' => 'tidak_mengembalikan', 'order' => 1],
            ['name' => 'Merusak Barang', 'value' => 'merusak_barang', 'order' => 2],
            ['name' => 'Tidak Bayar', 'value' => 'tidak_bayar', 'order' => 3],
            ['name' => 'Kabur', 'value' => 'kabur', 'order' => 4],
            ['name' => 'Telat Bayar', 'value' => 'telat_bayar', 'order' => 5],
            ['name' => 'Tidak Sesuai Perjanjian', 'value' => 'tidak_sesuai_perjanjian', 'order' => 6],
            ['name' => 'Lainnya', 'value' => 'lainnya', 'order' => 7],
        ];

        foreach ($kategoriMasalah as $item) {
            Attribute::updateOrCreate(
                ['type' => 'kategori_masalah', 'value' => $item['value']],
                [
                    'name' => $item['name'],
                    'description' => 'Jenis masalah: ' . $item['name'],
                    'order' => $item['order'],
                    'is_active' => true,
                    'is_default' => false,
                ]
            );
        }

        // Status Penanganan
        $statusPenanganan = [
            ['name' => 'Sudah Dilaporkan ke Polisi', 'value' => 'dilaporkan_polisi', 'order' => 1],
            ['name' => 'Sudah Dicoba Dihubungi tapi Tidak Ada Respon', 'value' => 'tidak_ada_respon', 'order' => 2],
            ['name' => 'Masih Proses Penyelesaian', 'value' => 'proses_penyelesaian', 'order' => 3],
            ['name' => 'Sudah Diselesaikan', 'value' => 'sudah_diselesaikan', 'order' => 4],
            ['name' => 'Lainnya', 'value' => 'lainnya', 'order' => 5],
        ];

        foreach ($statusPenanganan as $item) {
            Attribute::updateOrCreate(
                ['type' => 'status_penanganan', 'value' => $item['value']],
                [
                    'name' => $item['name'],
                    'description' => 'Status penanganan: ' . $item['name'],
                    'order' => $item['order'],
                    'is_active' => true,
                    'is_default' => false,
                ]
            );
        }

        // Jenis Kendaraan
        $jenisKendaraan = [
            ['name' => 'Mobil', 'value' => 'mobil', 'order' => 1],
            ['name' => 'Motor', 'value' => 'motor', 'order' => 2],
            ['name' => 'Sepeda', 'value' => 'sepeda', 'order' => 3],
            ['name' => 'Truk', 'value' => 'truk', 'order' => 4],
            ['name' => 'Bus', 'value' => 'bus', 'order' => 5],
            ['name' => 'Lainnya', 'value' => 'lainnya', 'order' => 6],
        ];

        foreach ($jenisKendaraan as $item) {
            Attribute::updateOrCreate(
                ['type' => 'jenis_kendaraan', 'value' => $item['value']],
                [
                    'name' => $item['name'],
                    'description' => 'Jenis kendaraan: ' . $item['name'],
                    'order' => $item['order'],
                    'is_active' => true,
                    'is_default' => false,
                ]
            );
        }

        // Merk Kendaraan
        $merkKendaraan = [
            ['name' => 'Toyota', 'value' => 'toyota', 'order' => 1],
            ['name' => 'Honda', 'value' => 'honda', 'order' => 2],
            ['name' => 'Suzuki', 'value' => 'suzuki', 'order' => 3],
            ['name' => 'Yamaha', 'value' => 'yamaha', 'order' => 4],
            ['name' => 'Kawasaki', 'value' => 'kawasaki', 'order' => 5],
            ['name' => 'Mitsubishi', 'value' => 'mitsubishi', 'order' => 6],
            ['name' => 'Daihatsu', 'value' => 'daihatsu', 'order' => 7],
            ['name' => 'Nissan', 'value' => 'nissan', 'order' => 8],
            ['name' => 'Lainnya', 'value' => 'lainnya', 'order' => 9],
        ];

        foreach ($merkKendaraan as $item) {
            Attribute::updateOrCreate(
                ['type' => 'merk_kendaraan', 'value' => $item['value']],
                [
                    'name' => $item['name'],
                    'description' => 'Merk kendaraan: ' . $item['name'],
                    'order' => $item['order'],
                    'is_active' => true,
                    'is_default' => false,
                ]
            );
        }

        $this->command->info('Attribute seeder completed successfully!');
    }
}
