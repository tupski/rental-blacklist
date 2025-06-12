<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'site_name' => 'RentalGuard',
            'site_tagline' => 'Sistem Blacklist Rental Indonesia',
            'hero_title' => 'Lindungi Bisnis Rental Anda',
            'hero_subtitle' => 'Cek data blacklist pelanggan sebelum menyewakan barang. 100% Gratis untuk pengusaha rental!',
            'meta_title' => 'RentalGuard - Sistem Blacklist Rental Indonesia',
            'meta_description' => 'Sistem blacklist rental terpercaya di Indonesia. Cek data pelanggan bermasalah sebelum menyewakan barang Anda. Gratis untuk pengusaha rental.',
            'meta_keywords' => 'blacklist rental, rental indonesia, cek pelanggan rental, sistem blacklist, rental bermasalah',
            'contact_email' => 'support@rentalguard.id',
            'contact_phone' => '+62 21 1234 5678',
            'facebook_url' => '',
            'twitter_url' => '',
            'instagram_url' => '',
            'whatsapp_number' => '',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
