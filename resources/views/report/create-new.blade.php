@extends('layouts.main')

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
                                    <label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select class="form-select @error('jenis_kelamin') is-invalid @enderror" name="jenis_kelamin">
                                        <option value="">Pilih jenis kelamin</option>
                                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nomor KTP <span class="text-muted">(opsional)</span></label>
                                    <input type="text" class="form-control @error('nik') is-invalid @enderror"
                                           name="nik" value="{{ old('nik') }}" id="nik_input"
                                           placeholder="1234567890123456" maxlength="16">
                                    <div class="form-text">Maksimal 16 digit angka</div>
                                    @error('nik')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nomor Telepon/WhatsApp <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('no_hp') is-invalid @enderror"
                                           name="no_hp" value="{{ old('no_hp') }}" id="no_hp_input"
                                           placeholder="081234567890" maxlength="13">
                                    <div class="form-text">Maksimal 13 digit angka</div>
                                    @error('no_hp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Alamat Lengkap <span class="text-muted">(jika diketahui)</span></label>
                                    <textarea class="form-control @error('alamat') is-invalid @enderror"
                                              name="alamat" rows="3"
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
                                    <label class="form-label fw-semibold">Kategori Rental <span class="text-danger">*</span></label>
                                    <select class="form-select @error('jenis_rental') is-invalid @enderror" name="jenis_rental">
                                        <option value="">Pilih kategori rental</option>
                                        @foreach($jenisRental as $jenis)
                                            <option value="{{ $jenis->value }}" {{ old('jenis_rental') == $jenis->value ? 'selected' : '' }}>
                                                {{ $jenis->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('jenis_rental')
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
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Jenis Masalah <span class="text-danger">*</span></label>
                                    <div class="row g-2">
                                        @foreach($kategoriMasalah as $kategori)
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input @error('jenis_laporan') is-invalid @enderror"
                                                       type="checkbox" name="jenis_laporan[]" value="{{ $kategori->value }}"
                                                       id="jenis_{{ $kategori->id }}"
                                                       {{ in_array($kategori->value, old('jenis_laporan', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="jenis_{{ $kategori->id }}">
                                                    {{ $kategori->name }}
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @error('jenis_laporan')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nomor Polisi <span class="text-muted">(jika kendaraan)</span></label>
                                    <input type="text" class="form-control @error('nomor_polisi') is-invalid @enderror"
                                           name="nomor_polisi" value="{{ old('nomor_polisi') }}" id="nomor_polisi_input"
                                           placeholder="B 1234 ABC" style="text-transform: uppercase;">
                                    <div class="form-text">Format: B 1234 ABC atau AB 1234 AC</div>
                                    @error('nomor_polisi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nilai Kerugian <span class="text-muted">(estimasi)</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control @error('nilai_kerugian') is-invalid @enderror"
                                               name="nilai_kerugian" value="{{ old('nilai_kerugian') }}" id="nilai_kerugian_input"
                                               placeholder="0">
                                    </div>
                                    <div class="form-text">Contoh: Rp 1.000.000</div>
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
                                        @foreach($statusPenanganan as $status)
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input @error('status_penanganan') is-invalid @enderror"
                                                       type="checkbox" name="status_penanganan[]" value="{{ $status->value }}"
                                                       id="status_{{ $status->id }}" {{ in_array($status->value, old('status_penanganan', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="status_{{ $status->id }}">
                                                    {{ $status->name }}
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
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

                        <!-- Pemeriksaan Keamanan -->
                        <div class="mb-5">
                            <h4 class="text-primary mb-3 border-bottom pb-2">
                                <i class="fas fa-shield-alt me-2"></i>
                                Pemeriksaan Keamanan
                            </h4>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fas fa-robot me-2"></i>
                                        <strong>Verifikasi Keamanan:</strong> Sistem akan melakukan verifikasi otomatis untuk mencegah spam dan laporan palsu.
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Verifikasi Captcha <span class="text-danger">*</span></label>
                                    @if(config('services.recaptcha.site_key'))
                                        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                                        @error('g-recaptcha-response')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            reCAPTCHA belum dikonfigurasi. Silakan hubungi administrator.
                                        </div>
                                    @endif
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
@if(config('services.recaptcha.site_key'))
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endif
<script>
$(document).ready(function() {
    // NIK validation - only numbers, max 16 digits
    $('#nik_input').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 16) {
            this.value = this.value.slice(0, 16);
        }
    });

    // Phone number validation - only numbers, max 13 digits
    $('#no_hp_input').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 13) {
            this.value = this.value.slice(0, 13);
        }
    });

    // License plate formatting
    $('#nomor_polisi_input').on('input', function() {
        let value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');

        // Format license plate: B1234ABC -> B 1234 ABC
        if (value.length > 0) {
            let formatted = '';
            let cityCode = '';
            let numbers = '';
            let suffix = '';

            // Extract city code (1-2 letters at start)
            let match = value.match(/^([A-Z]{1,2})(.*)$/);
            if (match) {
                cityCode = match[1];
                let remaining = match[2];

                // Extract numbers (1-4 digits)
                let numberMatch = remaining.match(/^(\d{1,4})(.*)$/);
                if (numberMatch) {
                    numbers = numberMatch[1];
                    suffix = numberMatch[2].substring(0, 3); // Max 3 letters for suffix
                }
            }

            // Build formatted string
            if (cityCode) formatted += cityCode;
            if (numbers) formatted += (formatted ? ' ' : '') + numbers;
            if (suffix) formatted += (formatted ? ' ' : '') + suffix;

            this.value = formatted;
        }
    });

    // Currency formatting for nilai kerugian
    $('#nilai_kerugian_input').on('input', function() {
        let value = this.value.replace(/[^\d]/g, '');
        if (value) {
            // Format as currency
            let formatted = parseInt(value).toLocaleString('id-ID');
            this.value = formatted;
        }
    });

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

        // Check if at least one jenis masalah is selected
        if ($('input[name="jenis_laporan[]"]:checked').length === 0) {
            alert('Pilih minimal satu jenis masalah.');
            isValid = false;
        }

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

        // Check reCAPTCHA if enabled
        @if(config('services.recaptcha.site_key'))
        if (typeof grecaptcha !== 'undefined') {
            let recaptchaResponse = grecaptcha.getResponse();
            if (!recaptchaResponse) {
                alert('Silakan selesaikan verifikasi reCAPTCHA.');
                isValid = false;
            }
        }
        @endif

        if (!isValid) {
            e.preventDefault();
            return false;
        }

        // Convert currency format back to number for nilai_kerugian
        let nilaiKerugian = $('#nilai_kerugian_input').val();
        if (nilaiKerugian) {
            let numericValue = nilaiKerugian.replace(/[^\d]/g, '');
            $('#nilai_kerugian_input').val(numericValue);
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
