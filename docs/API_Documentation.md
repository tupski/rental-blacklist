# Dokumentasi API Rental Blacklist

## Base URL
```
http://localhost:8000/api
```

## Authentication
Untuk endpoint yang memerlukan autentikasi, gunakan Laravel Sanctum token di header:
```
Authorization: Bearer {your_token}
```

## Rate Limiting
- Public API: 100 requests per minute
- Authenticated API: No limit

---

## 1. Public API Endpoints

### 1.1 Search Blacklist
**Endpoint:** `GET /v1/search`

**Description:** Mencari data blacklist berdasarkan NIK atau nama

**Parameters:**
- `q` (required): Query pencarian (minimal 3 karakter)
- `limit` (optional): Jumlah hasil maksimal (1-100, default: 10)

**Example Request:**
```bash
GET /api/v1/search?q=John&limit=5
```

**Example Response (Success):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "nama_lengkap": "J***n D*e",
            "nik": "3201****1234",
            "no_hp": "0812****89",
            "jenis_rental": "Motor",
            "jenis_laporan": ["Tidak Mengembalikan", "Merusak Kendaraan"],
            "tanggal_kejadian": "2024-01-15",
            "jumlah_laporan": 2,
            "pelapor": "Rental ABC",
            "created_at": "2024-01-16 10:30:00"
        }
    ],
    "total": 1,
    "query": "John"
}
```

### 1.2 Get Blacklist Detail
**Endpoint:** `GET /v1/blacklist/{id}`

**Description:** Mendapatkan detail data blacklist (data tersensor untuk public)

**Example Request:**
```bash
GET /api/v1/blacklist/1
```

**Example Response (Success):**
```json
{
    "success": true,
    "message": "For full access, please register as rental business (FREE) or purchase credits.",
    "data": {
        "id": 1,
        "nama_lengkap": "J***n D*e",
        "nik": "3201****1234",
        "no_hp": "0812****89",
        "jenis_rental": "Motor",
        "jenis_laporan": ["Tidak Mengembalikan", "Merusak Kendaraan"],
        "tanggal_kejadian": "2024-01-15",
        "jumlah_laporan": 2,
        "pelapor": "Rental ABC",
        "created_at": "2024-01-16 10:30:00"
    }
}
```

**Example Response (Not Found):**
```json
{
    "success": false,
    "message": "Data not found"
}
```

### 1.3 Get Statistics
**Endpoint:** `GET /v1/stats`

**Description:** Mendapatkan statistik umum sistem

**Example Request:**
```bash
GET /api/v1/stats
```

**Example Response:**
```json
{
    "success": true,
    "data": {
        "total_laporan": 150,
        "total_pelanggan_bermasalah": 120,
        "rental_terdaftar": 25,
        "laporan_bulan_ini": 12
    }
}
```

---

## 2. Authenticated API Endpoints

### 2.1 Get All Blacklist Data
**Endpoint:** `GET /v1/blacklist`

**Description:** Mendapatkan semua data blacklist dengan pagination (hanya untuk user yang sudah login)

**Headers:**
```
Authorization: Bearer {your_token}
```

**Parameters:**
- `page` (optional): Halaman (default: 1)
- `limit` (optional): Jumlah data per halaman (1-100, default: 15)
- `search` (optional): Query pencarian (minimal 3 karakter)

**Example Request:**
```bash
GET /api/v1/blacklist?page=1&limit=10&search=motor
Authorization: Bearer 1|abc123def456...
```

**Example Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "nik": "3201234567891234",
            "nama_lengkap": "John Doe",
            "jenis_kelamin": "Laki-laki",
            "no_hp": "081234567890",
            "alamat": "Jl. Merdeka No. 123, Jakarta",
            "jenis_rental": "Motor",
            "jenis_laporan": ["Tidak Mengembalikan", "Merusak Kendaraan"],
            "status_validitas": "Valid",
            "kronologi": "Pelanggan menyewa motor selama 3 hari...",
            "bukti": ["foto1.jpg", "foto2.jpg"],
            "tanggal_kejadian": "2024-01-15",
            "user_id": 1,
            "created_at": "2024-01-16T10:30:00.000000Z",
            "updated_at": "2024-01-16T10:30:00.000000Z",
            "user": {
                "id": 1,
                "name": "Rental ABC"
            }
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 10,
        "total": 45
    }
}
```

### 2.2 Create New Blacklist Data
**Endpoint:** `POST /v1/blacklist`

**Description:** Menambahkan data blacklist baru (hanya untuk user yang sudah login)

