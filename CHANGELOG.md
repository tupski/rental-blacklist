# Changelog

Semua perubahan penting pada project ini akan didokumentasikan dalam file ini.

Format berdasarkan [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
dan project ini mengikuti [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2024-12-06

### Added
- 🎉 **Rilis Pertama** - Sistem Blacklist Rental Indonesia
- 🔍 **Pencarian Publik** - Pencarian blacklist dengan data disensor
- 👥 **Sistem Autentikasi** - Login/register untuk pengusaha rental
- 📊 **Dashboard** - Dashboard lengkap dengan statistik
- 📝 **CRUD Laporan** - Tambah, edit, hapus laporan blacklist
- 📎 **Upload Bukti** - Upload gambar, video, dan dokumen
- ✅ **Validasi Otomatis** - Status valid jika ≥2 laporan dari user berbeda
- 🔒 **Data Sensor** - Privasi data untuk akses publik
- 📱 **Mobile Friendly** - Responsive design untuk semua perangkat
- ⚡ **AJAX Real-time** - Semua operasi tanpa reload halaman
- 🎨 **UI/UX Modern** - Design cantik dengan TailwindCSS
- 🔗 **Dynamic URL** - URL berubah dengan history.pushState

### Technical
- **Framework**: Laravel 12
- **Authentication**: Laravel Breeze
- **Frontend**: Blade Templates + TailwindCSS
- **JavaScript**: jQuery AJAX
- **Database**: SQLite (default)
- **Storage**: Laravel File Storage
- **Development**: Laravel Debugbar

### Features
- **Untuk Publik**:
  - Pencarian berdasarkan NIK dan nama
  - Data disensor untuk privasi
  - Tidak sensor value yang dicari
  - CTA untuk akses data lengkap
  
- **Untuk Pengusaha Rental**:
  - Dashboard dengan statistik
  - Pencarian tanpa sensor
  - CRUD laporan blacklist
  - Upload bukti pendukung
  - Sistem validasi otomatis

### Security
- CSRF Protection
- File Upload Validation
- Input Sanitization
- Authorization Control
- Data Censoring

### Documentation
- README.md lengkap
- Dokumentasi teknis
- Summary implementasi
- Changelog
- License MIT

---

## Template untuk Update Selanjutnya

### [Unreleased]
#### Added
#### Changed
#### Deprecated
#### Removed
#### Fixed
#### Security
