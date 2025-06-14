@extends('layouts.app')

@section('title', 'Formulir Pelaporan Penyewa Bermasalah')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="text-center mb-4">
                <h1 class="display-6 fw-bold text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Formulir Pelaporan Penyewa Bermasalah
                </h1>
                <p class="lead text-muted">
                    Laporkan penyewa yang bermasalah untuk membantu sesama pengusaha rental
                </p>
            </div>

            <!-- Alert Success -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Form Card -->
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <form action="{{ route('laporan.simpan') }}" method="POST" enctype="multipart/form-data" id="reportForm">
                        @csrf

                        <!-- 1. Informasi Pelapor (Rental) -->
                        @guest
                        <div class="mb-5">
                            <h4 class="text-primary mb-3 border-bottom pb-2">
                                <i class="fas fa-building me-2"></i>
                                1. Informasi Pelapor (Rental)
                            </h4>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama Perusahaan Rental <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_perusahaan_rental') is-invalid @enderror"
                                           name="nama_perusahaan_rental" value="{{ old('nama_perusahaan_rental') }}"
                                           placeholder="CV. Rental Sejahtera">
                                    @error('nama_perusahaan_rental')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama Penanggung Jawab <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_penanggung_jawab') is-invalid @enderror"
                                           name="nama_penanggung_jawab" value="{{ old('nama_penanggung_jawab') }}"
                                           placeholder="Budi Santoso">
                                    @error('nama_penanggung_jawab')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nomor WhatsApp/Telepon <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('no_wa_pelapor') is-invalid @enderror"
                                           name="no_wa_pelapor" value="{{ old('no_wa_pelapor') }}"
                                           placeholder="081234567890">
                                    @error('no_wa_pelapor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email_pelapor') is-invalid @enderror"
                                           name="email_pelapor" value="{{ old('email_pelapor') }}"
                                           placeholder="info@rentalsejahtera.com">
                                    @error('email_pelapor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Alamat Usaha <span class="text-muted">(opsional)</span></label>
                                    <textarea class="form-control @error('alamat_usaha') is-invalid @enderror"
                                              name="alamat_usaha" rows="2"
                                              placeholder="Jl. Raya No. 123, Kota">{{ old('alamat_usaha') }}</textarea>
                                    @error('alamat_usaha')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Website/Instagram Usaha <span class="text-muted">(opsional)</span></label>
                                    <input type="url" class="form-control @error('website_usaha') is-invalid @enderror"
                                           name="website_usaha" value="{{ old('website_usaha') }}"
                                           placeholder="https://www.rentalsejahtera.com atau https://instagram.com/rentalsejahtera">
                                    @error('website_usaha')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="mb-5">
                            <h4 class="text-primary mb-3 border-bottom pb-2">
                                <i class="fas fa-building me-2"></i>
                                1. Informasi Pelapor (Rental)
                            </h4>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Pelapor:</strong> {{ auth()->user()->name }} ({{ auth()->user()->email }})
                                <br>
                                <small class="text-muted">Data pelapor diambil dari akun yang sedang login</small>
                            </div>
                        </div>
                        @endguest

                        <!-- 2. Data Penyewa -->
                        <div class="mb-5">
                            <h4 class="text-primary mb-3 border-bottom pb-2">
                                <i class="fas fa-user me-2"></i>
                                2. Data Penyewa
                            </h4>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama Lengkap Penyewa <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror"
                                           name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                                           placeholder="John Doe">
                                    @error('nama_lengkap')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nomor KTP <span class="text-muted">(opsional)</span></label>
                                    <input type="text" class="form-control @error('nik') is-invalid @enderror"
                                           name="nik" value="{{ old('nik') }}"
                                           placeholder="1234567890123456" maxlength="16">
                                    @error('nik')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nomor Telepon/WhatsApp <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('no_hp') is-invalid @enderror"
                                           name="no_hp" value="{{ old('no_hp') }}"
                                           placeholder="081234567890">
                                    @error('no_hp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Alamat Lengkap <span class="text-muted">(jika diketahui)</span></label>
                                    <textarea class="form-control @error('alamat') is-invalid @enderror"
                                              name="alamat" rows="2"
                                              placeholder="Jl. Contoh No. 123, RT/RW, Kelurahan, Kecamatan, Kota">{{ old('alamat') }}</textarea>
                                    @error('alamat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Foto Penyewa <span class="text-muted">(jika ada)</span></label>
                                    <input type="file" class="form-control @error('foto_penyewa.*') is-invalid @enderror"
                                           name="foto_penyewa[]" multiple accept="image/*">
                                    <div class="form-text">Format: JPG, PNG, GIF. Maksimal 5MB per file.</div>
                                    @error('foto_penyewa.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Foto KTP/SIM <span class="text-muted">(jika ada, sensor jika perlu)</span></label>
                                    <input type="file" class="form-control @error('foto_ktp_sim.*') is-invalid @enderror"
                                           name="foto_ktp_sim[]" multiple accept="image/*">
                                    <div class="form-text">Format: JPG, PNG, GIF. Maksimal 5MB per file.</div>
                                    @error('foto_ktp_sim.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- 3. Detail Masalah -->
                        <div class="mb-5">
                            <h4 class="text-primary mb-3 border-bottom pb-2">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                3. Detail Masalah
                            </h4>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tanggal Sewa <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_sewa') is-invalid @enderror"
                                           name="tanggal_sewa" value="{{ old('tanggal_sewa') }}" max="{{ date('Y-m-d') }}">
                                    @error('tanggal_sewa')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tanggal Kejadian Masalah <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_kejadian') is-invalid @enderror"
                                           name="tanggal_kejadian" value="{{ old('tanggal_kejadian') }}" max="{{ date('Y-m-d') }}">
                                    @error('tanggal_kejadian')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Jenis Kendaraan/Barang yang Disewa <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('jenis_kendaraan') is-invalid @enderror"
                                           name="jenis_kendaraan" value="{{ old('jenis_kendaraan') }}"
                                           placeholder="Honda Beat 2020, Toyota Avanza 2019, Kamera Canon EOS, dll">
                                    @error('jenis_kendaraan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nomor Polisi <span class="text-muted">(jika kendaraan)</span></label>
                                    <input type="text" class="form-control @error('nomor_polisi') is-invalid @enderror"
                                           name="nomor_polisi" value="{{ old('nomor_polisi') }}"
                                           placeholder="B 1234 ABC">
                                    @error('nomor_polisi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nilai Kerugian <span class="text-muted">(estimasi)</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control @error('nilai_kerugian') is-invalid @enderror"
                                               name="nilai_kerugian" value="{{ old('nilai_kerugian') }}"
                                               placeholder="0" min="0" step="1000">
                                    </div>
                                    @error('nilai_kerugian')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Deskripsi Kejadian <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('kronologi') is-invalid @enderror"
                                              name="kronologi" rows="5"
                                              placeholder="Tuliskan secara kronologis dan detail apa yang terjadi. Minimal 50 karakter.">{{ old('kronologi') }}</textarea>
                                    <div class="form-text">Minimal 50 karakter. Jelaskan secara detail dan kronologis.</div>
                                    @error('kronologi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Bukti Pendukung <span class="text-muted">(opsional)</span></label>
                                    <input type="file" class="form-control @error('bukti.*') is-invalid @enderror"
                                           name="bukti[]" multiple accept="image/*,video/*,.pdf">
                                    <div class="form-text">
                                        Upload bukti chat, foto kendaraan, surat perjanjian, dll.
                                        Format: JPG, PNG, GIF, MP4, AVI, MOV, PDF. Maksimal 50MB per file video, 5MB per file lainnya.
                                    </div>
                                    @error('bukti.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- 4. Status Penanganan -->
                        <div class="mb-5">
                            <h4 class="text-primary mb-3 border-bottom pb-2">
                                <i class="fas fa-tasks me-2"></i>
                                4. Status Penanganan
                            </h4>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Pilih status yang sesuai <span class="text-danger">*</span></label>
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input @error('status_penanganan') is-invalid @enderror"
                                                       type="checkbox" name="status_penanganan[]" value="dilaporkan_polisi"
                                                       id="status_polisi" {{ in_array('dilaporkan_polisi', old('status_penanganan', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status_polisi">
                                                    <i class="fas fa-shield-alt me-1"></i>
                                                    Sudah dilaporkan ke polisi
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input @error('status_penanganan') is-invalid @enderror"
                                                       type="checkbox" name="status_penanganan[]" value="tidak_ada_respon"
                                                       id="status_respon" {{ in_array('tidak_ada_respon', old('status_penanganan', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status_respon">
                                                    <i class="fas fa-phone-slash me-1"></i>
                                                    Sudah dicoba dihubungi tapi tidak ada respon
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input @error('status_penanganan') is-invalid @enderror"
                                                       type="checkbox" name="status_penanganan[]" value="proses_penyelesaian"
                                                       id="status_proses" {{ in_array('proses_penyelesaian', old('status_penanganan', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status_proses">
                                                    <i class="fas fa-hourglass-half me-1"></i>
                                                    Masih proses penyelesaian
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input @error('status_penanganan') is-invalid @enderror"
                                                       type="checkbox" name="status_penanganan[]" value="lainnya"
                                                       id="status_lainnya" {{ in_array('lainnya', old('status_penanganan', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status_lainnya">
                                                    <i class="fas fa-ellipsis-h me-1"></i>
                                                    Lainnya
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @error('status_penanganan')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12" id="status_lainnya_input" style="display: none;">
                                    <label class="form-label fw-semibold">Keterangan Lainnya</label>
                                    <input type="text" class="form-control @error('status_lainnya') is-invalid @enderror"
                                           name="status_lainnya" value="{{ old('status_lainnya') }}"
                                           placeholder="Jelaskan status penanganan lainnya">
                                    @error('status_lainnya')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- 5. Persetujuan dan Pernyataan -->
                        <div class="mb-5">
                            <h4 class="text-primary mb-3 border-bottom pb-2">
                                <i class="fas fa-file-signature me-2"></i>
                                5. Persetujuan dan Pernyataan
                            </h4>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="alert alert-warning">
                                        <h6 class="alert-heading">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Pernyataan Pelapor
                                        </h6>
                                        <p class="mb-2">
                                            Saya menyatakan bahwa informasi yang saya berikan adalah <strong>benar dan dapat dipertanggungjawabkan</strong>.
                                            Saya bersedia dihubungi untuk klarifikasi jika dibutuhkan, dan menyetujui data ini digunakan untuk
                                            membangun ekosistem blacklist penyewa yang bertujuan melindungi sesama pengusaha rental.
                                        </p>
                                        <div class="form-check">
                                            <input class="form-check-input @error('persetujuan') is-invalid @enderror"
                                                   type="checkbox" name="persetujuan" value="1"
                                                   id="persetujuan" {{ old('persetujuan') ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="persetujuan">
                                                <span class="text-danger">*</span> Saya setuju dengan pernyataan di atas
                                            </label>
                                            @error('persetujuan')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama Lengkap Pelapor <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_pelapor_ttd') is-invalid @enderror"
                                           name="nama_pelapor_ttd" value="{{ old('nama_pelapor_ttd', auth()->user()->name ?? '') }}"
                                           placeholder="Nama lengkap untuk tanda tangan">
                                    <div class="form-text">Nama ini akan digunakan sebagai tanda tangan digital</div>
                                    @error('nama_pelapor_ttd')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tanggal Pelaporan</label>
                                    <input type="text" class="form-control" value="{{ date('d/m/Y') }}" readonly>
                                    <div class="form-text">Tanggal otomatis saat form dikirim</div>
                                </div>
                            </div>
                        </div>

                        <!-- Tambahan (opsional) -->
                        <div class="mb-5">
                            <h4 class="text-primary mb-3 border-bottom pb-2">
                                <i class="fas fa-plus-circle me-2"></i>
                                Tambahan (opsional)
                            </h4>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fas fa-robot me-2"></i>
                                        <strong>Verifikasi Keamanan:</strong> Sistem akan melakukan verifikasi otomatis untuk mencegah spam dan laporan palsu.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('beranda') }}" class="btn btn-secondary btn-lg me-md-2">
                                <i class="fas fa-arrow-left me-2"></i>
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-danger btn-lg" id="submitBtn">
                                <i class="fas fa-paper-plane me-2"></i>
                                Laporkan Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Show/hide status lainnya input
    function toggleStatusLainnya() {
        if ($('#status_lainnya').is(':checked')) {
            $('#status_lainnya_input').show();
        } else {
            $('#status_lainnya_input').hide();
            $('input[name="status_lainnya"]').val('');
        }
    }

    $('#status_lainnya').change(toggleStatusLainnya);
    toggleStatusLainnya(); // Initial check

    // Form validation
    $('#reportForm').on('submit', function(e) {
        let isValid = true;

        // Check if at least one status penanganan is selected
        if ($('input[name="status_penanganan[]"]:checked').length === 0) {
            alert('Pilih minimal satu status penanganan.');
            isValid = false;
        }

        // Check persetujuan
        if (!$('#persetujuan').is(':checked')) {
            alert('Anda harus menyetujui pernyataan untuk melanjutkan.');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            return false;
        }

        // Show loading state
        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...');
    });

    // File upload preview (optional enhancement)
    $('input[type="file"]').change(function() {
        const files = this.files;
        const maxSize = this.name.includes('bukti') && this.accept.includes('video') ? 50 * 1024 * 1024 : 5 * 1024 * 1024;

        for (let i = 0; i < files.length; i++) {
            if (files[i].size > maxSize) {
                alert(`File ${files[i].name} terlalu besar. Maksimal ${maxSize / (1024 * 1024)}MB.`);
                this.value = '';
                break;
            }
        }
    });

    // Auto-format currency input
    $('input[name="nilai_kerugian"]').on('input', function() {
        let value = this.value.replace(/[^\d]/g, '');
        this.value = value;
    });

    // Character counter for kronologi
    $('textarea[name="kronologi"]').on('input', function() {
        const length = this.value.length;
        const minLength = 50;
        const counter = $(this).siblings('.form-text');

        if (length < minLength) {
            counter.text(`Minimal 50 karakter. Saat ini: ${length} karakter.`).removeClass('text-success').addClass('text-warning');
        } else {
            counter.text(`${length} karakter. Sudah memenuhi syarat.`).removeClass('text-warning').addClass('text-success');
        }
    });
});
</script>
@endpush
@endsection
