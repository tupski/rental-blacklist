<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FooterWidget;

class FooterWidgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing widgets
        FooterWidget::truncate();

        // Widget 1: Layanan
        FooterWidget::create([
            'title' => 'Layanan',
            'content' => null,
            'type' => 'links',
            'data' => [
                'links' => [
                    ['text' => 'Cek Blacklist', 'url' => url('/')],
                    ['text' => 'Pendaftaran Rental', 'url' => url('/daftar')],
                    ['text' => 'Lapor Masalah', 'url' => url('/laporan/buat')],
                    ['text' => 'API Access', 'url' => url('/api/dokumentasi')],
                ]
            ],
            'order' => 1,
            'is_active' => true,
            'css_class' => null
        ]);

        // Widget 2: Bantuan
        FooterWidget::create([
            'title' => 'Bantuan',
            'content' => null,
            'type' => 'links',
            'data' => [
                'links' => [
                    ['text' => 'FAQ', 'url' => '#'],
                    ['text' => 'Kontak', 'url' => '#'],
                    ['text' => 'Kebijakan Privasi', 'url' => url('/kebijakan-privasi')],
                    ['text' => 'Syarat & Ketentuan', 'url' => url('/syarat-ketentuan')],
                ]
            ],
            'order' => 2,
            'is_active' => true,
            'css_class' => null
        ]);

        // Widget 3: Kontak
        FooterWidget::create([
            'title' => 'Kontak',
            'content' => null,
            'type' => 'contact',
            'data' => [
                'address' => 'Jl. Teknologi No. 123, Jakarta Selatan, DKI Jakarta 12345',
                'phone' => '+62 21 1234 5678',
                'email' => 'support@cekpenyewa.com',
                'whatsapp' => '+62 812 3456 7890'
            ],
            'order' => 3,
            'is_active' => true,
            'css_class' => null
        ]);

        // Widget 4: Media Sosial
        FooterWidget::create([
            'title' => 'Ikuti Kami',
            'content' => null,
            'type' => 'social',
            'data' => [
                'social' => [
                    ['platform' => 'facebook', 'url' => 'https://facebook.com/cekpenyewa'],
                    ['platform' => 'twitter', 'url' => 'https://twitter.com/cekpenyewa'],
                    ['platform' => 'instagram', 'url' => 'https://instagram.com/cekpenyewa'],
                    ['platform' => 'whatsapp', 'url' => 'https://wa.me/6281234567890'],
                ]
            ],
            'order' => 4,
            'is_active' => true,
            'css_class' => null
        ]);

        $this->command->info('Footer widget seeder completed successfully!');
    }
}
