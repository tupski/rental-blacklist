<?php

namespace Database\Seeders;

use App\Models\ChatbotKnowledgeBase;
use Illuminate\Database\Seeder;

class ChatbotKnowledgeBaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $knowledgeBase = [
            // Platform Overview
            [
                'category' => 'platform',
                'title' => 'Tentang CekPenyewa.com',
                'content' => 'CekPenyewa.com adalah platform sistem blacklist rental Indonesia yang membantu pengusaha rental melindungi bisnis mereka dari pelanggan bermasalah. Platform ini menyediakan database terpusat untuk berbagi informasi tentang penyewa yang pernah bermasalah.',
                'keywords' => ['cekpenyewa', 'blacklist', 'rental', 'indonesia', 'platform', 'tentang'],
                'related_routes' => ['/'],
                'priority' => 1,
            ],
            [
                'category' => 'features',
                'title' => 'Fitur Utama Platform',
                'content' => 'Fitur utama CekPenyewa.com meliputi: 1) Pencarian data blacklist gratis untuk pengusaha rental, 2) Laporan pelanggan bermasalah dengan detail lengkap, 3) Sistem verifikasi data, 4) Dashboard khusus untuk rental owner, 5) Topup saldo untuk user reguler, 6) Sistem sponsor untuk promosi bisnis rental.',
                'keywords' => ['fitur', 'pencarian', 'laporan', 'dashboard', 'topup', 'sponsor'],
                'related_routes' => ['/dasbor', '/rental/dasbor'],
                'priority' => 1,
            ],

            // User Types
            [
                'category' => 'users',
                'title' => 'Jenis Pengguna',
                'content' => 'Ada 3 jenis pengguna di CekPenyewa.com: 1) User Reguler - dapat mencari data dengan membayar per pencarian, 2) Rental Owner - mendapat akses gratis untuk pencarian dan dapat melaporkan pelanggan bermasalah, 3) Admin - mengelola seluruh sistem dan moderasi konten.',
                'keywords' => ['user', 'pengguna', 'rental owner', 'admin', 'jenis akun'],
                'related_routes' => ['/daftar'],
                'priority' => 1,
            ],

            // Registration
            [
                'category' => 'registration',
                'title' => 'Cara Mendaftar',
                'content' => 'Untuk mendaftar di CekPenyewa.com: 1) Klik tombol Daftar, 2) Pilih jenis akun (User Reguler atau Rental Owner), 3) Isi form pendaftaran dengan data lengkap, 4) Upload dokumen yang diperlukan (KTP, SIUP untuk rental owner), 5) Verifikasi email, 6) Tunggu approval admin untuk rental owner.',
                'keywords' => ['daftar', 'registrasi', 'pendaftaran', 'akun baru'],
                'related_routes' => ['/daftar'],
                'priority' => 1,
            ],

            // Search System
            [
                'category' => 'search',
                'title' => 'Cara Mencari Data Blacklist',
                'content' => 'Untuk mencari data blacklist: 1) Login ke akun Anda, 2) Masuk ke dashboard, 3) Gunakan form pencarian dengan nama, NIK, atau nomor telepon, 4) Untuk user reguler: pastikan saldo mencukupi (Rp800-1500 per pencarian), 5) Klik tombol cari, 6) Lihat hasil pencarian dalam bentuk kartu, 7) Klik "Lihat Detail" untuk informasi lengkap.',
                'keywords' => ['cari', 'pencarian', 'search', 'blacklist', 'data', 'nama', 'nik', 'telepon'],
                'related_routes' => ['/pengguna/dasbor', '/rental/dasbor'],
                'priority' => 1,
            ],

            // Pricing
            [
                'category' => 'pricing',
                'title' => 'Harga Pencarian Data',
                'content' => 'Harga pencarian data bervariasi berdasarkan jenis rental: Rental Mobil/Motor: Rp1.500 per pencarian, Kamera: Rp1.000 per pencarian, Lainnya: Rp800 per pencarian. Rental owner mendapat akses gratis tanpa biaya. User reguler perlu topup saldo terlebih dahulu.',
                'keywords' => ['harga', 'biaya', 'pricing', 'tarif', 'mobil', 'motor', 'kamera', 'gratis'],
                'related_routes' => ['/pengguna/topup'],
                'priority' => 1,
            ],

            // Topup System
            [
                'category' => 'topup',
                'title' => 'Cara Topup Saldo',
                'content' => 'Untuk topup saldo: 1) Login sebagai user reguler, 2) Masuk ke menu Topup, 3) Pilih nominal topup (minimal Rp10.000), 4) Pilih metode pembayaran (Transfer Bank, GoPay, Dana, OVO), 5) Upload bukti transfer, 6) Tunggu verifikasi admin (1x24 jam), 7) Saldo akan otomatis bertambah setelah diverifikasi.',
                'keywords' => ['topup', 'saldo', 'pembayaran', 'transfer', 'gopay', 'dana', 'ovo'],
                'related_routes' => ['/pengguna/topup'],
                'priority' => 1,
            ],

            // Reporting System
            [
                'category' => 'reporting',
                'title' => 'Cara Melaporkan Pelanggan Bermasalah',
                'content' => 'Untuk melaporkan pelanggan bermasalah (khusus rental owner): 1) Login ke akun rental owner, 2) Masuk ke menu Laporan, 3) Isi form laporan dengan data lengkap pelanggan, 4) Jelaskan kronologi masalah yang terjadi, 5) Upload bukti pendukung (foto, dokumen), 6) Submit laporan, 7) Admin akan review dan verifikasi laporan.',
                'keywords' => ['laporan', 'report', 'pelanggan bermasalah', 'rental owner', 'kronologi'],
                'related_routes' => ['/rental/laporan'],
                'priority' => 1,
            ],

            // Payment Methods
            [
                'category' => 'payment',
                'title' => 'Metode Pembayaran',
                'content' => 'Metode pembayaran yang tersedia: 1) Transfer Bank: BCA (6050381330), BJB (12345869594939), BRI (208319382834), 2) E-Wallet: GoPay/Dana (0819-1191-9993), OVO (0822-1121-9993), semua atas nama ANGGA DWY SAPUTRA. Setelah transfer, upload bukti di halaman topup.',
                'keywords' => ['pembayaran', 'transfer', 'bank', 'bca', 'bri', 'bjb', 'gopay', 'dana', 'ovo'],
                'related_routes' => ['/pengguna/topup'],
                'priority' => 1,
            ],

            // Verification
            [
                'category' => 'verification',
                'title' => 'Verifikasi Dokumen',
                'content' => 'Sistem verifikasi dokumen menggunakan QR Code untuk memastikan keaslian laporan. Setiap laporan yang diverifikasi akan mendapat QR Code unik yang dapat dipindai untuk mengecek validitas data. Fitur ini membantu mencegah laporan palsu dan meningkatkan kredibilitas platform.',
                'keywords' => ['verifikasi', 'dokumen', 'qr code', 'validitas', 'keaslian'],
                'related_routes' => ['/verifikasi-dokumen'],
                'priority' => 1,
            ],

            // Sponsor System
            [
                'category' => 'sponsor',
                'title' => 'Program Sponsor',
                'content' => 'Program sponsor memungkinkan bisnis rental untuk mempromosikan layanan mereka di platform CekPenyewa.com. Sponsor akan ditampilkan di halaman utama dan hasil pencarian. Untuk menjadi sponsor, hubungi admin melalui halaman kontak atau WhatsApp.',
                'keywords' => ['sponsor', 'promosi', 'iklan', 'bisnis rental'],
                'related_routes' => ['/sponsor'],
                'priority' => 1,
            ],

            // Contact & Support
            [
                'category' => 'support',
                'title' => 'Kontak dan Dukungan',
                'content' => 'Untuk bantuan dan dukungan: Email: support@cekpenyewa.com, WhatsApp: +62 819-1191-9993, Jam operasional: Senin-Jumat 09:00-17:00 WIB. Tim support siap membantu masalah teknis, verifikasi akun, dan pertanyaan umum tentang platform.',
                'keywords' => ['kontak', 'support', 'bantuan', 'whatsapp', 'email', 'dukungan'],
                'related_routes' => ['/kontak'],
                'priority' => 1,
            ],

            // FAQ
            [
                'category' => 'faq',
                'title' => 'Pertanyaan Umum (FAQ)',
                'content' => 'FAQ CekPenyewa.com: Q: Apakah gratis untuk rental owner? A: Ya, rental owner mendapat akses gratis. Q: Berapa lama verifikasi topup? A: Maksimal 1x24 jam. Q: Bagaimana cara menjadi rental owner? A: Daftar dengan upload SIUP dan tunggu approval admin. Q: Data apa saja yang bisa dicari? A: Nama, NIK, nomor telepon penyewa bermasalah.',
                'keywords' => ['faq', 'pertanyaan', 'gratis', 'verifikasi', 'rental owner', 'data'],
                'related_routes' => ['/faq'],
                'priority' => 1,
            ],
        ];

        foreach ($knowledgeBase as $item) {
            ChatbotKnowledgeBase::updateOrCreate(
                [
                    'category' => $item['category'],
                    'title' => $item['title']
                ],
                $item
            );
        }
    }
}
