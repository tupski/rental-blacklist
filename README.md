# RentalGuard - Sistem Blacklist Rental Indonesia

Sistem blacklist rental terpercaya di Indonesia untuk melindungi bisnis rental dari pelanggan bermasalah.

## Fitur Utama

- **Pencarian Blacklist**: Cek data pelanggan bermasalah dengan NIK atau nama
- **Dashboard Admin**: Kelola laporan blacklist dengan mudah
- **Data Tersensor**: Data publik ditampilkan dengan sensor untuk privasi
- **Validasi Otomatis**: Laporan tervalidasi otomatis jika ada 2+ pelapor berbeda
- **Responsive Design**: Tampilan modern dan mobile-friendly

## Teknologi

- **Backend**: Laravel 11
- **Frontend**: Blade Templates + TailwindCSS
- **Database**: MySQL
- **JavaScript**: jQuery + AJAX
- **Authentication**: Laravel Breeze

## Instalasi

1. Clone repository
```bash
git clone <repository-url>
cd rental-blacklist
```

2. Install dependencies
```bash
composer install
npm install
```

3. Setup environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Konfigurasi database di `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rental_blacklist
DB_USERNAME=root
DB_PASSWORD=mysql
```

5. Jalankan migrasi
```bash
php artisan migrate
```

6. Build assets
```bash
npm run build
```

7. Jalankan server
```bash
php artisan serve
```

## Penggunaan

### Untuk Pengguna Umum
- Akses halaman utama untuk mencari data blacklist
- Data ditampilkan dengan sensor untuk melindungi privasi
- Beli kredit untuk melihat data lengkap

### Untuk Pengusaha Rental
- Daftar gratis untuk akses penuh
- Tambah laporan pelanggan bermasalah
- Kelola data laporan Anda
- Lihat data lengkap tanpa sensor

## Kontribusi

1. Fork repository
2. Buat branch fitur (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## Lisensi

Distributed under the MIT License. See `LICENSE` for more information.

## Kontak

Email: support@rentalguard.id
Website: https://rentalguard.id
