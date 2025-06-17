<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Carbon\Carbon;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create blog categories
        $categories = [
            [
                'name' => 'Tips Rental',
                'slug' => 'tips-rental',
                'description' => 'Tips dan trik untuk bisnis rental kendaraan',
                'meta_title' => 'Tips Rental Kendaraan - CekPenyewa.com',
                'meta_description' => 'Kumpulan tips dan trik untuk menjalankan bisnis rental kendaraan yang sukses dan aman.',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Keamanan Rental',
                'slug' => 'keamanan-rental',
                'description' => 'Artikel tentang keamanan dan perlindungan bisnis rental',
                'meta_title' => 'Keamanan Bisnis Rental - CekPenyewa.com',
                'meta_description' => 'Panduan lengkap tentang keamanan dan perlindungan bisnis rental dari berbagai risiko.',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Blacklist Guide',
                'slug' => 'blacklist-guide',
                'description' => 'Panduan tentang sistem blacklist rental',
                'meta_title' => 'Panduan Blacklist Rental - CekPenyewa.com',
                'meta_description' => 'Panduan lengkap tentang sistem blacklist rental dan cara menggunakannya dengan efektif.',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'Berita Rental',
                'slug' => 'berita-rental',
                'description' => 'Berita terkini seputar industri rental',
                'meta_title' => 'Berita Industri Rental - CekPenyewa.com',
                'meta_description' => 'Berita terkini dan update terbaru seputar industri rental kendaraan di Indonesia.',
                'is_active' => true,
                'sort_order' => 4
            ]
        ];

        foreach ($categories as $categoryData) {
            BlogCategory::create($categoryData);
        }

        // Get admin user for author
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin Blog',
                'email' => 'admin@cekpenyewa.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'account_status' => 'approved',
                'email_verified_at' => now()
            ]);
        }

        // Create sample blog posts
        $posts = [
            [
                'title' => '10 Tips Memulai Bisnis Rental Mobil yang Sukses',
                'slug' => '10-tips-memulai-bisnis-rental-mobil-sukses',
                'excerpt' => 'Panduan lengkap untuk memulai bisnis rental mobil dari nol hingga sukses. Pelajari strategi, tips, dan trik yang terbukti efektif.',
                'content' => $this->getTipsRentalContent(),
                'category_id' => 1, // Tips Rental
                'author_id' => $admin->id,
                'status' => 'published',
                'seo_title' => '10 Tips Memulai Bisnis Rental Mobil yang Sukses | CekPenyewa',
                'seo_description' => 'Panduan lengkap memulai bisnis rental mobil yang sukses. Tips strategi, modal, perizinan, dan cara menghindari kerugian.',
                'seo_keywords' => 'bisnis rental mobil, tips rental, usaha rental, modal rental mobil',
                'published_at' => Carbon::now()->subDays(7),
                'views_count' => 1250,
                'reading_time' => 8
            ],
            [
                'title' => 'Cara Melindungi Bisnis Rental dari Penyewa Bermasalah',
                'slug' => 'cara-melindungi-bisnis-rental-penyewa-bermasalah',
                'excerpt' => 'Strategi efektif untuk melindungi bisnis rental Anda dari penyewa yang berpotensi merugikan. Termasuk sistem screening dan verifikasi.',
                'content' => $this->getKeamananRentalContent(),
                'category_id' => 2, // Keamanan Rental
                'author_id' => $admin->id,
                'status' => 'published',
                'seo_title' => 'Cara Melindungi Bisnis Rental dari Penyewa Bermasalah',
                'seo_description' => 'Pelajari strategi efektif melindungi bisnis rental dari penyewa bermasalah. Sistem screening, verifikasi, dan pencegahan kerugian.',
                'seo_keywords' => 'keamanan rental, penyewa bermasalah, screening penyewa, verifikasi rental',
                'published_at' => Carbon::now()->subDays(5),
                'views_count' => 890,
                'reading_time' => 6
            ],
            [
                'title' => 'Panduan Lengkap Menggunakan Sistem Blacklist CekPenyewa',
                'slug' => 'panduan-lengkap-sistem-blacklist-cekpenyewa',
                'excerpt' => 'Tutorial step-by-step menggunakan sistem blacklist CekPenyewa untuk melindungi bisnis rental Anda dari penyewa bermasalah.',
                'content' => $this->getBlacklistGuideContent(),
                'category_id' => 3, // Blacklist Guide
                'author_id' => $admin->id,
                'status' => 'published',
                'seo_title' => 'Panduan Lengkap Sistem Blacklist CekPenyewa | Tutorial',
                'seo_description' => 'Tutorial lengkap menggunakan sistem blacklist CekPenyewa. Cara cek, lapor, dan melindungi bisnis rental dari penyewa bermasalah.',
                'seo_keywords' => 'blacklist rental, cekpenyewa, sistem blacklist, tutorial blacklist',
                'published_at' => Carbon::now()->subDays(3),
                'views_count' => 2100,
                'reading_time' => 10
            ],
            [
                'title' => 'Tren Industri Rental 2024: Digitalisasi dan Keamanan',
                'slug' => 'tren-industri-rental-2024-digitalisasi-keamanan',
                'excerpt' => 'Analisis tren terkini industri rental di Indonesia tahun 2024, fokus pada digitalisasi layanan dan peningkatan sistem keamanan.',
                'content' => $this->getBeritaRentalContent(),
                'category_id' => 4, // Berita Rental
                'author_id' => $admin->id,
                'status' => 'published',
                'seo_title' => 'Tren Industri Rental 2024: Digitalisasi dan Keamanan',
                'seo_description' => 'Analisis tren industri rental Indonesia 2024. Digitalisasi layanan, sistem keamanan, dan perkembangan teknologi rental.',
                'seo_keywords' => 'tren rental 2024, industri rental indonesia, digitalisasi rental, teknologi rental',
                'published_at' => Carbon::now()->subDays(1),
                'views_count' => 450,
                'reading_time' => 7
            ]
        ];

        foreach ($posts as $postData) {
            $post = BlogPost::create($postData);

            // Calculate SEO score
            $post->seo_score = $this->calculateSeoScore($post);
            $post->save();
        }

        // Create blog settings
        $this->createBlogSettings();
    }

    private function getTipsRentalContent()
    {
        return '<h2>Memulai Bisnis Rental Mobil yang Menguntungkan</h2>

<p>Bisnis rental mobil merupakan salah satu peluang usaha yang menjanjikan di Indonesia. Dengan pertumbuhan ekonomi dan mobilitas yang tinggi, kebutuhan akan kendaraan rental terus meningkat. Berikut adalah 10 tips untuk memulai bisnis rental mobil yang sukses:</p>

<h3>1. Riset Pasar dan Lokasi</h3>
<p>Lakukan riset mendalam tentang kondisi pasar di area target Anda. Identifikasi kompetitor, harga pasar, dan kebutuhan konsumen. Pilih lokasi strategis yang mudah diakses dan memiliki potensi pelanggan tinggi.</p>

<h3>2. Persiapan Modal yang Cukup</h3>
<p>Hitung kebutuhan modal dengan cermat, termasuk:</p>
<ul>
<li>Pembelian atau leasing kendaraan</li>
<li>Biaya perizinan dan legalitas</li>
<li>Modal operasional 6-12 bulan</li>
<li>Biaya marketing dan promosi</li>
<li>Dana cadangan untuk maintenance</li>
</ul>

<h3>3. Pilih Jenis Kendaraan yang Tepat</h3>
<p>Mulai dengan kendaraan yang paling diminati seperti:</p>
<ul>
<li>Mobil ekonomis untuk harian</li>
<li>MPV untuk keluarga</li>
<li>Mobil mewah untuk acara khusus</li>
</ul>

<h3>4. Urus Legalitas dengan Benar</h3>
<p>Pastikan semua dokumen legal lengkap:</p>
<ul>
<li>SIUP (Surat Izin Usaha Perdagangan)</li>
<li>TDP (Tanda Daftar Perusahaan)</li>
<li>NPWP</li>
<li>Izin usaha rental</li>
</ul>

<h3>5. Sistem Keamanan dan Verifikasi</h3>
<p>Implementasikan sistem keamanan yang ketat untuk melindungi aset Anda. Gunakan sistem blacklist seperti CekPenyewa untuk screening calon penyewa.</p>

<blockquote>
<p><strong>Tips Pro:</strong> Selalu lakukan verifikasi identitas, riwayat kredit, dan referensi sebelum menyewakan kendaraan.</p>
</blockquote>

<h3>6. Tentukan Harga yang Kompetitif</h3>
<p>Riset harga kompetitor dan tentukan pricing strategy yang menguntungkan namun tetap kompetitif. Pertimbangkan faktor musiman dan event khusus.</p>

<h3>7. Marketing Digital yang Efektif</h3>
<p>Manfaatkan platform digital untuk promosi:</p>
<ul>
<li>Website dan SEO</li>
<li>Media sosial</li>
<li>Google Ads</li>
<li>Platform marketplace</li>
</ul>

<h3>8. Layanan Pelanggan Prima</h3>
<p>Berikan pelayanan terbaik untuk membangun loyalitas pelanggan. Respon cepat, kendaraan bersih, dan proses yang mudah adalah kunci kepuasan pelanggan.</p>

<h3>9. Maintenance Rutin</h3>
<p>Jadwalkan maintenance rutin untuk menjaga kondisi kendaraan tetap prima. Kendaraan yang terawat akan mengurangi biaya perbaikan dan meningkatkan kepuasan pelanggan.</p>

<h3>10. Evaluasi dan Pengembangan</h3>
<p>Lakukan evaluasi berkala terhadap performa bisnis. Analisis data pelanggan, tingkat okupansi, dan profitabilitas untuk pengembangan bisnis ke depan.</p>

<h2>Kesimpulan</h2>
<p>Memulai bisnis rental mobil membutuhkan persiapan yang matang dan strategi yang tepat. Dengan mengikuti tips di atas dan konsisten dalam pelaksanaannya, bisnis rental Anda memiliki peluang besar untuk sukses dan berkembang.</p>';
    }

    private function getKeamananRentalContent()
    {
        return '<h2>Strategi Melindungi Bisnis Rental dari Kerugian</h2>

<p>Bisnis rental kendaraan memiliki risiko yang cukup tinggi, terutama dari penyewa yang berpotensi merugikan. Berikut adalah strategi komprehensif untuk melindungi bisnis Anda:</p>

<h3>Sistem Screening Penyewa</h3>
<p>Implementasikan sistem screening yang ketat untuk setiap calon penyewa:</p>

<h4>1. Verifikasi Identitas</h4>
<ul>
<li>KTP asli dan fotokopi</li>
<li>SIM yang masih berlaku</li>
<li>Kartu keluarga</li>
<li>Foto selfie dengan KTP</li>
</ul>

<h4>2. Verifikasi Finansial</h4>
<ul>
<li>Slip gaji atau surat keterangan penghasilan</li>
<li>Rekening koran 3 bulan terakhir</li>
<li>Kartu kredit (jika ada)</li>
</ul>

<h4>3. Referensi dan Riwayat</h4>
<ul>
<li>Kontak darurat keluarga</li>
<li>Referensi dari tempat kerja</li>
<li>Riwayat rental sebelumnya</li>
<li>Cek blacklist di sistem CekPenyewa</li>
</ul>

<h3>Sistem Jaminan yang Efektif</h3>
<p>Terapkan sistem jaminan berlapis:</p>

<h4>Deposit Keamanan</h4>
<p>Tentukan deposit yang cukup untuk menutupi risiko kerusakan atau kehilangan. Umumnya 20-30% dari harga kendaraan.</p>

<h4>Jaminan Tambahan</h4>
<ul>
<li>BPKB motor/mobil pribadi</li>
<li>Sertifikat tanah/rumah</li>
<li>Emas atau barang berharga lainnya</li>
</ul>

<h3>Teknologi Keamanan</h3>
<p>Manfaatkan teknologi untuk monitoring:</p>

<h4>GPS Tracking</h4>
<p>Pasang GPS tracker pada setiap kendaraan untuk monitoring real-time lokasi dan rute perjalanan.</p>

<h4>Immobilizer</h4>
<p>Sistem pengaman yang dapat mematikan mesin dari jarak jauh jika terjadi masalah.</p>

<h4>Dashcam</h4>
<p>Kamera dashboard untuk merekam aktivitas selama penyewaan.</p>

<h3>Kontrak yang Jelas</h3>
<p>Buat kontrak sewa yang detail dan jelas mencakup:</p>
<ul>
<li>Hak dan kewajiban kedua belah pihak</li>
<li>Sanksi pelanggaran</li>
<li>Prosedur pengembalian</li>
<li>Kondisi force majeure</li>
</ul>

<h3>Asuransi Komprehensif</h3>
<p>Lindungi aset dengan asuransi yang tepat:</p>
<ul>
<li>Asuransi all risk untuk kendaraan</li>
<li>Asuransi tanggung jawab hukum</li>
<li>Asuransi kehilangan</li>
</ul>

<blockquote>
<p><strong>Penting:</strong> Selalu gunakan sistem blacklist seperti CekPenyewa untuk mengecek riwayat calon penyewa sebelum menyetujui penyewaan.</p>
</blockquote>

<h2>Kesimpulan</h2>
<p>Keamanan bisnis rental memerlukan pendekatan yang komprehensif. Kombinasi screening yang ketat, teknologi keamanan, kontrak yang jelas, dan asuransi yang tepat akan melindungi bisnis Anda dari berbagai risiko.</p>';
    }

    private function getBlacklistGuideContent()
    {
        return '<h2>Panduan Menggunakan Sistem Blacklist CekPenyewa</h2>

<p>Sistem blacklist CekPenyewa adalah platform terpercaya untuk melindungi bisnis rental dari penyewa bermasalah. Berikut panduan lengkap penggunaannya:</p>

<h3>Cara Mengecek Data Penyewa</h3>

<h4>1. Akses Website CekPenyewa.com</h4>
<p>Buka website resmi CekPenyewa.com dan gunakan fitur pencarian di halaman utama.</p>

<h4>2. Input Data Penyewa</h4>
<p>Masukkan informasi penyewa yang ingin dicek:</p>
<ul>
<li>Nama lengkap</li>
<li>NIK (Nomor Induk Kependudukan)</li>
<li>Nomor HP</li>
</ul>

<h4>3. Analisis Hasil Pencarian</h4>
<p>Sistem akan menampilkan hasil pencarian dengan informasi:</p>
<ul>
<li>Status validitas data</li>
<li>Riwayat laporan (jika ada)</li>
<li>Tingkat risiko</li>
<li>Detail kronologi masalah</li>
</ul>

<h3>Cara Melaporkan Penyewa Bermasalah</h3>

<h4>1. Daftar Akun Rental</h4>
<p>Buat akun sebagai pengusaha rental dengan melengkapi:</p>
<ul>
<li>Data perusahaan rental</li>
<li>Dokumen legalitas</li>
<li>Informasi kontak</li>
</ul>

<h4>2. Buat Laporan Baru</h4>
<p>Akses menu "Lapor" dan isi form laporan dengan lengkap:</p>

<h5>Data Penyewa</h5>
<ul>
<li>Informasi identitas lengkap</li>
<li>Foto KTP/SIM</li>
<li>Foto penyewa</li>
</ul>

<h5>Detail Masalah</h5>
<ul>
<li>Jenis pelanggaran</li>
<li>Kronologi kejadian</li>
<li>Bukti pendukung (foto, dokumen)</li>
<li>Nilai kerugian</li>
</ul>

<h4>3. Verifikasi Laporan</h4>
<p>Tim CekPenyewa akan melakukan verifikasi laporan sebelum dipublikasikan untuk memastikan akurasi data.</p>

<h3>Fitur Premium untuk Rental</h3>

<h4>API Access</h4>
<p>Integrasikan sistem CekPenyewa dengan sistem rental Anda melalui API untuk pengecekan otomatis.</p>

<h4>Bulk Check</h4>
<p>Cek multiple data penyewa sekaligus untuk efisiensi operasional.</p>

<h4>Real-time Notification</h4>
<p>Dapatkan notifikasi real-time jika ada laporan baru terkait penyewa yang pernah Anda layani.</p>

<h3>Tips Menggunakan Sistem Blacklist</h3>

<h4>1. Cek Sebelum Menyewa</h4>
<p>Selalu lakukan pengecekan sebelum menyetujui penyewaan, meskipun penyewa terlihat terpercaya.</p>

<h4>2. Verifikasi Silang</h4>
<p>Cocokkan data di sistem dengan dokumen identitas yang diberikan penyewa.</p>

<h4>3. Lapor dengan Akurat</h4>
<p>Pastikan laporan yang Anda buat akurat dan didukung bukti yang kuat.</p>

<h4>4. Update Berkala</h4>
<p>Lakukan pengecekan berkala untuk penyewa yang sering menggunakan jasa rental Anda.</p>

<blockquote>
<p><strong>Catatan Penting:</strong> Sistem blacklist harus digunakan secara bijak dan bertanggung jawab. Pastikan laporan yang dibuat berdasarkan fakta dan bukti yang valid.</p>
</blockquote>

<h3>Manfaat Menggunakan CekPenyewa</h3>

<ul>
<li><strong>Pencegahan Kerugian:</strong> Hindari penyewa bermasalah sebelum terjadi kerugian</li>
<li><strong>Efisiensi Screening:</strong> Proses verifikasi lebih cepat dan akurat</li>
<li><strong>Jaringan Komunitas:</strong> Berbagi informasi dengan sesama pengusaha rental</li>
<li><strong>Data Terpercaya:</strong> Database yang terverifikasi dan terupdate</li>
</ul>

<h2>Kesimpulan</h2>
<p>Sistem blacklist CekPenyewa adalah tools penting untuk melindungi bisnis rental. Dengan menggunakan sistem ini secara optimal, Anda dapat mengurangi risiko kerugian dan meningkatkan keamanan bisnis rental Anda.</p>';
    }

    private function getBeritaRentalContent()
    {
        return '<h2>Transformasi Digital Industri Rental Indonesia</h2>

<p>Industri rental kendaraan di Indonesia mengalami transformasi signifikan di tahun 2024. Digitalisasi dan peningkatan sistem keamanan menjadi dua tren utama yang mendorong pertumbuhan sektor ini.</p>

<h3>Digitalisasi Layanan Rental</h3>

<h4>Platform Online Terintegrasi</h4>
<p>Semakin banyak perusahaan rental yang mengadopsi platform online terintegrasi untuk:</p>
<ul>
<li>Booking dan reservasi real-time</li>
<li>Manajemen armada digital</li>
<li>Sistem pembayaran cashless</li>
<li>Customer relationship management</li>
</ul>

<h4>Aplikasi Mobile</h4>
<p>Pengembangan aplikasi mobile menjadi prioritas untuk memberikan kemudahan akses bagi pelanggan:</p>
<ul>
<li>Pencarian kendaraan berdasarkan lokasi</li>
<li>Booking instan dengan konfirmasi otomatis</li>
<li>Tracking kendaraan real-time</li>
<li>Rating dan review sistem</li>
</ul>

<h3>Peningkatan Sistem Keamanan</h3>

<h4>Teknologi IoT dan GPS</h4>
<p>Implementasi Internet of Things (IoT) dan GPS tracking semakin masif:</p>
<ul>
<li>Monitoring kendaraan 24/7</li>
<li>Alert system untuk pelanggaran</li>
<li>Predictive maintenance</li>
<li>Anti-theft protection</li>
</ul>

<h4>Sistem Blacklist Terintegrasi</h4>
<p>Platform seperti CekPenyewa.com menjadi standar industri untuk:</p>
<ul>
<li>Verifikasi calon penyewa</li>
<li>Sharing informasi antar rental</li>
<li>Database penyewa bermasalah</li>
<li>Risk assessment otomatis</li>
</ul>

<h3>Tren Pasar 2024</h3>

<h4>Pertumbuhan Segmen</h4>
<p>Data menunjukkan pertumbuhan signifikan di beberapa segmen:</p>
<ul>
<li><strong>Corporate Rental:</strong> +25% YoY</li>
<li><strong>Long-term Rental:</strong> +30% YoY</li>
<li><strong>Luxury Car Rental:</strong> +20% YoY</li>
<li><strong>Motorcycle Rental:</strong> +35% YoY</li>
</ul>

<h4>Preferensi Konsumen</h4>
<p>Perubahan perilaku konsumen yang teridentifikasi:</p>
<ul>
<li>Preferensi booking online (78%)</li>
<li>Pembayaran digital (65%)</li>
<li>Fleksibilitas jadwal (82%)</li>
<li>Transparansi harga (91%)</li>
</ul>

<h3>Tantangan dan Peluang</h3>

<h4>Tantangan Utama</h4>
<ul>
<li>Kompetisi harga yang ketat</li>
<li>Biaya teknologi yang tinggi</li>
<li>Regulasi yang belum jelas</li>
<li>Skill gap SDM digital</li>
</ul>

<h4>Peluang Pengembangan</h4>
<ul>
<li>Ekspansi ke kota tier-2 dan tier-3</li>
<li>Diversifikasi jenis kendaraan</li>
<li>Partnership dengan platform digital</li>
<li>Layanan value-added services</li>
</ul>

<h3>Prediksi Masa Depan</h3>

<h4>2025-2026</h4>
<p>Proyeksi perkembangan industri rental:</p>
<ul>
<li>Adopsi AI untuk customer service</li>
<li>Autonomous vehicle pilot project</li>
<li>Blockchain untuk smart contracts</li>
<li>Sustainability focus dengan electric vehicles</li>
</ul>

<blockquote>
<p><strong>Insight:</strong> Perusahaan rental yang tidak beradaptasi dengan digitalisasi diprediksi akan tertinggal dalam 2-3 tahun ke depan.</p>
</blockquote>

<h3>Rekomendasi untuk Pelaku Industri</h3>

<ol>
<li><strong>Investasi Teknologi:</strong> Alokasikan budget untuk digitalisasi sistem</li>
<li><strong>Training SDM:</strong> Tingkatkan kemampuan digital tim</li>
<li><strong>Partnership Strategis:</strong> Kolaborasi dengan tech companies</li>
<li><strong>Customer Experience:</strong> Fokus pada kemudahan dan kecepatan layanan</li>
<li><strong>Data Analytics:</strong> Manfaatkan data untuk decision making</li>
</ol>

<h2>Kesimpulan</h2>
<p>Tahun 2024 menjadi titik balik industri rental Indonesia menuju era digital. Perusahaan yang mampu beradaptasi dengan tren digitalisasi dan keamanan akan memiliki competitive advantage yang signifikan di masa depan.</p>';
    }

    private function calculateSeoScore($post)
    {
        $score = 0;

        // Title length (30-60 chars)
        $titleLength = strlen($post->title);
        if ($titleLength >= 30 && $titleLength <= 60) {
            $score += 20;
        }

        // Content length (min 300 words)
        $wordCount = str_word_count(strip_tags($post->content));
        if ($wordCount >= 300) {
            $score += 25;
        }

        // Meta description
        if ($post->seo_description && strlen($post->seo_description) >= 120 && strlen($post->seo_description) <= 160) {
            $score += 20;
        }

        // Keywords
        if ($post->seo_keywords) {
            $score += 15;
        }

        // Headings in content
        if (preg_match_all('/<h[1-6][^>]*>/i', $post->content) > 0) {
            $score += 20;
        }

        return min($score, 100);
    }

    private function createBlogSettings()
    {
        $blogSettings = [
            'blog_title' => 'Blog CekPenyewa.com',
            'blog_description' => 'Informasi terkini seputar rental dan blacklist penyewa',
            'blog_posts_per_page' => 10,
            'blog_allow_comments' => true,
            'blog_moderate_comments' => true,
            'blog_auto_approve_registered_users' => false,
            'blog_show_author_bio' => true,
            'blog_enable_social_sharing' => true,
            'blog_enable_related_posts' => true,
            'blog_related_posts_count' => 3,
            'blog_comments_enabled_globally' => true,
            'blog_comments_require_approval_globally' => true,
            'blog_comments_allow_guest_comments' => true,
            'blog_comments_max_depth' => 3,
            'blog_comments_per_page' => 20,
            'blog_comments_auto_close_days' => 30,
        ];

        foreach ($blogSettings as $key => $value) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => is_bool($value) ? 'boolean' : (is_int($value) ? 'integer' : 'string')]
            );
        }
    }
}