**Headers:**
```
Authorization: Bearer {your_token}
Content-Type: application/json
```

**Request Body:**
```json
{
    "nik": "3201234567891234",
    "nama_lengkap": "Jane Smith",
    "jenis_kelamin": "Perempuan",
    "no_hp": "081987654321",
    "alamat": "Jl. Sudirman No. 456, Bandung",
    "jenis_rental": "Mobil",
    "jenis_laporan": ["Tidak Mengembalikan", "Hilang Kontak"],
    "kronologi": "Pelanggan menyewa mobil Avanza selama 1 minggu untuk keperluan mudik. Setelah masa sewa berakhir, pelanggan tidak mengembalikan kendaraan dan tidak dapat dihubungi melalui nomor yang terdaftar.",
    "tanggal_kejadian": "2024-01-20"
}
```

**Example Request:**
```bash
POST /api/v1/blacklist
Authorization: Bearer 1|abc123def456...
Content-Type: application/json

{
    "nik": "3201234567891234",
    "nama_lengkap": "Jane Smith",
    "jenis_kelamin": "Perempuan",
    "no_hp": "081987654321",
    "alamat": "Jl. Sudirman No. 456, Bandung",
    "jenis_rental": "Mobil",
    "jenis_laporan": ["Tidak Mengembalikan", "Hilang Kontak"],
    "kronologi": "Pelanggan menyewa mobil Avanza selama 1 minggu untuk keperluan mudik. Setelah masa sewa berakhir, pelanggan tidak mengembalikan kendaraan dan tidak dapat dihubungi melalui nomor yang terdaftar.",
    "tanggal_kejadian": "2024-01-20"
}
```

**Example Response (Success):**
```json
{
    "success": true,
    "message": "Blacklist data created successfully",
    "data": {
        "id": 2,
        "nik": "3201234567891234",
        "nama_lengkap": "Jane Smith",
        "jenis_kelamin": "Perempuan",
        "no_hp": "081987654321",
        "alamat": "Jl. Sudirman No. 456, Bandung",
        "jenis_rental": "Mobil",
        "jenis_laporan": ["Tidak Mengembalikan", "Hilang Kontak"],
        "status_validitas": "Valid",
        "kronologi": "Pelanggan menyewa mobil Avanza selama 1 minggu untuk keperluan mudik. Setelah masa sewa berakhir, pelanggan tidak mengembalikan kendaraan dan tidak dapat dihubungi melalui nomor yang terdaftar.",
        "bukti": null,
        "tanggal_kejadian": "2024-01-20",
        "user_id": 1,
        "created_at": "2024-01-21T14:25:30.000000Z",
        "updated_at": "2024-01-21T14:25:30.000000Z"
    }
}
```

**Example Response (Validation Error):**
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "nik": ["The nik field is required."],
        "nama_lengkap": ["The nama lengkap field is required."],
        "jenis_laporan": ["The jenis laporan field must be an array."]
    }
}
```

### 2.3 Update Blacklist Data
**Endpoint:** `PUT /v1/blacklist/{id}`

**Description:** Mengupdate data blacklist (hanya pemilik data yang bisa mengupdate)

**Headers:**
```
Authorization: Bearer {your_token}
Content-Type: application/json
```

**Request Body (semua field opsional):**
```json
{
    "kronologi": "Update: Pelanggan akhirnya mengembalikan kendaraan setelah 2 minggu terlambat dengan kondisi rusak pada bagian bemper depan.",
    "jenis_laporan": ["Tidak Mengembalikan", "Merusak Kendaraan", "Terlambat Mengembalikan"]
}
```

**Example Request:**
```bash
PUT /api/v1/blacklist/2
Authorization: Bearer 1|abc123def456...
Content-Type: application/json

