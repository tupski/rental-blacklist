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
                'description' => 'Tagline atau slogan situs'
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
            [
                'key' => 'whatsapp_number',
                'value' => '6281234567890',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Nomor WhatsApp',
                'description' => 'Nomor WhatsApp untuk kontak (format: 6281234567890)'
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

            // SMTP Settings
            [
                'key' => 'smtp_host',
                'value' => 'smtp.gmail.com',
                'type' => 'text',
                'group' => 'smtp',
                'label' => 'SMTP Host',
                'description' => 'Host server SMTP untuk pengiriman email'
            ],
            [
                'key' => 'smtp_port',
                'value' => '587',
                'type' => 'number',
                'group' => 'smtp',
                'label' => 'SMTP Port',
                'description' => 'Port server SMTP (biasanya 587 atau 465)'
            ],
            [
                'key' => 'smtp_username',
                'value' => '',
                'type' => 'text',
                'group' => 'smtp',
                'label' => 'SMTP Username',
                'description' => 'Username untuk autentikasi SMTP'
            ],
            [
                'key' => 'smtp_password',
                'value' => '',
                'type' => 'password',
                'group' => 'smtp',
                'label' => 'SMTP Password',
                'description' => 'Password untuk autentikasi SMTP'
            ],
            [
                'key' => 'smtp_encryption',
                'value' => 'tls',
                'type' => 'select',
                'group' => 'smtp',
                'label' => 'SMTP Encryption',
                'description' => 'Jenis enkripsi SMTP (tls/ssl)'
            ],
            [
                'key' => 'mail_from_address',
                'value' => 'noreply@rentalguard.id',
                'type' => 'email',
                'group' => 'smtp',
                'label' => 'Email Pengirim',
                'description' => 'Email yang akan muncul sebagai pengirim'
            ],
            [
                'key' => 'mail_from_name',
                'value' => 'RentalGuard',
                'type' => 'text',
                'group' => 'smtp',
                'label' => 'Nama Pengirim',
                'description' => 'Nama yang akan muncul sebagai pengirim email'
            ],

            // Payment Gateway Settings
            [
                'key' => 'midtrans_server_key',
                'value' => '',
                'type' => 'password',
                'group' => 'payment',
                'label' => 'Midtrans Server Key',
                'description' => 'Server Key dari Midtrans untuk payment gateway'
            ],
            [
                'key' => 'midtrans_client_key',
                'value' => '',
                'type' => 'text',
                'group' => 'payment',
                'label' => 'Midtrans Client Key',
                'description' => 'Client Key dari Midtrans'
            ],
            [
                'key' => 'midtrans_is_production',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'payment',
                'label' => 'Midtrans Production Mode',
                'description' => 'Aktifkan untuk menggunakan mode production'
            ],
            [
                'key' => 'xendit_secret_key',
                'value' => '',
                'type' => 'password',
                'group' => 'payment',
                'label' => 'Xendit Secret Key',
                'description' => 'Secret Key dari Xendit untuk payment gateway'
            ],
            [
                'key' => 'xendit_public_key',
                'value' => '',
                'type' => 'text',
                'group' => 'payment',
                'label' => 'Xendit Public Key',
                'description' => 'Public Key dari Xendit'
            ],
            [
                'key' => 'payment_methods',
                'value' => json_encode(['bank_transfer', 'e_wallet', 'credit_card']),
                'type' => 'json',
                'group' => 'payment',
                'label' => 'Metode Pembayaran',
                'description' => 'Metode pembayaran yang tersedia'
            ],

            // Pricing Settings
            [
                'key' => 'price_rental_mobil',
                'value' => '1500',
                'type' => 'number',
                'group' => 'pricing',
                'label' => 'Harga Rental Mobil',
                'description' => 'Harga per detail untuk rental mobil (Rupiah)'
            ],
            [
                'key' => 'price_rental_motor',
                'value' => '1500',
                'type' => 'number',
                'group' => 'pricing',
                'label' => 'Harga Rental Motor',
                'description' => 'Harga per detail untuk rental motor (Rupiah)'
            ],
            [
                'key' => 'price_kamera',
                'value' => '1000',
                'type' => 'number',
                'group' => 'pricing',
                'label' => 'Harga Kamera',
                'description' => 'Harga per detail untuk kamera (Rupiah)'
            ],
            [
                'key' => 'price_lainnya',
                'value' => '800',
                'type' => 'number',
                'group' => 'pricing',
                'label' => 'Harga Lainnya',
                'description' => 'Harga per detail untuk kategori lainnya (Rupiah)'
            ],

            // System Settings
            [
                'key' => 'timezone',
                'value' => 'Asia/Jakarta',
                'type' => 'select',
                'group' => 'system',
                'label' => 'Zona Waktu',
                'description' => 'Zona waktu sistem'
            ],
            [
                'key' => 'date_format',
                'value' => 'd/m/Y',
                'type' => 'select',
                'group' => 'system',
                'label' => 'Format Tanggal',
                'description' => 'Format tampilan tanggal'
            ],
            [
                'key' => 'time_format',
                'value' => 'H:i',
                'type' => 'select',
                'group' => 'system',
                'label' => 'Format Waktu',
                'description' => 'Format tampilan waktu'
            ],
            [
                'key' => 'currency_symbol',
                'value' => 'Rp',
                'type' => 'text',
                'group' => 'system',
                'label' => 'Simbol Mata Uang',
                'description' => 'Simbol mata uang yang digunakan'
            ],
            [
                'key' => 'currency_position',
                'value' => 'before',
                'type' => 'select',
                'group' => 'system',
                'label' => 'Posisi Mata Uang',
                'description' => 'Posisi simbol mata uang (before/after)'
            ],
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'system',
                'label' => 'Mode Maintenance',
                'description' => 'Aktifkan mode maintenance untuk sistem'
            ],
            [
                'key' => 'maintenance_message',
                'value' => 'Sistem sedang dalam pemeliharaan. Silakan coba lagi nanti.',
                'type' => 'textarea',
                'group' => 'system',
                'label' => 'Pesan Maintenance',
                'description' => 'Pesan yang ditampilkan saat mode maintenance aktif'
            ],

            // Payment Instructions
            [
                'key' => 'bank_bca_number',
                'value' => '6050381330',
                'type' => 'text',
                'group' => 'payment',
                'label' => 'Nomor Rekening BCA',
                'description' => 'Nomor rekening BCA untuk transfer manual'
            ],
            [
                'key' => 'bank_bca_name',
                'value' => 'ANGGA DWY SAPUTRA',
                'type' => 'text',
                'group' => 'payment',
                'label' => 'Nama Pemilik Rekening BCA',
                'description' => 'Nama pemilik rekening BCA'
            ],
            [
                'key' => 'bank_bjb_number',
                'value' => '12345869594939',
                'type' => 'text',
                'group' => 'payment',
                'label' => 'Nomor Rekening BJB',
                'description' => 'Nomor rekening BJB untuk transfer manual'
            ],
            [
                'key' => 'bank_bjb_name',
                'value' => 'ANGGA DWY SAPUTRA',
                'type' => 'text',
                'group' => 'payment',
                'label' => 'Nama Pemilik Rekening BJB',
                'description' => 'Nama pemilik rekening BJB'
            ],
            [
                'key' => 'bank_bri_number',
                'value' => '208319382834',
                'type' => 'text',
                'group' => 'payment',
                'label' => 'Nomor Rekening BRI',
                'description' => 'Nomor rekening BRI untuk transfer manual'
            ],
            [
                'key' => 'bank_bri_name',
                'value' => 'ANGGA DWY SAPUTRA',
                'type' => 'text',
                'group' => 'payment',
                'label' => 'Nama Pemilik Rekening BRI',
                'description' => 'Nama pemilik rekening BRI'
            ],
            [
                'key' => 'gopay_number',
                'value' => '0819-1191-9993',
                'type' => 'text',
                'group' => 'payment',
                'label' => 'Nomor GoPay',
                'description' => 'Nomor GoPay untuk pembayaran digital'
            ],
            [
                'key' => 'dana_number',
                'value' => '0819-1191-9993',
                'type' => 'text',
                'group' => 'payment',
                'label' => 'Nomor DANA',
                'description' => 'Nomor DANA untuk pembayaran digital'
            ],
            [
                'key' => 'ovo_number',
                'value' => '0822-1121-9993',
                'type' => 'text',
                'group' => 'payment',
                'label' => 'Nomor OVO',
                'description' => 'Nomor OVO untuk pembayaran digital'
            ],
            [
                'key' => 'payment_instruction',
                'value' => 'Silakan transfer sesuai nominal yang tertera ke salah satu rekening di atas. Setelah transfer, kirim bukti transfer melalui WhatsApp untuk konfirmasi.',
                'type' => 'textarea',
                'group' => 'payment',
                'label' => 'Instruksi Pembayaran',
                'description' => 'Instruksi pembayaran untuk transfer manual'
            ],
            [
                'key' => 'auto_payment_enabled',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'payment',
                'label' => 'Pembayaran Otomatis',
                'description' => 'Aktifkan pembayaran otomatis melalui payment gateway'
            ],
            [
                'key' => 'midtrans_server_key',
                'value' => '',
                'type' => 'password',
                'group' => 'payment',
                'label' => 'Midtrans Server Key',
                'description' => 'Server key untuk integrasi Midtrans'
            ],
            [
                'key' => 'midtrans_client_key',
                'value' => '',
                'type' => 'text',
                'group' => 'payment',
                'label' => 'Midtrans Client Key',
                'description' => 'Client key untuk integrasi Midtrans'
            ],
            [
                'key' => 'xendit_secret_key',
                'value' => '',
                'type' => 'password',
                'group' => 'payment',
                'label' => 'Xendit Secret Key',
                'description' => 'Secret key untuk integrasi Xendit'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
