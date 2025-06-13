# Contoh Response API Rental Blacklist

## 1. Public API Responses

### 1.1 Search Blacklist - Success
**Request:** `GET /api/v1/search?q=3201&limit=3`

**Response (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "nama_lengkap": "A***d S*****o",
            "nik": "3201****5678",
            "no_hp": "0812****89",
            "jenis_rental": "Motor",
            "jenis_laporan": ["Tidak Mengembalikan"],
            "tanggal_kejadian": "2024-01-15",
            "jumlah_laporan": 1,
            "pelapor": "Rental Jaya Motor",
            "created_at": "2024-01-16 10:30:00"
        },
        {
            "id": 5,
            "nama_lengkap": "B***i P*****a",
            "nik": "3201****9012",
            "no_hp": "0813****45",
            "jenis_rental": "Mobil",
            "jenis_laporan": ["Merusak Kendaraan", "Terlambat Mengembalikan"],
            "tanggal_kejadian": "2024-01-10",
            "jumlah_laporan": 2,
            "pelapor": "Rental Sukses",
            "created_at": "2024-01-11 14:20:00"
        }
    ],
    "total": 2,
    "query": "3201"
}
```

### 1.2 Search Blacklist - No Results
**Request:** `GET /api/v1/search?q=xyz`

**Response (200):**
```json
{
    "success": true,
    "data": [],
    "total": 0,
    "query": "xyz"
}
```

### 1.3 Search Blacklist - Validation Error
**Request:** `GET /api/v1/search?q=ab`

**Response (422):**
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "q": ["The q field must be at least 3 characters."]
    }
}
```

### 1.4 Get Blacklist Detail - Success
**Request:** `GET /api/v1/blacklist/1`

**Response (200):**
```json
{
    "success": true,
    "message": "For full access, please register as rental business (FREE) or purchase credits.",
    "data": {
        "id": 1,
        "nama_lengkap": "A***d S*****o",
        "nik": "3201****5678",
        "no_hp": "0812****89",
        "jenis_rental": "Motor",
        "jenis_laporan": ["Tidak Mengembalikan"],
        "tanggal_kejadian": "2024-01-15",
        "jumlah_laporan": 1,
        "pelapor": "Rental Jaya Motor",
        "created_at": "2024-01-16 10:30:00"
    }
}
```

### 1.5 Get Statistics - Success
**Request:** `GET /api/v1/stats`

**Response (200):**
```json
{
    "success": true,
    "data": {
        "total_laporan": 156,
        "total_pelanggan_bermasalah": 134,
        "rental_terdaftar": 28,
        "laporan_bulan_ini": 15
    }
}
```

## 2. Authenticated API Responses

### 2.1 Get All Blacklist - Success
**Request:** `GET /api/v1/blacklist?page=1&limit=2`

