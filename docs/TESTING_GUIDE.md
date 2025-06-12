# 🧪 Testing Guide

Panduan lengkap untuk testing Sistem Blacklist Rental Indonesia.

## 🚀 Quick Start Testing

### 1. Setup Environment
```bash
# Clone dan setup
git clone https://github.com/tupski/rental-blacklist.git
cd rental-blacklist
composer install
npm install && npm run build

# Setup database
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link

# Jalankan server
php artisan serve
```

### 2. Akses Aplikasi
- **URL**: `http://127.0.0.1:8000`
- **Demo Accounts**:
  - Email: `budi@rental.com` | Password: `password`
  - Email: `siti@rental.com` | Password: `password`

## 🔍 Testing Scenarios

### A. Public Search Testing

#### Test Case 1: Pencarian Berhasil
1. **Buka halaman utama** `http://127.0.0.1:8000`
2. **Input pencarian**: "Angga"
3. **Klik tombol Cari**
4. **Expected Result**:
   - Data ditemukan
   - Nama ditampilkan utuh: "Angga Artupas"
   - NIK disensor: "3674*********0001"
   - No HP disensor: "0812******90"
   - Jumlah laporan: 2
   - Status: Valid

#### Test Case 2: Pencarian dengan NIK
1. **Input pencarian**: "3674012345670001"
2. **Klik tombol Cari**
3. **Expected Result**:
   - Data ditemukan
   - NIK ditampilkan utuh: "3674012345670001"
   - Nama disensor: "A***a A******s"
   - Data lain disensor

#### Test Case 3: Pencarian Tidak Ditemukan
1. **Input pencarian**: "DataTidakAda"
2. **Klik tombol Cari**
3. **Expected Result**:
   - Pesan "Data Tidak Ditemukan"
   - Background hijau (kabar baik)

#### Test Case 4: URL Dinamis
1. **Lakukan pencarian** "Angga"
2. **Cek URL browser**: harus berubah ke `?search=Angga`
3. **Refresh halaman**: pencarian tetap ada
4. **Back/Forward browser**: berfungsi normal

### B. Authentication Testing

#### Test Case 5: Login Berhasil
1. **Klik tombol Login**
2. **Input credentials**:
   - Email: `budi@rental.com`
   - Password: `password`
3. **Klik "Masuk ke Dashboard"**
4. **Expected Result**:
   - Redirect ke dashboard
   - Menampilkan nama user
   - Statistik laporan

#### Test Case 6: Login Gagal
1. **Input credentials salah**
2. **Expected Result**:
   - Error message
   - Tetap di halaman login

#### Test Case 7: Register Baru
1. **Klik "Daftar sekarang"**
2. **Isi form registrasi**
3. **Expected Result**:
   - Akun terbuat
   - Auto login
   - Redirect ke dashboard

### C. Dashboard Testing

#### Test Case 8: Dashboard Overview
1. **Login sebagai budi@rental.com**
2. **Cek dashboard elements**:
   - ✅ Statistik cards (4 cards)
   - ✅ Search form
   - ✅ Quick actions
   - ✅ Recent reports table
   - ✅ Mobile responsive

#### Test Case 9: Dashboard Search
1. **Di dashboard, cari "Angga"**
2. **Expected Result**:
   - Data tanpa sensor
   - NIK lengkap: "3674012345670001"
   - Nama lengkap: "Angga Artupas"
   - No HP lengkap: "081234567890"

### D. CRUD Testing

#### Test Case 10: Tambah Laporan
1. **Klik "Tambah Laporan"**
2. **Isi form lengkap**:
   ```
   Nama: John Doe Test
   NIK: 1234567890123456
   Jenis Kelamin: L
   No HP: 081234567890
   Alamat: Jl. Test No. 123
   Jenis Rental: Mobil
   Jenis Laporan: ✅ Penipuan
   Kronologi: Test kronologi...
   Tanggal: 2024-01-01
   ```
3. **Klik "Simpan Laporan"**
4. **Expected Result**:
   - Success message
   - Redirect ke index
   - Data muncul di list

#### Test Case 11: Edit Laporan
1. **Di list laporan, klik edit (hanya laporan sendiri)**
2. **Ubah beberapa field**
3. **Klik "Update Laporan"**
4. **Expected Result**:
   - Data terupdate
   - Success message

