@extends('layouts.main')

@section('title', 'Daftar Rental')

@section('content')
<div class="bg-gradient-primary py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Header -->
                <div class="text-center mb-5">
                    <h1 class="display-5 fw-bold text-dark mb-3">
                        <i class="fas fa-store text-primary me-3"></i>
                        Daftar Rental
                    </h1>
                    <p class="lead text-muted">
                        Daftarkan bisnis rental Anda dan dapatkan akses penuh ke database blacklist
                    </p>
                    <div class="badge bg-success fs-6 pulse">
                        <i class="fas fa-gift me-1"></i>
                        100% GRATIS SELAMANYA
                    </div>
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
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-file-alt me-2"></i>
                            Form Pendaftaran Rental
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('rental.simpan') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Data Rental -->
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-store me-2"></i>
                                    Data Rental
                                </h5>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="nama_rental" class="form-label">Nama Rental <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nama_rental') is-invalid @enderror"
                                               id="nama_rental" name="nama_rental" value="{{ old('nama_rental') }}"
                                               placeholder="Nama bisnis rental Anda" required>
                                        @error('nama_rental')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Jenis Rental <span class="text-danger">*</span></label>
                                        <div class="row g-2">
                                            @php
                                                $jenisRental = ['Motor', 'Mobil', 'Alat Berat', 'Elektronik', 'Peralatan', 'Lainnya'];
                                            @endphp
                                            @foreach($jenisRental as $jenis)
                                            <div class="col-6">
                                                <div class="form-check">
                                                    <input class="form-check-input @error('jenis_rental') is-invalid @enderror"
                                                           type="checkbox" name="jenis_rental[]" value="{{ $jenis }}"
                                                           id="jenis_{{ $loop->index }}"
                                                           {{ in_array($jenis, old('jenis_rental', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="jenis_{{ $loop->index }}">
                                                        {{ $jenis }}
                                                    </label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        @error('jenis_rental')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="alamat" class="form-label">Alamat Rental <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('alamat') is-invalid @enderror"
                                                  id="alamat" name="alamat" rows="3"
                                                  placeholder="Alamat lengkap rental" required>{{ old('alamat') }}</textarea>
                                        @error('alamat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="kota" class="form-label">Kota <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('kota') is-invalid @enderror"
                                               id="kota" name="kota" value="{{ old('kota') }}"
                                               placeholder="Kota" required>
                                        @error('kota')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="provinsi" class="form-label">Provinsi <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('provinsi') is-invalid @enderror"
                                               id="provinsi" name="provinsi" value="{{ old('provinsi') }}"
                                               placeholder="Provinsi" required>
                                        @error('provinsi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="no_hp" class="form-label">No. HP Rental <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('no_hp') is-invalid @enderror"
                                               id="no_hp" name="no_hp" value="{{ old('no_hp') }}"
                                               placeholder="08xxxxxxxxxx" required>
                                        @error('no_hp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email Rental <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                               id="email" name="email" value="{{ old('email') }}"
                                               placeholder="email@rental.com" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="deskripsi" class="form-label">Deskripsi Rental</label>
                                        <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                                                  id="deskripsi" name="deskripsi" rows="3"
                                                  placeholder="Ceritakan tentang rental Anda...">{{ old('deskripsi') }}</textarea>
                                        @error('deskripsi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Data Pemilik -->
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-user me-2"></i>
                                    Data Pemilik
                                </h5>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="nama_pemilik" class="form-label">Nama Pemilik <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nama_pemilik') is-invalid @enderror"
                                               id="nama_pemilik" name="nama_pemilik" value="{{ old('nama_pemilik') }}"
                                               placeholder="Nama lengkap pemilik" required>
                                        @error('nama_pemilik')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="nik_pemilik" class="form-label">NIK Pemilik <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nik_pemilik') is-invalid @enderror"
                                               id="nik_pemilik" name="nik_pemilik" value="{{ old('nik_pemilik') }}"
                                               placeholder="16 digit NIK" maxlength="16" required>
                                        @error('nik_pemilik')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="no_hp_pemilik" class="form-label">No. HP Pemilik <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('no_hp_pemilik') is-invalid @enderror"
                                               id="no_hp_pemilik" name="no_hp_pemilik" value="{{ old('no_hp_pemilik') }}"
                                               placeholder="08xxxxxxxxxx" required>
                                        @error('no_hp_pemilik')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Kontak & Media Sosial -->
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-share-alt me-2"></i>
                                    Kontak & Media Sosial
                                </h5>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="website" class="form-label">Website</label>
                                        <input type="url" class="form-control @error('website') is-invalid @enderror"
                                               id="website" name="website" value="{{ old('website') }}"
                                               placeholder="https://rental.com">
                                        @error('website')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="facebook" class="form-label">Facebook</label>
                                        <input type="url" class="form-control @error('sosial_media.facebook') is-invalid @enderror"
                                               id="facebook" name="sosial_media[facebook]" value="{{ old('sosial_media.facebook') }}"
                                               placeholder="https://facebook.com/rental">
                                        @error('sosial_media.facebook')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="instagram" class="form-label">Instagram</label>
                                        <input type="url" class="form-control @error('sosial_media.instagram') is-invalid @enderror"
                                               id="instagram" name="sosial_media[instagram]" value="{{ old('sosial_media.instagram') }}"
                                               placeholder="https://instagram.com/rental">
                                        @error('sosial_media.instagram')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="whatsapp" class="form-label">WhatsApp</label>
                                        <input type="text" class="form-control @error('sosial_media.whatsapp') is-invalid @enderror"
                                               id="whatsapp" name="sosial_media[whatsapp]" value="{{ old('sosial_media.whatsapp') }}"
                                               placeholder="628xxxxxxxxxx">
                                        @error('sosial_media.whatsapp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Dokumen -->
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-file-upload me-2"></i>
                                    Upload Dokumen
                                </h5>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="dokumen_legalitas" class="form-label">Dokumen Legalitas</label>
                                        <input type="file" class="form-control @error('dokumen_legalitas.*') is-invalid @enderror"
                                               id="dokumen_legalitas" name="dokumen_legalitas[]" multiple accept=".jpg,.jpeg,.png,.pdf">
                                        <div class="form-text">SIUP, TDP, atau dokumen legalitas lainnya (JPG, PNG, PDF, max 2MB per file)</div>
                                        @error('dokumen_legalitas.*')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="foto_tempat" class="form-label">Foto Tempat Rental</label>
                                        <input type="file" class="form-control @error('foto_tempat.*') is-invalid @enderror"
                                               id="foto_tempat" name="foto_tempat[]" multiple accept=".jpg,.jpeg,.png">
                                        <div class="form-text">Foto tampak depan, dalam, atau area rental (JPG, PNG, max 2MB per file)</div>
                                        @error('foto_tempat.*')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('beranda') }}" class="btn btn-secondary btn-lg me-md-2">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Kembali
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Daftar Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Benefits Card -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body">
                        <h6 class="text-success mb-3">
                            <i class="fas fa-gift me-2"></i>
                            Keuntungan Bergabung
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Akses penuh database blacklist
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Lihat data lengkap tanpa sensor
                                    </li>
                                    <li class="mb-0">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Tambah laporan pelanggan bermasalah
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Kelola data laporan Anda
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Akses API untuk integrasi
                                    </li>
                                    <li class="mb-0">
                                        <i class="fas fa-check text-success me-2"></i>
                                        100% gratis selamanya
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // NIK validation
    $('#nik_pemilik').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 16) {
            this.value = this.value.slice(0, 16);
        }
    });

    // Phone number validation
    $('#no_hp, #no_hp_pemilik, #whatsapp').on('input', function() {
        this.value = this.value.replace(/[^0-9+]/g, '');
    });
});
</script>
@endpush
