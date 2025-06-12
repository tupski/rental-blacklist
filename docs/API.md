# 📡 API Documentation

Dokumentasi lengkap API endpoints untuk Sistem Blacklist Rental Indonesia.

## 🔐 Authentication

Sistem menggunakan Laravel Sanctum untuk API authentication (untuk pengembangan future).
Saat ini menggunakan web authentication dengan session.

### Headers Required
```http
Content-Type: application/json
X-CSRF-TOKEN: {csrf_token}
```

## 🌐 Public Endpoints

### 1. Search Blacklist (Public)

**Endpoint**: `POST /search`  
**Description**: Mencari data blacklist dengan data disensor untuk akses publik  
**Authentication**: None

#### Request
```http
POST /search
Content-Type: application/json
X-CSRF-TOKEN: {csrf_token}

{
    "search": "Angga"
}
```

#### Response Success (200)
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "nama_lengkap": "Angga Artupas",
            "nik": "3674*********0001",
            "no_hp": "0812******90",
            "jenis_rental": "Mobil",
            "jenis_laporan": ["penipuan", "tidak_mengembalikan_barang"],
            "tanggal_kejadian": "15/01/2024",
            "jumlah_laporan": 2,
            "pelapor": "Budi Santoso"
        }
    ],
    "total": 1
}
```

#### Response No Data (200)
```json
{
    "success": true,
    "data": [],
    "total": 0
}
```

#### Response Error (422)
```json
{
    "message": "The search field is required.",
    "errors": {
        "search": ["The search field is required."]
    }
}
```

### 2. Get Detail (Public)

**Endpoint**: `GET /detail/{id}`  
**Description**: Mendapatkan detail laporan dengan CTA untuk akses penuh  
**Authentication**: None

#### Response Success (200)
```json
{
    "success": true,
    "message": "Untuk melihat data lengkap, silakan login sebagai pengusaha rental (GRATIS) atau beli kredit untuk akses sekali lihat.",
    "data": {
        "nama_lengkap": "A***a A******s",
        "nik": "3674*********0001",
        "no_hp": "0812******90",
        "jenis_rental": "Mobil",
        "jenis_laporan": ["penipuan", "tidak_mengembalikan_barang"],
        "tanggal_kejadian": "15/01/2024",
        "jumlah_laporan": 2,
        "pelapor": "Budi Santoso"
    }
}
```

## 🔒 Authenticated Endpoints

### 1. Dashboard Search

**Endpoint**: `POST /dashboard/blacklist/search`  
**Description**: Mencari data blacklist tanpa sensor untuk user login  
**Authentication**: Required

#### Request
```http
POST /dashboard/blacklist/search
Content-Type: application/json
X-CSRF-TOKEN: {csrf_token}
Authorization: Bearer {token}

{
    "search": "Angga"
}
```

#### Response Success (200)
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "nama_lengkap": "Angga Artupas",
            "nik": "3674012345670001",
            "no_hp": "081234567890",
            "alamat": "Jl. Merdeka No. 123, Jakarta Pusat",
            "jenis_rental": "Mobil",
            "jenis_laporan": ["penipuan", "tidak_mengembalikan_barang"],
            "status_validitas": "Valid",
            "tanggal_kejadian": "15/01/2024",
            "jumlah_laporan": 2,
            "pelapor": "Budi Santoso",
            "can_edit": false
        }
    ],
    "total": 1
}
```

### 2. Get Blacklist List

**Endpoint**: `GET /dashboard/blacklist`  
**Description**: Mendapatkan daftar laporan blacklist dengan pagination  
**Authentication**: Required

#### Query Parameters
- `search` (optional): Pencarian berdasarkan NIK atau nama
- `jenis_rental` (optional): Filter berdasarkan jenis rental
- `status` (optional): Filter berdasarkan status validitas
- `page` (optional): Nomor halaman

#### Response Success (200)
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "nama_lengkap": "Angga Artupas",
            "nik": "3674012345670001",
            "no_hp": "081234567890",
            "alamat": "Jl. Merdeka No. 123, Jakarta Pusat",
            "jenis_rental": "Mobil",
            "jenis_laporan": ["penipuan", "tidak_mengembalikan_barang"],
            "status_validitas": "Valid",
            "kronologi": "Pelanggan menyewa mobil...",
            "bukti": ["bukti/file1.jpg"],
            "tanggal_kejadian": "2024-01-15",
            "user_id": 1,
            "created_at": "2024-01-16T10:00:00.000000Z",
            "user": {
                "id": 1,
                "name": "Budi Santoso"
            }
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 1,
        "total": 1
    }
}
```

### 3. Create Blacklist Report

**Endpoint**: `POST /dashboard/blacklist`  
**Description**: Membuat laporan blacklist baru  
**Authentication**: Required

#### Request
```http
POST /dashboard/blacklist
Content-Type: multipart/form-data
X-CSRF-TOKEN: {csrf_token}

