@extends('layouts.main')

@section('title', 'Edit Laporan Blacklist')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('dashboard.blacklist.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-edit text-green-600 mr-3"></i>
                Edit Laporan Blacklist
            </h1>
        </div>
        <p class="text-gray-600">Edit informasi laporan blacklist</p>
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
            @method('PUT')
            
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
                            value="{{ $blacklist->nama_lengkap }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
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
                            value="{{ $blacklist->nik }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
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
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        >
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L" {{ $blacklist->jenis_kelamin === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ $blacklist->jenis_kelamin === 'P' ? 'selected' : '' }}>Perempuan</option>
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
                            value="{{ $blacklist->no_hp }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
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
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="Alamat lengkap"
                    >{{ $blacklist->alamat }}</textarea>
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
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        >
                            <option value="">Pilih Jenis Rental</option>
                            <option value="Mobil" {{ $blacklist->jenis_rental === 'Mobil' ? 'selected' : '' }}>Mobil</option>
                            <option value="Motor" {{ $blacklist->jenis_rental === 'Motor' ? 'selected' : '' }}>Motor</option>
                            <option value="Kamera" {{ $blacklist->jenis_rental === 'Kamera' ? 'selected' : '' }}>Kamera</option>
                            <option value="Alat Elektronik" {{ $blacklist->jenis_rental === 'Alat Elektronik' ? 'selected' : '' }}>Alat Elektronik</option>
                            <option value="Lainnya" {{ $blacklist->jenis_rental === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
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
                            value="{{ $blacklist->tanggal_kejadian->format('Y-m-d') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
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
                        <input type="checkbox" name="jenis_laporan[]" value="percobaan_penipuan" 
                               {{ in_array('percobaan_penipuan', $blacklist->jenis_laporan) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        <span class="ml-2 text-sm text-gray-700">Percobaan Penipuan</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="jenis_laporan[]" value="penipuan" 
                               {{ in_array('penipuan', $blacklist->jenis_laporan) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        <span class="ml-2 text-sm text-gray-700">Penipuan</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="jenis_laporan[]" value="tidak_mengembalikan_barang" 
                               {{ in_array('tidak_mengembalikan_barang', $blacklist->jenis_laporan) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        <span class="ml-2 text-sm text-gray-700">Tidak Mengembalikan Barang</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="jenis_laporan[]" value="identitas_palsu" 
                               {{ in_array('identitas_palsu', $blacklist->jenis_laporan) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        <span class="ml-2 text-sm text-gray-700">Identitas Palsu</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="jenis_laporan[]" value="sindikat" 
                               {{ in_array('sindikat', $blacklist->jenis_laporan) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        <span class="ml-2 text-sm text-gray-700">Sindikat</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="jenis_laporan[]" value="merusak_barang" 
                               {{ in_array('merusak_barang', $blacklist->jenis_laporan) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-green-600 focus:ring-green-500">
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
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    placeholder="Ceritakan kronologi kejadian secara detail..."
                >{{ $blacklist->kronologi }}</textarea>
                <div class="text-red-500 text-sm mt-1 hidden" id="kronologi_error"></div>
            </div>

            <!-- Existing Files -->
            @if($blacklist->bukti && count($blacklist->bukti) > 0)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Bukti Saat Ini
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="existingFiles">
                    @foreach($blacklist->bukti as $file)
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg" data-file="{{ $file }}">
                        <div class="flex items-center">
                            <i class="fas fa-file text-gray-500 mr-2"></i>
                            <a href="{{ asset('storage/' . $file) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                                {{ basename($file) }}
                            </a>
                        </div>
                        <button type="button" onclick="removeFile('{{ $file }}')" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    </div>
                    @endforeach
                </div>
                <input type="hidden" name="removed_files" id="removedFiles" value="">
            </div>
            @endif

            <!-- New Files -->
            <div>
                <label for="bukti" class="block text-sm font-medium text-gray-700 mb-2">
                    Tambah Bukti Baru (Opsional)
                </label>
                <input 
                    type="file" 
                    id="bukti" 
                    name="bukti[]" 
                    multiple
                    accept=".jpg,.jpeg,.png,.pdf,.mp4,.avi,.mov"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
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
                    class="px-6 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition duration-200"
                >
                    <i class="fas fa-save mr-2"></i>
                    Update Laporan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let removedFiles = [];

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

    // Remove file function
    window.removeFile = function(filename) {
        if (confirm('Hapus file ini?')) {
            removedFiles.push(filename);
            $('#removedFiles').val(JSON.stringify(removedFiles));
            $(`[data-file="${filename}"]`).remove();
        }
    };

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
        
        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Mengupdate...');

        $.ajax({
            url: '{{ route("dashboard.blacklist.update", $blacklist->id) }}',
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
                    alert('Terjadi kesalahan saat mengupdate data');
                }
            },
            complete: function() {
                $('#submitBtn').prop('disabled', false).html('<i class="fas fa-save mr-2"></i>Update Laporan');
            }
        });
    });
});
</script>
@endpush
