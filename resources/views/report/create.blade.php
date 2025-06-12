@extends('layouts.main')

@section('title', 'Lapor Pelanggan Bermasalah')

@section('content')
<div class="bg-gradient-to-br from-danger-subtle to-warning-subtle py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Header -->
                <div class="text-center mb-5">
                    <h1 class="display-5 fw-bold text-dark mb-3">
                        <i class="fas fa-exclamation-triangle text-danger me-3"></i>
                        Lapor Pelanggan Bermasalah
                    </h1>
                    <p class="lead text-muted">
                        Bantu sesama pengusaha rental dengan melaporkan pelanggan bermasalah
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
                    <div class="card-header bg-danger text-white">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-file-alt me-2"></i>
                            Form Laporan
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('report.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Data Pelanggan Bermasalah -->
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-user me-2"></i>
                                    Data Pelanggan Bermasalah
                                </h5>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="nik" class="form-label">NIK <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nik') is-invalid @enderror" 
                                               id="nik" name="nik" value="{{ old('nik') }}" 
                                               placeholder="16 digit NIK" maxlength="16" required>
                                        @error('nik')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" 
                                               id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" 
                                               placeholder="Nama lengkap sesuai KTP" required>
                                        @error('nama_lengkap')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                        <select class="form-select @error('jenis_kelamin') is-invalid @enderror" 
                                                id="jenis_kelamin" name="jenis_kelamin" required>
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="no_hp" class="form-label">No. HP <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('no_hp') is-invalid @enderror" 
                                               id="no_hp" name="no_hp" value="{{ old('no_hp') }}" 
                                               placeholder="08xxxxxxxxxx" required>
                                        @error('no_hp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                                  id="alamat" name="alamat" rows="3" 
                                                  placeholder="Alamat lengkap" required>{{ old('alamat') }}</textarea>
                                        @error('alamat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Data Laporan -->
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-clipboard-list me-2"></i>
                                    Data Laporan
                                </h5>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="jenis_rental" class="form-label">Jenis Rental <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('jenis_rental') is-invalid @enderror" 
                                               id="jenis_rental" name="jenis_rental" value="{{ old('jenis_rental') }}" 
                                               placeholder="Motor, Mobil, Alat, dll" required>
                                        @error('jenis_rental')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="tanggal_kejadian" class="form-label">Tanggal Kejadian <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('tanggal_kejadian') is-invalid @enderror" 
                                               id="tanggal_kejadian" name="tanggal_kejadian" value="{{ old('tanggal_kejadian') }}" 
                                               max="{{ date('Y-m-d') }}" required>
                                        @error('tanggal_kejadian')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12">
                                        <label class="form-label">Jenis Laporan <span class="text-danger">*</span></label>
                                        <div class="row g-2">
                                            @php
                                                $jenisLaporan = ['Tidak Mengembalikan', 'Merusak Barang', 'Tidak Bayar', 'Kabur', 'Lainnya'];
                                            @endphp
                                            @foreach($jenisLaporan as $jenis)
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input @error('jenis_laporan') is-invalid @enderror" 
                                                           type="checkbox" name="jenis_laporan[]" value="{{ $jenis }}" 
                                                           id="jenis_{{ $loop->index }}"
                                                           {{ in_array($jenis, old('jenis_laporan', [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="jenis_{{ $loop->index }}">
                                                        {{ $jenis }}
                                                    </label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        @error('jenis_laporan')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="kronologi" class="form-label">Kronologi Kejadian <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('kronologi') is-invalid @enderror" 
                                                  id="kronologi" name="kronologi" rows="5" 
                                                  placeholder="Ceritakan kronologi kejadian secara detail..." required>{{ old('kronologi') }}</textarea>
                                        @error('kronologi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="bukti" class="form-label">Bukti Pendukung</label>
                                        <input type="file" class="form-control @error('bukti.*') is-invalid @enderror" 
                                               id="bukti" name="bukti[]" multiple accept=".jpg,.jpeg,.png,.pdf">
                                        <div class="form-text">Upload foto, screenshot, atau dokumen pendukung (JPG, PNG, PDF, max 2MB per file)</div>
                                        @error('bukti.*')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Data Pelapor -->
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-user-edit me-2"></i>
                                    Data Pelapor
                                </h5>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="nama_pelapor" class="form-label">Nama Pelapor <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('nama_pelapor') is-invalid @enderror" 
                                               id="nama_pelapor" name="nama_pelapor" value="{{ old('nama_pelapor') }}" 
                                               placeholder="Nama lengkap pelapor" required>
                                        @error('nama_pelapor')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="email_pelapor" class="form-label">Email Pelapor <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email_pelapor') is-invalid @enderror" 
                                               id="email_pelapor" name="email_pelapor" value="{{ old('email_pelapor') }}" 
                                               placeholder="email@example.com" required>
                                        @error('email_pelapor')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="no_hp_pelapor" class="form-label">No. HP Pelapor <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('no_hp_pelapor') is-invalid @enderror" 
                                               id="no_hp_pelapor" name="no_hp_pelapor" value="{{ old('no_hp_pelapor') }}" 
                                               placeholder="08xxxxxxxxxx" required>
                                        @error('no_hp_pelapor')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('home') }}" class="btn btn-secondary btn-lg me-md-2">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Kembali
                                </a>
                                <button type="submit" class="btn btn-danger btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Kirim Laporan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Informasi Penting
                        </h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Laporan akan diverifikasi dalam 1-3 hari kerja
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Data yang valid akan ditambahkan ke database blacklist
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Identitas pelapor akan dijaga kerahasiaannya
                            </li>
                            <li class="mb-0">
                                <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                Laporan palsu dapat dikenakan sanksi hukum
                            </li>
                        </ul>
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
    $('#nik').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 16) {
            this.value = this.value.slice(0, 16);
        }
    });
    
    // Phone number validation
    $('#no_hp, #no_hp_pelapor').on('input', function() {
        this.value = this.value.replace(/[^0-9+]/g, '');
    });
});
</script>
@endpush
