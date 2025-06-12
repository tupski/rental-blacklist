@extends('layouts.main')

@section('title', 'Tambah Laporan Blacklist')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('dashboard.blacklist.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-plus text-red-600 mr-3"></i>
                Tambah Laporan Blacklist
            </h1>
        </div>
        <p class="text-gray-600">Laporkan pelanggan yang bermasalah untuk melindungi sesama pengusaha rental</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-form text-blue-500 mr-2"></i>
                Informasi Laporan
            </h3>
        </div>
        
        <form id="blacklistForm" class="p-6 space-y-6" enctype="multipart/form-data">
            @csrf
            
            <!-- Data Pribadi -->
            <div>
                <h4 class="text-md font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">
                    <i class="fas fa-user text-green-500 mr-2"></i>
                    Data Pribadi
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="nama_lengkap" 
                            name="nama_lengkap" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                            placeholder="Masukkan nama lengkap"
                        >
                        <div class="text-red-500 text-sm mt-1 hidden" id="nama_lengkap_error"></div>
                    </div>
                    
                    <div>
                        <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">
                            NIK <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="nik" 
                            name="nik" 
                            required
                            maxlength="16"
                            pattern="[0-9]{16}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                            placeholder="16 digit NIK"
                        >
                        <div class="text-red-500 text-sm mt-1 hidden" id="nik_error"></div>
                    </div>
                    
                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="jenis_kelamin" 
                            name="jenis_kelamin" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        >
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                        <div class="text-red-500 text-sm mt-1 hidden" id="jenis_kelamin_error"></div>
                    </div>
                    
                    <div>
                        <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-2">
                            No HP <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="tel" 
                            id="no_hp" 
                            name="no_hp" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                            placeholder="08xxxxxxxxxx"
                        >
                        <div class="text-red-500 text-sm mt-1 hidden" id="no_hp_error"></div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        id="alamat" 
                        name="alamat" 
                        required
                        rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        placeholder="Alamat lengkap"
                    ></textarea>
                    <div class="text-red-500 text-sm mt-1 hidden" id="alamat_error"></div>
                </div>
            </div>

            <!-- Data Rental -->
            <div>
                <h4 class="text-md font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">
                    <i class="fas fa-car text-blue-500 mr-2"></i>
                    Data Rental
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="jenis_rental" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Rental <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="jenis_rental" 
                            name="jenis_rental" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        >
                            <option value="">Pilih Jenis Rental</option>
                            <option value="Mobil">Mobil</option>
                            <option value="Motor">Motor</option>
                            <option value="Kamera">Kamera</option>
                            <option value="Alat Elektronik">Alat Elektronik</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        <div class="text-red-500 text-sm mt-1 hidden" id="jenis_rental_error"></div>
                    </div>
                    
                    <div>
                        <label for="tanggal_kejadian" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Kejadian <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            id="tanggal_kejadian" 
                            name="tanggal_kejadian" 
                            required
                            max="{{ date('Y-m-d') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        >
                        <div class="text-red-500 text-sm mt-1 hidden" id="tanggal_kejadian_error"></div>
                    </div>
                </div>
            </div>

            <!-- Jenis Laporan -->
            <div>
                <h4 class="text-md font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">
                    <i class="fas fa-exclamation-triangle text-orange-500 mr-2"></i>
                    Jenis Laporan
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="jenis_laporan[]" value="percobaan_penipuan" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <span class="ml-2 text-sm text-gray-700">Percobaan Penipuan</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="jenis_laporan[]" value="penipuan" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <span class="ml-2 text-sm text-gray-700">Penipuan</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="jenis_laporan[]" value="tidak_mengembalikan_barang" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <span class="ml-2 text-sm text-gray-700">Tidak Mengembalikan Barang</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="jenis_laporan[]" value="identitas_palsu" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <span class="ml-2 text-sm text-gray-700">Identitas Palsu</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="jenis_laporan[]" value="sindikat" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <span class="ml-2 text-sm text-gray-700">Sindikat</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="jenis_laporan[]" value="merusak_barang" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <span class="ml-2 text-sm text-gray-700">Merusak Barang</span>
                    </label>
                </div>
                <div class="text-red-500 text-sm mt-1 hidden" id="jenis_laporan_error"></div>
            </div>

            <!-- Kronologi -->
            <div>
                <label for="kronologi" class="block text-sm font-medium text-gray-700 mb-2">
                    Kronologi Kejadian <span class="text-red-500">*</span>
                </label>
                <textarea 
                    id="kronologi" 
                    name="kronologi" 
                    required
                    rows="5"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                    placeholder="Ceritakan kronologi kejadian secara detail..."
                ></textarea>
                <div class="text-red-500 text-sm mt-1 hidden" id="kronologi_error"></div>
            </div>

            <!-- Bukti -->
            <div>
                <label for="bukti" class="block text-sm font-medium text-gray-700 mb-2">
                    Bukti (Opsional)
                </label>
                <input 
                    type="file" 
                    id="bukti" 
                    name="bukti[]" 
                    multiple
                    accept=".jpg,.jpeg,.png,.pdf,.mp4,.avi,.mov"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                >
                <p class="text-sm text-gray-500 mt-1">
                    Format: JPG, PNG, PDF, MP4, AVI, MOV. Maksimal 10MB per file.
                </p>
                <div class="text-red-500 text-sm mt-1 hidden" id="bukti_error"></div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('dashboard.blacklist.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition duration-200">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button 
                    type="submit" 
                    id="submitBtn"
                    class="px-6 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition duration-200"
                >
                    <i class="fas fa-save mr-2"></i>
                    Simpan Laporan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // NIK validation
    $('#nik').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        $(this).val(value);
    });

    // Phone number validation
    $('#no_hp').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        $(this).val(value);
    });

    // Form submission
    $('#blacklistForm').on('submit', function(e) {
        e.preventDefault();
        
        // Clear previous errors
        $('.text-red-500').addClass('hidden');
        
        // Validate jenis laporan
        if ($('input[name="jenis_laporan[]"]:checked').length === 0) {
            $('#jenis_laporan_error').text('Pilih minimal satu jenis laporan').removeClass('hidden');
            return;
        }

        const formData = new FormData(this);
        
        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...');

        $.ajax({
            url: '{{ route("dashboard.blacklist.store") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    window.location.href = '{{ route("dashboard.blacklist.index") }}';
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        $(`#${key}_error`).text(errors[key][0]).removeClass('hidden');
                    });
                } else {
                    alert('Terjadi kesalahan saat menyimpan data');
                }
            },
            complete: function() {
                $('#submitBtn').prop('disabled', false).html('<i class="fas fa-save mr-2"></i>Simpan Laporan');
            }
        });
    });
});
</script>
@endpush