**Response (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "nik": "3201234567891234",
            "nama_lengkap": "Ahmad Suryanto",
            "jenis_kelamin": "Laki-laki",
            "no_hp": "081234567890",
            "alamat": "Jl. Merdeka No. 123, Jakarta Pusat",
            "jenis_rental": "Motor",
            "jenis_laporan": ["Tidak Mengembalikan"],
            "status_validitas": "Valid",
            "kronologi": "Pelanggan menyewa motor Honda Beat selama 3 hari untuk keperluan kerja. Setelah masa sewa berakhir, pelanggan tidak mengembalikan motor dan menghilang.",
            "bukti": ["foto_kontrak.jpg", "foto_ktp.jpg"],
            "tanggal_kejadian": "2024-01-15",
            "user_id": 1,
            "created_at": "2024-01-16T10:30:00.000000Z",
            "updated_at": "2024-01-16T10:30:00.000000Z",
            "user": {
                "id": 1,
                "name": "Rental Jaya Motor"
            }
        },
        {
            "id": 2,
            "nik": "3201987654321098",
            "nama_lengkap": "Budi Pratama",
            "jenis_kelamin": "Laki-laki",
            "no_hp": "081398765432",
            "alamat": "Jl. Sudirman No. 456, Bandung",
            "jenis_rental": "Mobil",
            "jenis_laporan": ["Merusak Kendaraan", "Terlambat Mengembalikan"],
            "status_validitas": "Valid",
            "kronologi": "Pelanggan menyewa mobil Avanza untuk mudik. Kendaraan dikembalikan 1 minggu terlambat dengan kondisi rusak pada bagian bemper dan spion.",
            "bukti": ["foto_kerusakan1.jpg", "foto_kerusakan2.jpg"],
            "tanggal_kejadian": "2024-01-10",
            "user_id": 2,
            "created_at": "2024-01-11T14:20:00.000000Z",
            "updated_at": "2024-01-11T14:20:00.000000Z",
            "user": {
                "id": 2,
                "name": "Rental Sukses"
            }
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 78,
        "per_page": 2,
        "total": 156
    }
}
```

### 2.2 Create Blacklist - Success
**Request:** `POST /api/v1/blacklist`

**Response (201):**
```json
{
    "success": true,
    "message": "Blacklist data created successfully",
    "data": {
        "id": 157,
        "nik": "3301234567891234",
        "nama_lengkap": "Siti Nurhaliza",
        "jenis_kelamin": "Perempuan",
        "no_hp": "081234567890",
        "alamat": "Jl. Diponegoro No. 789, Semarang",
        "jenis_rental": "Motor",
        "jenis_laporan": ["Tidak Mengembalikan", "Hilang Kontak"],
        "status_validitas": "Valid",
        "kronologi": "Pelanggan menyewa motor Yamaha Mio untuk keperluan kuliah. Setelah 2 minggu, pelanggan tidak dapat dihubungi dan motor tidak dikembalikan.",
        "bukti": null,
        "tanggal_kejadian": "2024-01-20",
        "user_id": 1,
        "created_at": "2024-01-21T15:45:30.000000Z",
        "updated_at": "2024-01-21T15:45:30.000000Z"
    }
}
```

### 2.3 Create Blacklist - Validation Error
**Request:** `POST /api/v1/blacklist` (dengan data tidak lengkap)

**Response (422):**
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "nik": ["The nik field is required."],
        "nama_lengkap": ["The nama lengkap field is required."],
        "jenis_kelamin": ["The jenis kelamin field is required."],
        "no_hp": ["The no hp field is required."],
        "alamat": ["The alamat field is required."],
        "jenis_rental": ["The jenis rental field is required."],
        "jenis_laporan": ["The jenis laporan field is required."],
        "kronologi": ["The kronologi field is required."],
        "tanggal_kejadian": ["The tanggal kejadian field is required."]
    }
}
```

### 2.4 Update Blacklist - Success
**Request:** `PUT /api/v1/blacklist/157`

**Response (200):**
```json
{
    "success": true,
    "message": "Blacklist data updated successfully",
    "data": {
        "id": 157,
        "nik": "3301234567891234",
        "nama_lengkap": "Siti Nurhaliza",
        "jenis_kelamin": "Perempuan",
        "no_hp": "081234567890",
        "alamat": "Jl. Diponegoro No. 789, Semarang",
        "jenis_rental": "Motor",
        "jenis_laporan": ["Tidak Mengembalikan", "Hilang Kontak", "Memberikan Data Palsu"],
        "status_validitas": "Valid",
        "kronologi": "Update: Setelah investigasi lebih lanjut, ditemukan bahwa pelanggan memberikan alamat dan nomor telepon palsu. Motor masih belum dikembalikan hingga saat ini.",
        "bukti": null,
        "tanggal_kejadian": "2024-01-20",
        "user_id": 1,
        "created_at": "2024-01-21T15:45:30.000000Z",
        "updated_at": "2024-01-21T16:20:15.000000Z"
    }
}
```

### 2.5 Delete Blacklist - Success
**Request:** `DELETE /api/v1/blacklist/157`

**Response (200):**
```json
{
    "success": true,
    "message": "Blacklist data deleted successfully"
}
```

## 3. Error Responses

### 3.1 Unauthorized (401)
**Request:** Request tanpa token atau token tidak valid

**Response (401):**
```json
{
    "message": "Unauthenticated."
}
```

### 3.2 Forbidden (403)
**Request:** Mencoba mengupdate/menghapus data milik user lain

**Response (403):**
```json
{
    "success": false,
    "message": "Unauthorized"
}
```

### 3.3 Not Found (404)
**Request:** `GET /api/v1/blacklist/999999`

**Response (404):**
```json
{
    "success": false,
    "message": "Data not found"
}
```

### 3.4 Rate Limit Exceeded (429)
**Request:** Terlalu banyak request dalam waktu singkat

**Response (429):**
```json
{
    "message": "Too Many Attempts."
}
```

### 3.5 Server Error (500)
**Request:** Error internal server

**Response (500):**
```json
{
    "message": "Server Error"
}
```