#### Test Case 12: Hapus Laporan
1. **Klik tombol hapus (hanya laporan sendiri)**
2. **Konfirmasi hapus**
3. **Expected Result**:
   - Data terhapus
   - Success message

#### Test Case 13: Authorization
1. **Login sebagai user A**
2. **Coba edit laporan user B**
3. **Expected Result**:
   - Error 403 Forbidden
   - Tidak bisa akses

### E. File Upload Testing

#### Test Case 14: Upload Bukti
1. **Saat tambah laporan, upload file**:
   - ✅ JPG (< 10MB)
   - ✅ PNG (< 10MB)
   - ✅ PDF (< 10MB)
2. **Expected Result**:
   - File ter-upload
   - Bisa diakses via link

#### Test Case 15: Validasi File
1. **Upload file tidak valid**:
   - ❌ File > 10MB
   - ❌ Format .txt
   - ❌ Format .exe
2. **Expected Result**:
   - Error validation
   - File tidak ter-upload

### F. Validation Testing

#### Test Case 16: Validasi Otomatis
1. **Buat laporan dengan NIK yang sama dari 2 user berbeda**
2. **Expected Result**:
   - Status berubah ke "Valid"
   - Semua laporan dengan NIK sama jadi "Valid"

#### Test Case 17: Form Validation
1. **Submit form kosong**
2. **Expected Result**:
   - Error messages untuk required fields
   - Form tidak submit

### G. Mobile Testing

#### Test Case 18: Mobile Responsive
1. **Buka di mobile browser atau resize window**
2. **Test semua halaman**:
   - ✅ Homepage responsive
   - ✅ Login/register responsive
   - ✅ Dashboard responsive
   - ✅ Forms responsive
   - ✅ Tables jadi cards di mobile

#### Test Case 19: Touch Interactions
1. **Test di mobile device**:
   - ✅ Buttons mudah di-tap
   - ✅ Forms mudah diisi
   - ✅ Navigation smooth

## 🔧 Performance Testing

### Test Case 20: Load Time
1. **Measure page load times**:
   - Homepage: < 2 seconds
   - Dashboard: < 3 seconds
   - Search results: < 1 second

### Test Case 21: Database Performance
1. **Test dengan banyak data**:
   - Search tetap cepat
   - Pagination berfungsi
   - No N+1 queries

## 🛡️ Security Testing

### Test Case 22: CSRF Protection
1. **Submit form tanpa CSRF token**
2. **Expected Result**: Error 419

### Test Case 23: SQL Injection
1. **Input malicious SQL di search**:
   - `'; DROP TABLE users; --`
2. **Expected Result**: Tidak ada effect, data aman

### Test Case 24: File Upload Security
1. **Upload file PHP/script**
2. **Expected Result**: Ditolak validation

## 📊 Browser Compatibility

### Test Case 25: Cross-Browser
Test di berbagai browser:
- ✅ Chrome (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Edge (latest)

## 🐛 Bug Report Template

Jika menemukan bug, laporkan dengan format:

```markdown
**Bug Description**
Deskripsi singkat bug

**Steps to Reproduce**
1. Buka halaman X
2. Klik tombol Y
3. Input Z
4. Lihat error

**Expected Behavior**
Apa yang seharusnya terjadi

**Actual Behavior**
Apa yang benar-benar terjadi

**Environment**
- OS: Windows 10
- Browser: Chrome 91
- PHP: 8.2
- Laravel: 12

**Screenshots**
Attach jika ada
```

## ✅ Testing Checklist

### Functional Testing
- [ ] Public search dengan sensor
- [ ] Login/logout
- [ ] Dashboard statistics
- [ ] CRUD laporan
- [ ] File upload
- [ ] Validation rules
- [ ] Authorization

### UI/UX Testing
- [ ] Responsive design
- [ ] Mobile navigation
- [ ] Form usability
- [ ] Loading states
- [ ] Error messages

### Security Testing
- [ ] CSRF protection
- [ ] Input validation
- [ ] File upload security
- [ ] Authorization checks

### Performance Testing
- [ ] Page load times
- [ ] Database queries
- [ ] File upload speed
- [ ] Search performance

## 📞 Support

Jika menemukan masalah saat testing:
1. Cek logs: `storage/logs/laravel.log`
2. Cek browser console untuk JS errors
3. Verify database seeder berjalan
4. Restart server jika perlu

---

**Happy Testing! 🧪**
