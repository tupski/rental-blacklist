<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert footer and captcha settings
        $settings = [
            // Footer Settings
            ['key' => 'footer_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'footer', 'label' => 'Aktifkan Footer', 'description' => 'Tampilkan footer di halaman website'],
            ['key' => 'footer_description', 'value' => 'Platform terpercaya untuk mengecek dan melaporkan blacklist rental di Indonesia.', 'type' => 'textarea', 'group' => 'footer', 'label' => 'Deskripsi Footer', 'description' => 'Deskripsi yang ditampilkan di footer'],
            ['key' => 'footer_copyright', 'value' => 'Semua hak dilindungi undang-undang.', 'type' => 'text', 'group' => 'footer', 'label' => 'Teks Copyright', 'description' => 'Teks copyright di footer'],
            ['key' => 'footer_links_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'footer', 'label' => 'Aktifkan Link Footer', 'description' => 'Tampilkan menu link di footer'],

            // Social Media Links
            ['key' => 'social_facebook', 'value' => '', 'type' => 'url', 'group' => 'social', 'label' => 'Facebook URL', 'description' => 'Link halaman Facebook'],
            ['key' => 'social_twitter', 'value' => '', 'type' => 'url', 'group' => 'social', 'label' => 'Twitter URL', 'description' => 'Link halaman Twitter'],
            ['key' => 'social_instagram', 'value' => '', 'type' => 'url', 'group' => 'social', 'label' => 'Instagram URL', 'description' => 'Link halaman Instagram'],
            ['key' => 'social_whatsapp', 'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'WhatsApp Number', 'description' => 'Nomor WhatsApp (format: 628123456789)'],
            ['key' => 'social_youtube', 'value' => '', 'type' => 'url', 'group' => 'social', 'label' => 'YouTube URL', 'description' => 'Link channel YouTube'],

            // Contact Information
            ['key' => 'contact_address', 'value' => 'Indonesia', 'type' => 'textarea', 'group' => 'contact', 'label' => 'Alamat', 'description' => 'Alamat lengkap perusahaan'],
            ['key' => 'contact_phone', 'value' => '+62 123 456 789', 'type' => 'text', 'group' => 'contact', 'label' => 'Nomor Telepon', 'description' => 'Nomor telepon yang bisa dihubungi'],
            ['key' => 'contact_email', 'value' => 'info@rentalblacklist.com', 'type' => 'email', 'group' => 'contact', 'label' => 'Email Kontak', 'description' => 'Email untuk kontak umum'],
            ['key' => 'contact_hours', 'value' => 'Senin - Jumat: 09:00 - 17:00 WIB', 'type' => 'text', 'group' => 'contact', 'label' => 'Jam Operasional', 'description' => 'Jam operasional layanan'],

            // Captcha Settings
            ['key' => 'captcha_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'captcha', 'label' => 'Aktifkan Captcha', 'description' => 'Aktifkan sistem captcha untuk keamanan'],
            ['key' => 'captcha_type', 'value' => 'recaptcha', 'type' => 'select', 'group' => 'captcha', 'label' => 'Jenis Captcha', 'description' => 'Pilih jenis captcha yang digunakan'],
            ['key' => 'recaptcha_site_key', 'value' => '', 'type' => 'text', 'group' => 'captcha', 'label' => 'reCAPTCHA Site Key', 'description' => 'Site key dari Google reCAPTCHA'],
            ['key' => 'recaptcha_secret_key', 'value' => '', 'type' => 'text', 'group' => 'captcha', 'label' => 'reCAPTCHA Secret Key', 'description' => 'Secret key dari Google reCAPTCHA'],
            ['key' => 'hcaptcha_site_key', 'value' => '', 'type' => 'text', 'group' => 'captcha', 'label' => 'hCaptcha Site Key', 'description' => 'Site key dari hCaptcha'],
            ['key' => 'hcaptcha_secret_key', 'value' => '', 'type' => 'text', 'group' => 'captcha', 'label' => 'hCaptcha Secret Key', 'description' => 'Secret key dari hCaptcha'],

            // Captcha Form Settings
            ['key' => 'captcha_login', 'value' => '1', 'type' => 'boolean', 'group' => 'captcha', 'label' => 'Captcha Login', 'description' => 'Aktifkan captcha di form login'],
            ['key' => 'captcha_register', 'value' => '1', 'type' => 'boolean', 'group' => 'captcha', 'label' => 'Captcha Register', 'description' => 'Aktifkan captcha di form registrasi'],
            ['key' => 'captcha_report', 'value' => '1', 'type' => 'boolean', 'group' => 'captcha', 'label' => 'Captcha Laporan', 'description' => 'Aktifkan captcha di form laporan'],
            ['key' => 'captcha_contact', 'value' => '1', 'type' => 'boolean', 'group' => 'captcha', 'label' => 'Captcha Kontak', 'description' => 'Aktifkan captcha di form kontak'],
            ['key' => 'captcha_reset_password', 'value' => '1', 'type' => 'boolean', 'group' => 'captcha', 'label' => 'Captcha Reset Password', 'description' => 'Aktifkan captcha di form reset password'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'group' => $setting['group'],
                    'label' => $setting['label'],
                    'description' => $setting['description'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $keys = [
            'footer_enabled', 'footer_description', 'footer_copyright', 'footer_links_enabled',
            'social_facebook', 'social_twitter', 'social_instagram', 'social_whatsapp', 'social_youtube',
            'contact_address', 'contact_phone', 'contact_email', 'contact_hours',
            'captcha_enabled', 'captcha_type', 'recaptcha_site_key', 'recaptcha_secret_key',
            'hcaptcha_site_key', 'hcaptcha_secret_key',
            'captcha_login', 'captcha_register', 'captcha_report', 'captcha_contact', 'captcha_reset_password'
        ];

        DB::table('settings')->whereIn('key', $keys)->delete();
    }
};
