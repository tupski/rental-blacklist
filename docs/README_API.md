# Panduan Penggunaan API Rental Blacklist

## Daftar Isi
1. [Pengenalan](#pengenalan)
2. [Setup Postman](#setup-postman)
3. [Mendapatkan Token API](#mendapatkan-token-api)
4. [Contoh Penggunaan](#contoh-penggunaan)
5. [Troubleshooting](#troubleshooting)

## Pengenalan

API Rental Blacklist menyediakan akses programatis ke database blacklist pelanggan rental kendaraan. API ini memiliki dua tingkat akses:

- **Public API**: Akses terbatas dengan data tersensor
- **Authenticated API**: Akses penuh untuk user yang sudah login

## Setup Postman

### 1. Import Collection
1. Buka Postman
2. Klik **Import** di pojok kiri atas
3. Pilih file `Rental_Blacklist_API.postman_collection.json`
4. Klik **Import**

### 2. Import Environment
1. Klik ikon gear (⚙️) di pojok kanan atas
2. Klik **Import**
3. Pilih file `Rental_Blacklist_Environment.postman_environment.json`
4. Klik **Import**
5. Pilih environment "Rental Blacklist - Local" dari dropdown

### 3. Setup Environment Variables
1. Klik ikon gear (⚙️) di pojok kanan atas
2. Pilih "Rental Blacklist - Local"
3. Update nilai variabel sesuai kebutuhan:
   - `base_url`: URL API (default: http://localhost:8000/api)
   - `web_url`: URL web aplikasi (default: http://localhost:8000)
   - `token`: Token autentikasi (akan diisi setelah login)

## Mendapatkan Token API

### Cara 1: Melalui Web Interface
1. Buka browser dan akses `http://localhost:8000`
2. Login dengan akun Anda
3. Pergi ke halaman Profile atau API Settings
4. Generate atau copy token API yang tersedia
5. Paste token ke environment variable `token` di Postman dengan format: `Bearer your_token_here`

### Cara 2: Melalui Laravel Tinker (Development)
```bash
php artisan tinker
```

```php
// Buat token untuk user tertentu
$user = App\Models\User::find(1); // Ganti dengan ID user yang diinginkan
$token = $user->createToken('API Token')->plainTextToken;
echo "Bearer " . $token;
```

## Contoh Penggunaan

### 1. Test Public API (Tanpa Authentication)

#### Search Blacklist
```bash
GET {{base_url}}/v1/search?q=John&limit=5
```

#### Get Statistics
```bash
GET {{base_url}}/v1/stats
```

### 2. Test Authenticated API

#### Get All Blacklist
```bash
GET {{base_url}}/v1/blacklist?page=1&limit=10
Authorization: {{token}}
```

#### Create New Blacklist
```bash
POST {{base_url}}/v1/blacklist
Authorization: {{token}}
Content-Type: application/json

{
    "nik": "3201234567891234",
    "nama_lengkap": "Jane Smith",
    "jenis_kelamin": "Perempuan",
    "no_hp": "081987654321",
    "alamat": "Jl. Sudirman No. 456, Bandung",
    "jenis_rental": "Mobil",
    "jenis_laporan": ["Tidak Mengembalikan", "Hilang Kontak"],
    "kronologi": "Pelanggan menyewa mobil Avanza...",
    "tanggal_kejadian": "2024-01-20"
}
```

## Troubleshooting

### Error 401 - Unauthenticated
**Penyebab**: Token tidak valid atau tidak ada
**Solusi**: 
- Pastikan token sudah diset di environment variable
- Pastikan format token benar: `Bearer your_token_here`
- Generate token baru jika diperlukan

### Error 422 - Validation Error
**Penyebab**: Data input tidak sesuai validasi
**Solusi**: 
- Periksa field yang required
- Pastikan format data sesuai (NIK 16 digit, tanggal format YYYY-MM-DD, dll)
- Lihat detail error di response body

### Error 429 - Rate Limit Exceeded
**Penyebab**: Terlalu banyak request dalam waktu singkat
**Solusi**: 
- Tunggu beberapa menit sebelum mencoba lagi
- Kurangi frekuensi request
- Gunakan pagination untuk data besar

### Error 403 - Forbidden
**Penyebab**: Tidak memiliki akses ke resource
**Solusi**: 
- Pastikan Anda adalah pemilik data (untuk update/delete)
- Pastikan user memiliki permission yang sesuai

### Error 404 - Not Found
**Penyebab**: Data tidak ditemukan
**Solusi**: 
- Periksa ID yang digunakan
- Pastikan data masih ada di database

## Tips Penggunaan

1. **Gunakan Environment Variables**: Jangan hardcode URL dan token
2. **Handle Error Response**: Selalu cek status code dan response body
3. **Implement Retry Logic**: Untuk handle rate limiting
4. **Use Pagination**: Untuk data yang banyak
5. **Validate Input**: Sebelum mengirim request
6. **Keep Token Secure**: Jangan expose token di client-side

## Contoh Response Format

### Success Response
```json
{
    "success": true,
    "data": {...},
    "message": "Optional message"
}
```

### Error Response
```json
{
    "success": false,
    "message": "Error message",
    "errors": {...} // Optional validation errors
}
```

## Support

Jika mengalami masalah atau butuh bantuan:
1. Periksa dokumentasi API lengkap di `API_Documentation.md`
2. Cek log aplikasi Laravel
3. Hubungi tim development
