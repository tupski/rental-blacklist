@extends('layouts.main')

@section('title', 'Edit Laporan Blacklist')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex align-items-center mb-3">
                <a href="{{ route('dashboard.blacklist.index') }}" class="btn btn-outline-secondary me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="display-6 fw-bold text-dark mb-1">
                        <i class="fas fa-edit text-success me-3"></i>
                        Edit Laporan Blacklist
                    </h1>
                    <p class="text-muted mb-0">Edit informasi laporan blacklist</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-0">
            <h5 class="card-title mb-0">
                <i class="fas fa-form text-primary me-2"></i>
                Informasi Laporan
            </h5>
        </div>

        <div class="card-body">
            <form id="blacklistForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Data Pribadi -->
                <div class="mb-5">
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                        <i class="fas fa-user text-success me-2"></i>
                        Data Pribadi
                    </h6>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nama_lengkap" class="form-label fw-medium">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                id="nama_lengkap"
                                name="nama_lengkap"
                                required
                                value="{{ $blacklist->nama_lengkap }}"
                                class="form-control"
                                placeholder="Masukkan nama lengkap"
                            >
                            <div class="invalid-feedback d-none" id="nama_lengkap_error"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="nik" class="form-label fw-medium">
                                NIK <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                id="nik"
                                name="nik"
                                required
                                maxlength="16"
                                pattern="[0-9]{16}"
                                value="{{ $blacklist->nik }}"
                                class="form-control"
                                placeholder="16 digit NIK"
                            >
                            <div class="invalid-feedback d-none" id="nik_error"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="jenis_kelamin" class="form-label fw-medium">
                                Jenis Kelamin <span class="text-danger">*</span>
                            </label>
                            <select
                                id="jenis_kelamin"
                                name="jenis_kelamin"
                                required
                                class="form-select"
                            >
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" {{ $blacklist->jenis_kelamin === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ $blacklist->jenis_kelamin === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            <div class="invalid-feedback d-none" id="jenis_kelamin_error"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="no_hp" class="form-label fw-medium">
                                No HP <span class="text-danger">*</span>
                            </label>
                            <input
                                type="tel"
                                id="no_hp"
                                name="no_hp"
                                required
                                value="{{ $blacklist->no_hp }}"
                                class="form-control"
                                placeholder="08xxxxxxxxxx"
                            >
                            <div class="invalid-feedback d-none" id="no_hp_error"></div>
                        </div>

                        <div class="col-12">
                            <label for="alamat" class="form-label fw-medium">
                                Alamat <span class="text-danger">*</span>
                            </label>
                            <textarea
                                id="alamat"
                                name="alamat"
                                required
                                rows="3"
                                class="form-control"
                                placeholder="Alamat lengkap"
                            >{{ $blacklist->alamat }}</textarea>
                            <div class="invalid-feedback d-none" id="alamat_error"></div>
                        </div>
                    </div>
                </div>

                <!-- Data Rental -->
                <div class="mb-5">
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                        <i class="fas fa-car text-primary me-2"></i>
                        Data Rental
                    </h6>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="jenis_rental" class="form-label fw-medium">
                                Jenis Rental <span class="text-danger">*</span>
                            </label>
                            <select
                                id="jenis_rental"
                                name="jenis_rental"
                                required
                                class="form-select"
                            >
                                <option value="">Pilih Jenis Rental</option>
                                <option value="Mobil" {{ $blacklist->jenis_rental === 'Mobil' ? 'selected' : '' }}>Mobil</option>
                                <option value="Motor" {{ $blacklist->jenis_rental === 'Motor' ? 'selected' : '' }}>Motor</option>
                                <option value="Kamera" {{ $blacklist->jenis_rental === 'Kamera' ? 'selected' : '' }}>Kamera</option>
                                <option value="Alat Elektronik" {{ $blacklist->jenis_rental === 'Alat Elektronik' ? 'selected' : '' }}>Alat Elektronik</option>
                                <option value="Lainnya" {{ $blacklist->jenis_rental === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            <div class="invalid-feedback d-none" id="jenis_rental_error"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="tanggal_kejadian" class="form-label fw-medium">
                                Tanggal Kejadian <span class="text-danger">*</span>
                            </label>
                            <input
                                type="date"
                                id="tanggal_kejadian"
                                name="tanggal_kejadian"
                                required
                                max="{{ date('Y-m-d') }}"
                                value="{{ $blacklist->tanggal_kejadian->format('Y-m-d') }}"
                                class="form-control"
                            >
                            <div class="invalid-feedback d-none" id="tanggal_kejadian_error"></div>
                        </div>
                    </div>
                </div>

                <!-- Jenis Laporan -->
                <div class="mb-5">
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Jenis Laporan
                    </h6>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" name="jenis_laporan[]" value="percobaan_penipuan"
                                       {{ in_array('percobaan_penipuan', $blacklist->jenis_laporan) ? 'checked' : '' }}
                                       class="form-check-input" id="percobaan_penipuan">
                                <label class="form-check-label" for="percobaan_penipuan">Percobaan Penipuan</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" name="jenis_laporan[]" value="penipuan"
                                       {{ in_array('penipuan', $blacklist->jenis_laporan) ? 'checked' : '' }}
                                       class="form-check-input" id="penipuan">
                                <label class="form-check-label" for="penipuan">Penipuan</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" name="jenis_laporan[]" value="tidak_mengembalikan_barang"
                                       {{ in_array('tidak_mengembalikan_barang', $blacklist->jenis_laporan) ? 'checked' : '' }}
                                       class="form-check-input" id="tidak_mengembalikan_barang">
                                <label class="form-check-label" for="tidak_mengembalikan_barang">Tidak Mengembalikan Barang</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" name="jenis_laporan[]" value="identitas_palsu"
                                       {{ in_array('identitas_palsu', $blacklist->jenis_laporan) ? 'checked' : '' }}
                                       class="form-check-input" id="identitas_palsu">
                                <label class="form-check-label" for="identitas_palsu">Identitas Palsu</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" name="jenis_laporan[]" value="sindikat"
                                       {{ in_array('sindikat', $blacklist->jenis_laporan) ? 'checked' : '' }}
                                       class="form-check-input" id="sindikat">
                                <label class="form-check-label" for="sindikat">Sindikat</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" name="jenis_laporan[]" value="merusak_barang"
                                       {{ in_array('merusak_barang', $blacklist->jenis_laporan) ? 'checked' : '' }}
                                       class="form-check-input" id="merusak_barang">
                                <label class="form-check-label" for="merusak_barang">Merusak Barang</label>
                            </div>
                        </div>
                    </div>
                    <div class="invalid-feedback d-none" id="jenis_laporan_error"></div>
                </div>

                <!-- Kronologi -->
                <div class="mb-4">
                    <label for="kronologi" class="form-label fw-medium">
                        Kronologi Kejadian <span class="text-danger">*</span>
                    </label>
                    <textarea
                        id="kronologi"
                        name="kronologi"
                        required
                        rows="5"
                        class="form-control"
                        placeholder="Ceritakan kronologi kejadian secara detail..."
                    >{{ $blacklist->kronologi }}</textarea>
                    <div class="invalid-feedback d-none" id="kronologi_error"></div>
                </div>

                <!-- Existing Files -->
                @if($blacklist->bukti && count($blacklist->bukti) > 0)
                <div class="mb-4">
                    <label class="form-label fw-medium">
                        Bukti Saat Ini
                    </label>
                    <div class="row g-3" id="existingFiles">
                        @foreach($blacklist->bukti as $file)
                        <div class="col-md-6">
                            <div class="card" data-file="{{ $file }}">
                                <div class="card-body d-flex justify-content-between align-items-center py-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-file text-muted me-2"></i>
                                        <a href="{{ asset('storage/' . $file) }}" target="_blank" class="text-decoration-none">
                                            {{ basename($file) }}
                                        </a>
                                    </div>
                                    <button type="button" onclick="removeFile('{{ $file }}')" class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="removed_files" id="removedFiles" value="">
                </div>
                @endif

                <!-- New Files -->
                <div class="mb-4">
                    <label for="bukti" class="form-label fw-medium">
                        Tambah Bukti Baru (Opsional)
                    </label>
                    <input
                        type="file"
                        id="bukti"
                        name="bukti[]"
                        multiple
                        accept=".jpg,.jpeg,.png,.pdf,.mp4,.avi,.mov"
                        class="form-control"
                    >
                    <div class="form-text">
                        Format: JPG, PNG, PDF, MP4, AVI, MOV. Maksimal 10MB per file.
                    </div>
                    <div class="invalid-feedback d-none" id="bukti_error"></div>
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-between pt-4 border-top">
                    <a href="{{ route('dashboard.blacklist.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>
                        Batal
                    </a>
                    <button
                        type="submit"
                        id="submitBtn"
                        class="btn btn-success"
                    >
                        <i class="fas fa-save me-2"></i>
                        Update Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
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
            $(`[data-file="${filename}"]`).closest('.col-md-6').remove();
        }
    };

    // Form submission
    $('#blacklistForm').on('submit', function(e) {
        e.preventDefault();

        // Clear previous errors
        $('.invalid-feedback').addClass('d-none');
        $('.form-control, .form-select').removeClass('is-invalid');

        // Validate jenis laporan
        if ($('input[name="jenis_laporan[]"]:checked').length === 0) {
            $('#jenis_laporan_error').text('Pilih minimal satu jenis laporan').removeClass('d-none');
            return;
        }

        const formData = new FormData(this);

        $('#submitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Mengupdate...');

        $.ajax({
            url: '{{ route("dashboard.blacklist.update", $blacklist->id) }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Show success toast
                    const toast = new bootstrap.Toast(document.getElementById('successToast'));
                    toast.show();

                    setTimeout(function() {
                        window.location.href = '{{ route("dashboard.blacklist.index") }}';
                    }, 1500);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        $(`#${key}`).addClass('is-invalid');
                        $(`#${key}_error`).text(errors[key][0]).removeClass('d-none');
                    });
                } else {
                    alert('Terjadi kesalahan saat mengupdate data');
                }
            },
            complete: function() {
                $('#submitBtn').prop('disabled', false).html('<i class="fas fa-save me-2"></i>Update Laporan');
            }
        });
    });
});
</script>
@endpush

<!-- Success Toast -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="successToast" class="toast" role="alert">
        <div class="toast-header bg-success text-white">
            <i class="fas fa-check-circle me-2"></i>
            <strong class="me-auto">Berhasil</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            Laporan berhasil diupdate!
        </div>
    </div>
</div>

@endsection