{
    "kronologi": "Update: Pelanggan akhirnya mengembalikan kendaraan setelah 2 minggu terlambat dengan kondisi rusak pada bagian bemper depan.",
    "jenis_laporan": ["Tidak Mengembalikan", "Merusak Kendaraan", "Terlambat Mengembalikan"]
}
```

**Example Response (Success):**
```json
{
    "success": true,
    "message": "Blacklist data updated successfully",
    "data": {
        "id": 2,
        "nik": "3201234567891234",
        "nama_lengkap": "Jane Smith",
        "jenis_kelamin": "Perempuan",
        "no_hp": "081987654321",
        "alamat": "Jl. Sudirman No. 456, Bandung",
        "jenis_rental": "Mobil",
        "jenis_laporan": ["Tidak Mengembalikan", "Merusak Kendaraan", "Terlambat Mengembalikan"],
        "status_validitas": "Valid",
        "kronologi": "Update: Pelanggan akhirnya mengembalikan kendaraan setelah 2 minggu terlambat dengan kondisi rusak pada bagian bemper depan.",
        "bukti": null,
        "tanggal_kejadian": "2024-01-20",
        "user_id": 1,
        "created_at": "2024-01-21T14:25:30.000000Z",
        "updated_at": "2024-01-21T15:10:45.000000Z"
    }
}
```

**Example Response (Unauthorized):**
```json
{
    "success": false,
    "message": "Unauthorized"
}
```

**Example Response (Not Found):**
```json
{
    "success": false,
    "message": "Data not found"
}
```

### 2.4 Delete Blacklist Data
**Endpoint:** `DELETE /v1/blacklist/{id}`

**Description:** Menghapus data blacklist (hanya pemilik data yang bisa menghapus)

**Headers:**
```
Authorization: Bearer {your_token}
```

**Example Request:**
```bash
DELETE /api/v1/blacklist/2
Authorization: Bearer 1|abc123def456...
```

**Example Response (Success):**
```json
{
    "success": true,
    "message": "Blacklist data deleted successfully"
}
```

**Example Response (Unauthorized):**
```json
{
    "success": false,
    "message": "Unauthorized"
}
```

**Example Response (Not Found):**
```json
{
    "success": false,
    "message": "Data not found"
}
```

---

## 3. Authentication

### 3.1 Get User Token
**Endpoint:** `POST /login` (Web route, bukan API)

**Description:** Login untuk mendapatkan token API

**Request Body:**
```json
{
    "email": "rental@example.com",
    "password": "password123"
}
```

**Example Response (Success):**
```json
{
    "success": true,
    "token": "1|abc123def456ghi789jkl012mno345pqr678stu901vwx234yz",
    "user": {
        "id": 1,
        "name": "Rental ABC",
        "email": "rental@example.com"
    }
}
```

### 3.2 Get Current User
**Endpoint:** `GET /user`

**Description:** Mendapatkan informasi user yang sedang login

**Headers:**
```
Authorization: Bearer {your_token}
```

**Example Request:**
```bash
GET /api/user
Authorization: Bearer 1|abc123def456...
```

**Example Response:**
```json
{
    "id": 1,
    "name": "Rental ABC",
    "email": "rental@example.com",
    "email_verified_at": "2024-01-01T00:00:00.000000Z",
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
}
```

---

## 4. Error Responses

### 4.1 Validation Error (422)
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "field_name": ["Error message 1", "Error message 2"]
    }
}
```

### 4.2 Unauthorized (401)
```json
{
    "message": "Unauthenticated."
}
```

### 4.3 Forbidden (403)
```json
{
    "success": false,
    "message": "Unauthorized"
}
```

### 4.4 Not Found (404)
```json
{
    "success": false,
    "message": "Data not found"
}
```

### 4.5 Rate Limit Exceeded (429)
```json
{
    "message": "Too Many Attempts."
}
```

### 4.6 Server Error (500)
```json
{
    "message": "Server Error"
}
```

---

## 5. Field Validation Rules

### 5.1 Create/Update Blacklist
- `nik`: Required, string, exactly 16 characters
- `nama_lengkap`: Required, string, max 255 characters
- `jenis_kelamin`: Required, enum: "Laki-laki" or "Perempuan"
- `no_hp`: Required, string, max 20 characters
- `alamat`: Required, string
- `jenis_rental`: Required, string, max 100 characters
- `jenis_laporan`: Required, array, minimum 1 item
- `kronologi`: Required, string
- `tanggal_kejadian`: Required, date, must be today or earlier

### 5.2 Search Parameters
- `q`: Required, string, minimum 3 characters
- `limit`: Optional, integer, between 1-100
- `page`: Optional, integer, minimum 1

---

## 6. Data Censoring Rules

### 6.1 Public Access (Tanpa Authentication)
- **Nama**: Karakter tengah disensor dengan `*` (contoh: "John Doe" → "J**n D*e")
- **NIK**: 8 karakter tengah disensor (contoh: "3201234567891234" → "3201****1234")
- **No HP**: Karakter tengah disensor, hanya 4 digit awal dan 2 digit akhir yang terlihat (contoh: "081234567890" → "0812****90")

### 6.2 Authenticated Access
- Semua data ditampilkan lengkap tanpa sensor
- User hanya bisa mengupdate/menghapus data yang mereka buat sendiri