{
    "nik": "3674012345670001",
    "nama_lengkap": "John Doe",
    "jenis_kelamin": "L",
    "no_hp": "081234567890",
    "alamat": "Jl. Example No. 123",
    "jenis_rental": "Mobil",
    "jenis_laporan": ["penipuan", "tidak_mengembalikan_barang"],
    "kronologi": "Pelanggan menyewa mobil...",
    "tanggal_kejadian": "2024-01-15",
    "bukti": [file1, file2]
}
```

#### Response Success (200)
```json
{
    "success": true,
    "message": "Laporan blacklist berhasil ditambahkan"
}
```

#### Response Validation Error (422)
```json
{
    "message": "The nik field is required.",
    "errors": {
        "nik": ["The nik field is required."],
        "nama_lengkap": ["The nama lengkap field is required."]
    }
}
```

### 4. Get Blacklist Detail

**Endpoint**: `GET /dashboard/blacklist/{id}`  
**Description**: Mendapatkan detail laporan blacklist  
**Authentication**: Required

#### Response Success (200)
```json
{
    "success": true,
    "data": {
        "id": 1,
        "nama_lengkap": "Angga Artupas",
        "nik": "3674012345670001",
        "jenis_kelamin": "L",
        "no_hp": "081234567890",
        "alamat": "Jl. Merdeka No. 123, Jakarta Pusat",
        "jenis_rental": "Mobil",
        "jenis_laporan": ["penipuan", "tidak_mengembalikan_barang"],
        "status_validitas": "Valid",
        "kronologi": "Pelanggan menyewa mobil...",
        "bukti": ["bukti/file1.jpg"],
        "tanggal_kejadian": "2024-01-15",
        "user": {
            "id": 1,
            "name": "Budi Santoso"
        }
    }
}
```

### 5. Update Blacklist Report

**Endpoint**: `PUT /dashboard/blacklist/{id}`  
**Description**: Mengupdate laporan blacklist (hanya pemilik laporan)  
**Authentication**: Required

#### Request
```http
PUT /dashboard/blacklist/1
Content-Type: multipart/form-data
X-CSRF-TOKEN: {csrf_token}

{
    "nik": "3674012345670001",
    "nama_lengkap": "John Doe Updated",
    "jenis_kelamin": "L",
    "no_hp": "081234567890",
    "alamat": "Jl. Example No. 123 Updated",
    "jenis_rental": "Motor",
    "jenis_laporan": ["penipuan"],
    "kronologi": "Updated kronologi...",
    "tanggal_kejadian": "2024-01-15",
    "bukti": [new_file],
    "removed_files": ["bukti/old_file.jpg"]
}
```

#### Response Success (200)
```json
{
    "success": true,
    "message": "Laporan blacklist berhasil diperbarui"
}
```

#### Response Forbidden (403)
```json
{
    "message": "Anda hanya dapat mengedit laporan Anda sendiri"
}
```

### 6. Delete Blacklist Report

**Endpoint**: `DELETE /dashboard/blacklist/{id}`  
**Description**: Menghapus laporan blacklist (hanya pemilik laporan)  
**Authentication**: Required

#### Response Success (200)
```json
{
    "success": true,
    "message": "Laporan blacklist berhasil dihapus"
}
```

#### Response Forbidden (403)
```json
{
    "message": "Anda hanya dapat menghapus laporan Anda sendiri"
}
```

## 📊 Data Models

### Blacklist Model
```json
{
    "id": "integer",
    "nik": "string(16)",
    "nama_lengkap": "string",
    "jenis_kelamin": "enum(L,P)",
    "no_hp": "string(15)",
    "alamat": "text",
    "jenis_rental": "string",
    "jenis_laporan": "array",
    "status_validitas": "enum(Pending,Valid,Invalid)",
    "kronologi": "text",
    "bukti": "array",
    "tanggal_kejadian": "date",
    "user_id": "integer",
    "created_at": "timestamp",
    "updated_at": "timestamp"
}
```

### User Model
```json
{
    "id": "integer",
    "name": "string",
    "email": "string",
    "role": "string",
    "created_at": "timestamp",
    "updated_at": "timestamp"
}
```

## 🔍 Validation Rules

### Search Request
- `search`: required|string|min:3

### Blacklist Create/Update
- `nik`: required|string|size:16
- `nama_lengkap`: required|string|max:255
- `jenis_kelamin`: required|in:L,P
- `no_hp`: required|string|max:15
- `alamat`: required|string
- `jenis_rental`: required|string|max:100
- `jenis_laporan`: required|array|min:1
- `jenis_laporan.*`: in:percobaan_penipuan,penipuan,tidak_mengembalikan_barang,identitas_palsu,sindikat,merusak_barang
- `kronologi`: required|string
- `tanggal_kejadian`: required|date|before_or_equal:today
- `bukti.*`: nullable|file|mimes:jpg,jpeg,png,pdf,mp4,avi,mov|max:10240

## 🚨 Error Codes

| Code | Description |
|------|-------------|
| 200  | Success |
| 401  | Unauthorized |
| 403  | Forbidden |
| 404  | Not Found |
| 422  | Validation Error |
| 500  | Internal Server Error |

## 📝 Rate Limiting

- Public endpoints: 60 requests per minute
- Authenticated endpoints: 120 requests per minute

## 🔄 Future API Enhancements

### Planned Features
- RESTful API dengan Laravel Sanctum
- API versioning
- Webhook notifications
- Bulk operations
- Advanced filtering
- Export endpoints
- Statistics endpoints

---

**API Documentation v1.0** 📡
