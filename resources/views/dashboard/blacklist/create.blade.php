@extends('layouts.main')

@section('title', 'Tambah Laporan Blacklist')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex align-items-center mb-3">
                <a href="{{ route('dasbor.daftar-hitam.indeks') }}" class="btn btn-outline-secondary me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="display-6 fw-bold text-dark mb-1">
                        <i class="fas fa-plus text-danger me-3"></i>
                        Tambah Laporan Blacklist
                    </h1>
                    <p class="text-muted mb-0">Laporkan pelanggan yang bermasalah untuk melindungi sesama pengusaha rental</p>
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
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
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
                            ></textarea>
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
                                <option value="Mobil">Mobil</option>
                                <option value="Motor">Motor</option>
                                <option value="Kamera">Kamera</option>
                                <option value="Alat Elektronik">Alat Elektronik</option>
                                <option value="Lainnya">Lainnya</option>
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
                                <input type="checkbox" name="jenis_laporan[]" value="percobaan_penipuan" class="form-check-input" id="percobaan_penipuan">
                                <label class="form-check-label" for="percobaan_penipuan">Percobaan Penipuan</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" name="jenis_laporan[]" value="penipuan" class="form-check-input" id="penipuan">
                                <label class="form-check-label" for="penipuan">Penipuan</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" name="jenis_laporan[]" value="tidak_mengembalikan_barang" class="form-check-input" id="tidak_mengembalikan_barang">
                                <label class="form-check-label" for="tidak_mengembalikan_barang">Tidak Mengembalikan Barang</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" name="jenis_laporan[]" value="identitas_palsu" class="form-check-input" id="identitas_palsu">
                                <label class="form-check-label" for="identitas_palsu">Identitas Palsu</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" name="jenis_laporan[]" value="sindikat" class="form-check-input" id="sindikat">
                                <label class="form-check-label" for="sindikat">Sindikat</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" name="jenis_laporan[]" value="merusak_barang" class="form-check-input" id="merusak_barang">
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
                    ></textarea>
                    <div class="invalid-feedback d-none" id="kronologi_error"></div>
                </div>

                <!-- Bukti -->
                <div class="mb-4">
                    <label for="bukti" class="form-label fw-medium">
                        Bukti (Opsional)
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
                    <a href="{{ route('dasbor.daftar-hitam.indeks') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>
                        Batal
                    </a>
                    <button
                        type="submit"
                        id="submitBtn"
                        class="btn btn-danger"
                    >
                        <i class="fas fa-save me-2"></i>
                        Simpan Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
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
        $('.invalid-feedback').addClass('d-none');
        $('.form-control, .form-select').removeClass('is-invalid');

        // Validate jenis laporan
        if ($('input[name="jenis_laporan[]"]:checked').length === 0) {
            $('#jenis_laporan_error').text('Pilih minimal satu jenis laporan').removeClass('d-none');
            return;
        }

        const formData = new FormData(this);

        $('#submitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...');

        $.ajax({
            url: '{{ route('dasbor.daftar-hitam.simpan') }}',
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
                        window.location.href = '{{ route('dasbor.daftar-hitam.indeks') }}';
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
                    alert('Terjadi kesalahan saat menyimpan data');
                }
            },
            complete: function() {
                $('#submitBtn').prop('disabled', false).html('<i class="fas fa-save me-2"></i>Simpan Laporan');
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
            Laporan berhasil disimpan!
        </div>
    </div>
</div>

@endsection
