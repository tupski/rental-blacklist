#!/bin/bash

# Script untuk testing API Rental Blacklist
# Pastikan server Laravel sudah berjalan di http://localhost:8000

BASE_URL="http://localhost:8000/api"
TOKEN="" # Isi dengan token yang valid untuk testing authenticated endpoints

echo "=== Testing Rental Blacklist API ==="
echo "Base URL: $BASE_URL"
echo ""

# Function untuk print separator
print_separator() {
    echo "=================================================="
}

# Function untuk print test header
print_test() {
    echo ""
    print_separator
    echo "TEST: $1"
    print_separator
}

# Function untuk test API endpoint
test_endpoint() {
    local method=$1
    local endpoint=$2
    local data=$3
    local auth=$4
    
    echo "Request: $method $endpoint"
    
    if [ "$auth" = "true" ]; then
        if [ -z "$TOKEN" ]; then
            echo "❌ ERROR: Token tidak diset. Silakan isi variabel TOKEN di script ini."
            return
        fi
        if [ -n "$data" ]; then
            curl -s -X $method "$BASE_URL$endpoint" \
                -H "Accept: application/json" \
                -H "Content-Type: application/json" \
                -H "Authorization: Bearer $TOKEN" \
                -d "$data" | jq '.'
        else
            curl -s -X $method "$BASE_URL$endpoint" \
                -H "Accept: application/json" \
                -H "Authorization: Bearer $TOKEN" | jq '.'
        fi
    else
        if [ -n "$data" ]; then
            curl -s -X $method "$BASE_URL$endpoint" \
                -H "Accept: application/json" \
                -H "Content-Type: application/json" \
                -d "$data" | jq '.'
        else
            curl -s -X $method "$BASE_URL$endpoint" \
                -H "Accept: application/json" | jq '.'
        fi
    fi
    echo ""
}

# Check if jq is installed
if ! command -v jq &> /dev/null; then
    echo "❌ ERROR: jq tidak terinstall. Install dengan: sudo apt-get install jq (Ubuntu) atau brew install jq (macOS)"
    exit 1
fi

# Check if server is running
if ! curl -s "$BASE_URL/v1/stats" > /dev/null; then
    echo "❌ ERROR: Server tidak dapat diakses di $BASE_URL"
    echo "Pastikan server Laravel sudah berjalan dengan: php artisan serve"
    exit 1
fi

echo "✅ Server dapat diakses"

# =============================================================================
# PUBLIC API TESTS
# =============================================================================

print_test "1. Get Statistics (Public)"
test_endpoint "GET" "/v1/stats"

print_test "2. Search Blacklist - Valid Query (Public)"
test_endpoint "GET" "/v1/search?q=John&limit=5"

print_test "3. Search Blacklist - Short Query (Should Fail)"
test_endpoint "GET" "/v1/search?q=ab"

print_test "4. Get Blacklist Detail (Public - Censored Data)"
test_endpoint "GET" "/v1/blacklist/1"

print_test "5. Get Non-existent Blacklist (Should Return 404)"
test_endpoint "GET" "/v1/blacklist/999999"

# =============================================================================
# AUTHENTICATED API TESTS
# =============================================================================

if [ -z "$TOKEN" ]; then
    echo ""
    print_separator
    echo "⚠️  WARNING: Token tidak diset."
    echo "Untuk testing authenticated endpoints, silakan:"
    echo "1. Login ke aplikasi web"
    echo "2. Generate API token"
    echo "3. Edit script ini dan isi variabel TOKEN"
    echo "4. Jalankan script lagi"
    print_separator
    exit 0
fi

print_test "6. Get Current User (Authenticated)"
test_endpoint "GET" "/user" "" true

print_test "7. Get All Blacklist (Authenticated)"
test_endpoint "GET" "/v1/blacklist?page=1&limit=3" "" true

print_test "8. Create New Blacklist (Authenticated)"
create_data='{
    "nik": "3301234567891234",
    "nama_lengkap": "Test User API",
    "jenis_kelamin": "Laki-laki",
    "no_hp": "081234567890",
    "alamat": "Jl. Test API No. 123, Jakarta",
    "jenis_rental": "Motor",
    "jenis_laporan": ["Tidak Mengembalikan"],
    "kronologi": "Data test untuk API testing. Pelanggan tidak mengembalikan kendaraan sesuai jadwal.",
    "tanggal_kejadian": "2024-01-20"
}'
test_endpoint "POST" "/v1/blacklist" "$create_data" true

print_test "9. Create Blacklist with Invalid Data (Should Fail)"
invalid_data='{
    "nik": "123",
    "nama_lengkap": "",
    "jenis_kelamin": "Invalid"
}'
test_endpoint "POST" "/v1/blacklist" "$invalid_data" true

print_test "10. Search with Authentication (Full Data)"
test_endpoint "GET" "/v1/blacklist?search=Test&limit=5" "" true

# Note: Update dan Delete test memerlukan ID yang valid
# Uncomment dan sesuaikan ID jika diperlukan

# print_test "11. Update Blacklist (Authenticated)"
# update_data='{
#     "kronologi": "Update: Data telah diverifikasi dan dikonfirmasi.",
#     "jenis_laporan": ["Tidak Mengembalikan", "Terlambat Mengembalikan"]
# }'
# test_endpoint "PUT" "/v1/blacklist/REPLACE_WITH_VALID_ID" "$update_data" true

# print_test "12. Delete Blacklist (Authenticated)"
# test_endpoint "DELETE" "/v1/blacklist/REPLACE_WITH_VALID_ID" "" true

print_test "Testing Selesai"
echo "✅ Semua test telah dijalankan"
echo ""
echo "Catatan:"
echo "- Untuk test update/delete, uncomment dan sesuaikan ID di script"
echo "- Pastikan database memiliki data sample untuk testing"
echo "- Periksa response untuk memastikan API berfungsi dengan benar"
echo ""
