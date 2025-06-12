# 🛡️ Sistem Blacklist Rental Indonesia

Sistem web Laravel untuk mengelola blacklist pelanggan rental umum (mobil, motor, kamera, dll) dengan fitur pencarian publik dan manajemen laporan untuk pengusaha rental.

![Laravel](https://img.shields.io/badge/Laravel-12-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.0-blue.svg)
![jQuery](https://img.shields.io/badge/jQuery-3.7-yellow.svg)

## 🚀 Fitur Utama

### 👥 Untuk Publik (Tanpa Login)
- 🔍 **Pencarian Real-time** - Cari berdasarkan NIK atau nama dengan AJAX
- 🔒 **Data Privasi** - Informasi sensitif disensor otomatis
- 📱 **Mobile Friendly** - Responsive design untuk semua perangkat
- 🔗 **URL Dinamis** - History browser terintegrasi

### 🏢 Untuk Pengusaha Rental (Login)
- 📊 **Dashboard Lengkap** - Statistik dan overview laporan
- 📝 **Manajemen Laporan** - CRUD laporan blacklist
- 📎 **Upload Bukti** - Gambar, video, dan dokumen
- ✅ **Validasi Otomatis** - Sistem verifikasi laporan
- 🔓 **Akses Penuh** - Data tanpa sensor

## 🛠️ Teknologi

- **Backend**: Laravel 12 + Laravel Breeze
- **Frontend**: Blade Templates + TailwindCSS
- **JavaScript**: jQuery AJAX
- **Database**: SQLite (default)
- **Storage**: Laravel File Storage

## 📦 Instalasi Cepat

```bash
# Clone repository
git clone https://github.com/tupski/rental-blacklist.git
cd rental-blacklist

# Install dependencies
composer install
npm install && npm run build

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate:fresh --seed
php artisan storage:link

# Jalankan server
php artisan serve
```

## 🌐 Demo

**URL**: `http://127.0.0.1:8000`

**Akun Testing**:
- Email: `budi@rental.com` | Password: `password`
- Email: `siti@rental.com` | Password: `password`

## 📱 Screenshot

### Halaman Pencarian Publik
- Interface modern dan responsive
- Pencarian real-time dengan AJAX
- Data disensor untuk privasi

### Dashboard Pengusaha Rental
- Statistik laporan lengkap
- Manajemen laporan mudah
- Upload bukti terintegrasi

## 🔒 Keamanan

- ✅ CSRF Protection
- ✅ File Upload Validation
- ✅ Input Sanitization
- ✅ Authorization Control
- ✅ Data Censoring

## 📚 Dokumentasi

Dokumentasi lengkap tersedia di folder `docs/`:
- [Dokumentasi Lengkap](docs/DOKUMENTASI.md)
- [Summary Implementasi](docs/SUMMARY.md)

## 🧪 Testing

```bash
# Test dengan data seeder
# Cari: "Angga" atau "3674012345670001"
# Login dan test CRUD laporan
```

## 🤝 Kontribusi

1. Fork repository ini
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📄 Lisensi

Distributed under the MIT License. See `LICENSE` for more information.

## 📞 Kontak

**Developer**: Tupski  
**Repository**: [https://github.com/tupski/rental-blacklist](https://github.com/tupski/rental-blacklist)

## 🙏 Acknowledgments

- [Laravel](https://laravel.com)
- [TailwindCSS](https://tailwindcss.com)
- [Font Awesome](https://fontawesome.com)
- [jQuery](https://jquery.com)

---

⭐ **Jangan lupa beri star jika project ini membantu!**
