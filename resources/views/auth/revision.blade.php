@extends('layouts.main')

@section('title', 'Revisi Data Pendaftaran - CekPenyewa.com')

@section('content')
<div class="bg-gradient-primary py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Header -->
                <div class="text-center mb-5">
                    <h1 class="display-5 fw-bold text-dark mb-3">
                        <i class="fas fa-edit text-primary me-3"></i>
                        Revisi Data Pendaftaran
                    </h1>
                    <p class="lead text-muted">
                        Perbarui data pendaftaran rental Anda sesuai catatan admin
                    </p>
                    @if($user->revision_notes)
                        <div class="alert alert-info">
                            <h6><i class="fas fa-sticky-note me-2"></i>Catatan dari Admin:</h6>
                            <p class="mb-0">{{ $user->revision_notes }}</p>
                        </div>
                    @endif
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
                            Form Revisi Data Rental
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('daftar.revisi.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

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
                                               id="nama_rental" name="nama_rental" 
                                               value="{{ old('nama_rental', $registration->nama_rental) }}" required>
                                        @error('nama_rental')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="jenis_rental" class="form-label">Jenis Rental <span class="text-danger">*</span></label>
                                        <div class="row">
                                            @foreach(['Motor', 'Mobil', 'Alat Berat', 'Elektronik', 'Peralatan', 'Lainnya'] as $jenis)
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                           name="jenis_rental[]" value="{{ $jenis }}" 
                                                           id="jenis_{{ $jenis }}"
                                                           {{ in_array($jenis, old('jenis_rental', $registration->jenis_rental ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="jenis_{{ $jenis }}">
                                                        {{ $jenis }}
                                                    </label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        @error('jenis_rental')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Lokasi -->
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    Lokasi
                                </h5>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="alamat" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                                  id="alamat" name="alamat" rows="3" required>{{ old('alamat', $registration->alamat) }}</textarea>
                                        @error('alamat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="kota" class="form-label">Kota/Kabupaten <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('kota') is-invalid @enderror" 
                                               id="kota" name="kota" 
                                               value="{{ old('kota', $registration->kota) }}" required>
                                        @error('kota')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="provinsi" class="form-label">Provinsi <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('provinsi') is-invalid @enderror" 
                                               id="provinsi" name="provinsi" 
                                               value="{{ old('provinsi', $registration->provinsi) }}" required>
                                        @error('provinsi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Kontak -->
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-phone me-2"></i>
                                    Kontak
                                </h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="no_hp" class="form-label">No. HP Rental <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control @error('no_hp') is-invalid @enderror" 
                                               id="no_hp" name="no_hp" 
                                               value="{{ old('no_hp', $registration->no_hp) }}" required>
                                        @error('no_hp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email Rental <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" 
                                               value="{{ old('email', $registration->email) }}" required>
                                        @error('email')
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
                                               id="nama_pemilik" name="nama_pemilik" 
                                               value="{{ old('nama_pemilik', $registration->nama_pemilik) }}" required>
                                        @error('nama_pemilik')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nik_pemilik" class="form-label">NIK Pemilik <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nik_pemilik') is-invalid @enderror" 
                                               id="nik_pemilik" name="nik_pemilik" maxlength="16"
                                               value="{{ old('nik_pemilik', $registration->nik_pemilik) }}" required>
                                        @error('nik_pemilik')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="no_hp_pemilik" class="form-label">No. HP Pemilik <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control @error('no_hp_pemilik') is-invalid @enderror" 
                                               id="no_hp_pemilik" name="no_hp_pemilik" 
                                               value="{{ old('no_hp_pemilik', $registration->no_hp_pemilik) }}" required>
                                        @error('no_hp_pemilik')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Tambahan -->
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Informasi Tambahan
                                </h5>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="deskripsi" class="form-label">Deskripsi Rental</label>
                                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                                  id="deskripsi" name="deskripsi" rows="3" 
                                                  placeholder="Ceritakan tentang rental Anda...">{{ old('deskripsi', $registration->deskripsi) }}</textarea>
                                        @error('deskripsi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="website" class="form-label">Website</label>
                                        <input type="url" class="form-control @error('website') is-invalid @enderror" 
                                               id="website" name="website" 
                                               value="{{ old('website', $registration->website) }}" 
                                               placeholder="https://example.com">
                                        @error('website')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('dasbor') }}" class="btn btn-secondary me-md-2">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Kirim Revisi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
