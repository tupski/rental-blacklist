# Sistem Blacklist Rental Indonesia

Sistem web Laravel untuk mengelola blacklist pelanggan rental umum (mobil, motor, kamera, dll) dengan fitur pencarian publik dan manajemen laporan untuk pengusaha rental.

## 🚀 Fitur Utama

### Untuk Publik (Tanpa Login)
- ✅ Pencarian berdasarkan NIK dan nama dengan jQuery AJAX
- ✅ Data hasil pencarian disensor untuk privasi:
  - Nama → hanya inisial (A***R R******A)
  - NIK → bagian tengah disensor (3674*********0045)
  - No HP → sensor bagian tengah (0819******88)
- ✅ Menampilkan jumlah laporan, jenis rental, jenis laporan terakhir, tanggal terakhir
- ✅ URL pencarian dinamis dengan history.pushState
- ✅ Tidak sensor value yang dicari (jika mencari "Angga Artupas", tampilkan utuh)
- ✅ CTA untuk melihat data lengkap (berbayar untuk non-pengusaha rental)

### Untuk Pengusaha Rental (Login Required)
- ✅ Dashboard dengan statistik laporan
- ✅ Pencarian data tanpa sensor
- ✅ Tambah laporan blacklist baru dengan:
  - Data pribadi: nama_lengkap, nik, jenis_kelamin, no_hp, alamat
  - Data rental: jenis_rental, tanggal_kejadian
  - Jenis laporan (multiple): percobaan penipuan, penipuan, tidak mengembalikan barang, identitas palsu, sindikat, merusak barang
  - Kronologi kejadian
  - Upload bukti (gambar, video, dokumen)
- ✅ Edit & hapus laporan yang dibuat sendiri
- ✅ Melihat laporan dari user lain (read-only)
- ✅ Sistem validasi otomatis: jika ≥2 laporan dari user berbeda untuk NIK yang sama → status "Valid"

## 🛠️ Teknologi

- **Backend**: Laravel 12
- **Frontend**: Blade Templates + TailwindCSS
- **JavaScript**: jQuery AJAX
- **Authentication**: Laravel Breeze
- **Database**: SQLite (default)
- **File Storage**: Laravel Storage (public disk)

## 📦 Instalasi

1. **Clone dan Setup**
```bash
git clone <repository-url>
cd rental-blacklist
composer install
npm install && npm run build
```

2. **Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Database Setup**
```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

4. **Jalankan Server**
```bash
php artisan serve
```

Akses aplikasi di: `http://127.0.0.1:8000`

## 👥 Akun Testing

Setelah menjalankan seeder, tersedia akun testing:

**Pengusaha Rental 1:**
- Email: `budi@rental.com`
- Password: `password`

**Pengusaha Rental 2:**
- Email: `siti@rental.com`
- Password: `password`

## 🗄️ Struktur Database

### Tabel `users`
- `id`, `name`, `email`, `password`
- `role` (default: 'pengusaha_rental')
- `created_at`, `updated_at`

### Tabel `rental_blacklist`
- `id`, `nik`, `nama_lengkap`, `jenis_kelamin`
- `no_hp`, `alamat`, `jenis_rental`
- `jenis_laporan` (JSON array)
- `status_validitas` (enum: Pending, Valid, Invalid)
- `kronologi`, `bukti` (JSON array), `tanggal_kejadian`
- `user_id` (foreign key), `created_at`, `updated_at`

## 🔍 API Endpoints

### Public Routes
- `GET /` - Halaman pencarian publik
- `POST /search` - Pencarian blacklist (data disensor)
- `GET /detail/{id}` - Detail laporan (CTA untuk akses penuh)

### Authenticated Routes
- `GET /dashboard` - Dashboard pengusaha rental
- `GET /dashboard/blacklist` - Kelola laporan
- `GET /dashboard/blacklist/create` - Form tambah laporan
- `POST /dashboard/blacklist` - Simpan laporan baru
- `GET /dashboard/blacklist/{id}/edit` - Form edit laporan
- `PUT /dashboard/blacklist/{id}` - Update laporan
- `DELETE /dashboard/blacklist/{id}` - Hapus laporan
- `POST /dashboard/blacklist/search` - Pencarian tanpa sensor

## 🎨 Fitur UI/UX

- ✅ Responsive design dengan TailwindCSS
- ✅ Loading states dan feedback visual
- ✅ AJAX untuk semua operasi CRUD tanpa reload
- ✅ Dynamic URL updates dengan pushState
- ✅ Modal untuk detail laporan
- ✅ File upload dengan validasi
- ✅ Form validation dengan error handling
- ✅ Mobile-friendly navigation

## 🔒 Keamanan & Validasi

- ✅ CSRF protection untuk semua form
- ✅ File upload validation (type, size)
- ✅ Input sanitization dan validation
- ✅ Authorization: user hanya bisa edit/hapus laporan sendiri
- ✅ Data censoring untuk akses publik

## 📱 Rencana Pengembangan

### Sistem Pembayaran
- Kredit untuk pengguna umum (per sekali lihat data)
- Berlangganan untuk pengusaha rental
- Pembayaran manual yang bisa disetting admin

### Fitur Admin
- Panel admin untuk moderasi laporan
- Pengaturan sistem pembayaran
- Statistik dan analytics

### Fitur Tambahan
- Notifikasi email untuk laporan baru
- Export data laporan
- API untuk integrasi dengan sistem lain
- Sistem rating dan review

## 🧪 Testing

### Manual Testing
1. Buka `http://127.0.0.1:8000`
2. Test pencarian dengan data seeder:
   - Cari "Angga" atau "3674012345670001"
   - Verifikasi data disensor kecuali yang dicari
3. Register/login sebagai pengusaha rental
4. Test CRUD laporan di dashboard
5. Verifikasi sistem validasi otomatis

### Data Testing
Seeder menyediakan 4 laporan sample dengan 2 NIK yang sama dari user berbeda untuk testing validasi otomatis.

## 📄 Lisensi

MIT License - Silakan gunakan untuk keperluan komersial maupun non-komersial.

## 🤝 Kontribusi

1. Fork repository
2. Buat feature branch
3. Commit changes
4. Push ke branch
5. Buat Pull Request

## 📞 Support

Untuk pertanyaan atau dukungan, silakan buat issue di repository ini.