---

## 7. Postman Collection

### 7.1 Environment Variables
Buat environment di Postman dengan variabel:
- `base_url`: `http://localhost:8000/api`
- `token`: `Bearer {your_token_here}`

### 7.2 Headers Template
Untuk endpoint yang memerlukan authentication:
```
Authorization: {{token}}
Content-Type: application/json
```

---

## 8. Contoh Penggunaan dengan cURL

### 8.1 Search Blacklist (Public)
```bash
curl -X GET "http://localhost:8000/api/v1/search?q=John&limit=5" \
  -H "Accept: application/json"
```

### 8.2 Get Statistics (Public)
```bash
curl -X GET "http://localhost:8000/api/v1/stats" \
  -H "Accept: application/json"
```

### 8.3 Get All Blacklist (Authenticated)
```bash
curl -X GET "http://localhost:8000/api/v1/blacklist?page=1&limit=10" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer 1|abc123def456..."
```

### 8.4 Create New Blacklist (Authenticated)
```bash
curl -X POST "http://localhost:8000/api/v1/blacklist" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer 1|abc123def456..." \
  -d '{
    "nik": "3201234567891234",
    "nama_lengkap": "Jane Smith",
    "jenis_kelamin": "Perempuan",
    "no_hp": "081987654321",
    "alamat": "Jl. Sudirman No. 456, Bandung",
    "jenis_rental": "Mobil",
    "jenis_laporan": ["Tidak Mengembalikan", "Hilang Kontak"],
    "kronologi": "Pelanggan menyewa mobil Avanza selama 1 minggu untuk keperluan mudik. Setelah masa sewa berakhir, pelanggan tidak mengembalikan kendaraan dan tidak dapat dihubungi melalui nomor yang terdaftar.",
    "tanggal_kejadian": "2024-01-20"
  }'
```

### 8.5 Update Blacklist (Authenticated)
```bash
curl -X PUT "http://localhost:8000/api/v1/blacklist/2" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer 1|abc123def456..." \
  -d '{
    "kronologi": "Update: Pelanggan akhirnya mengembalikan kendaraan setelah 2 minggu terlambat dengan kondisi rusak pada bagian bemper depan.",
    "jenis_laporan": ["Tidak Mengembalikan", "Merusak Kendaraan", "Terlambat Mengembalikan"]
  }'
```

### 8.6 Delete Blacklist (Authenticated)
```bash
curl -X DELETE "http://localhost:8000/api/v1/blacklist/2" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer 1|abc123def456..."
```

---

## 9. Tips dan Best Practices

### 9.1 Rate Limiting
- Public API dibatasi 100 request per menit
- Gunakan parameter `limit` untuk mengontrol jumlah data yang dikembalikan
- Implementasikan retry logic dengan exponential backoff jika terkena rate limit

### 9.2 Pagination
- Gunakan parameter `page` dan `limit` untuk pagination
- Response akan menyertakan informasi pagination di field `pagination`
- Default limit adalah 15, maksimal 100 per request

### 9.3 Search Optimization
- Query pencarian minimal 3 karakter
- Pencarian dilakukan pada field `nik` dan `nama_lengkap`
- Gunakan kata kunci yang spesifik untuk hasil yang lebih akurat

### 9.4 Error Handling
- Selalu cek field `success` dalam response
- Handle berbagai HTTP status code (401, 403, 404, 422, 429, 500)
- Implementasikan logging untuk debugging

### 9.5 Security
- Jangan pernah expose token API di client-side code
- Gunakan HTTPS di production
- Regenerate token secara berkala
- Validate semua input data

### 9.6 Data Privacy
- Data publik sudah tersensor sesuai aturan privacy
- Untuk akses data lengkap, diperlukan authentication
- User hanya bisa mengakses/mengubah data yang mereka buat

---

## 10. Status Codes

| Code | Description |
|------|-------------|
| 200  | OK - Request berhasil |
| 201  | Created - Data berhasil dibuat |
| 401  | Unauthorized - Token tidak valid atau tidak ada |
| 403  | Forbidden - Tidak memiliki akses ke resource |
| 404  | Not Found - Data tidak ditemukan |
| 422  | Unprocessable Entity - Validation error |
| 429  | Too Many Requests - Rate limit exceeded |
| 500  | Internal Server Error - Server error |

---

## 11. Changelog

### Version 1.0.0 (Current)
- Initial API release
- Public search endpoint
- Authenticated CRUD operations
- Data censoring for public access
- Rate limiting implementation
- Statistics endpoint
