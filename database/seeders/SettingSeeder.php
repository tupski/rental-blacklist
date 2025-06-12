<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'site_name',
                'value' => 'RentalGuard',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Nama Situs',
                'description' => 'Nama situs yang akan ditampilkan di header dan title'
            ],
            [
                'key' => 'site_tagline',
                'value' => 'Sistem Blacklist Rental Indonesia',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Tagline Situs',
                'description' => 'Tagline yang ditampilkan di bawah nama situs'
            ],
            [
                'key' => 'contact_email',
                'value' => 'support@rentalguard.id',
                'type' => 'email',
                'group' => 'general',
                'label' => 'Email Kontak',
                'description' => 'Email untuk kontak dan support'
            ],
            [
                'key' => 'contact_phone',
                'value' => '+62 21 1234 5678',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Nomor Telepon',
                'description' => 'Nomor telepon untuk kontak'
            ],

            // SEO Settings
            [
                'key' => 'meta_title',
                'value' => 'RentalGuard - Sistem Blacklist Rental Indonesia',
                'type' => 'text',
                'group' => 'seo',
                'label' => 'Meta Title',
                'description' => 'Title yang akan muncul di search engine'
            ],
            [
                'key' => 'meta_description',
                'value' => 'Sistem blacklist rental terpercaya di Indonesia. Cek data pelanggan bermasalah sebelum menyewakan barang Anda. Gratis untuk pengusaha rental.',
                'type' => 'textarea',
                'group' => 'seo',
                'label' => 'Meta Description',
                'description' => 'Deskripsi yang akan muncul di search engine'
            ],
            [
                'key' => 'meta_keywords',
                'value' => 'blacklist rental, rental indonesia, cek pelanggan rental, sistem blacklist, rental bermasalah',
                'type' => 'textarea',
                'group' => 'seo',
                'label' => 'Meta Keywords',
                'description' => 'Keywords untuk SEO (pisahkan dengan koma)'
            ],

            // Appearance Settings
            [
                'key' => 'hero_title',
                'value' => 'Lindungi Bisnis Rental Anda',
                'type' => 'text',
                'group' => 'appearance',
                'label' => 'Judul Hero',
                'description' => 'Judul utama di halaman depan'
            ],
            [
                'key' => 'hero_subtitle',
                'value' => 'Cek data blacklist pelanggan sebelum menyewakan barang. 100% Gratis untuk pengusaha rental!',
                'type' => 'textarea',
                'group' => 'appearance',
                'label' => 'Subjudul Hero',
                'description' => 'Subjudul di halaman depan'
            ],
            [
                'key' => 'primary_color',
                'value' => '#dc2626',
                'type' => 'color',
                'group' => 'appearance',
                'label' => 'Warna Utama',
                'description' => 'Warna utama untuk tema situs'
            ],

            // Social Media
            [
                'key' => 'facebook_url',
                'value' => '',
                'type' => 'url',
                'group' => 'social',
                'label' => 'URL Facebook',
                'description' => 'Link ke halaman Facebook'
            ],
            [
                'key' => 'twitter_url',
                'value' => '',
                'type' => 'url',
                'group' => 'social',
                'label' => 'URL Twitter',
                'description' => 'Link ke halaman Twitter'
            ],
            [
                'key' => 'instagram_url',
                'value' => '',
                'type' => 'url',
                'group' => 'social',
                'label' => 'URL Instagram',
                'description' => 'Link ke halaman Instagram'
            ],
            [
                'key' => 'whatsapp_number',
                'value' => '',
                'type' => 'text',
                'group' => 'social',
                'label' => 'Nomor WhatsApp',
                'description' => 'Nomor WhatsApp untuk kontak (format: 628123456789)'
            ]
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
