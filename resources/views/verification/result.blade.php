@extends('layouts.app')

@section('title', 'Hasil Verifikasi Dokumen')

@section('content')
<div class="bg-light min-vh-100">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header -->
                <div class="text-center mb-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h1 class="display-5 fw-bold text-dark mb-3">Dokumen Terverifikasi</h1>
                    <p class="lead text-muted">
                        Dokumen dengan kode <strong>{{ $verification->verification_code }}</strong> adalah dokumen asli
                    </p>
                </div>

                <!-- Verification Details -->
                <div class="card border-0 shadow-lg mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-certificate me-2"></i>
                            Detail Verifikasi
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-bold">Kode Verifikasi:</td>
                                        <td>
                                            <code class="bg-light p-2 rounded">{{ $verification->verification_code }}</code>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Jenis Dokumen:</td>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ $verification->document_type === 'print' ? 'Dokumen Cetak' : 'Dokumen PDF' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Tanggal Generate:</td>
                                        <td>{{ $verification->generated_at->format('d/m/Y H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Diverifikasi:</td>
                                        <td>{{ $verification->verified_at ? $verification->verified_at->format('d/m/Y H:i:s') : 'Belum diverifikasi' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-bold">Dibuat oleh:</td>
                                        <td>{{ $verification->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Email:</td>
                                        <td>{{ $verification->user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Role:</td>
                                        <td>
                                            <span class="badge bg-info">
                                                @switch($verification->user->role)
                                                    @case('admin')
                                                        Administrator
                                                        @break
                                                    @case('pengusaha_rental')
                                                        Pemilik Rental
                                                        @break
                                                    @case('user')
                                                        User Biasa
                                                        @break
                                                    @default
                                                        {{ $verification->user->role }}
                                                @endswitch
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Status:</td>
                                        <td>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>
                                                Dokumen Valid
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Document Content -->
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-file-alt me-2"></i>
                            Informasi Laporan
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-user me-2"></i>
                                    Data Penyewa
                                </h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td class="fw-bold">Nama Lengkap:</td>
                                        <td>{{ $verification->blacklist->nama_lengkap }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">NIK:</td>
                                        <td>{{ $verification->blacklist->nik }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">No. HP:</td>
                                        <td>{{ $verification->blacklist->no_hp }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Jenis Kelamin:</td>
                                        <td>{{ $verification->blacklist->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-danger mb-3">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Data Laporan
                                </h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td class="fw-bold">Jenis Rental:</td>
                                        <td>{{ $verification->blacklist->jenis_rental }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Tanggal Kejadian:</td>
                                        <td>{{ $verification->blacklist->tanggal_kejadian ? $verification->blacklist->tanggal_kejadian->format('d/m/Y') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Status Validitas:</td>
                                        <td>
                                            <span class="badge {{ $verification->blacklist->status_validitas === 'Valid' ? 'bg-success' : ($verification->blacklist->status_validitas === 'Pending' ? 'bg-warning' : 'bg-danger') }}">
                                                {{ $verification->blacklist->status_validitas }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Tanggal Laporan:</td>
                                        <td>{{ $verification->blacklist->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if($verification->blacklist->kronologi)
                        <div class="mt-4">
                            <h6 class="text-info mb-3">
                                <i class="fas fa-file-text me-2"></i>
                                Kronologi Kejadian
                            </h6>
                            <div class="bg-light p-3 rounded">
                                {{ $verification->blacklist->kronologi }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="text-center mt-4">
                    <a href="{{ route('verifikasi.index') }}" class="btn btn-primary btn-lg me-3">
                        <i class="fas fa-search me-2"></i>
                        Verifikasi Dokumen Lain
                    </a>
                    <a href="{{ route('beranda') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-home me-2"></i>
                        Kembali ke Beranda
                    </a>
                </div>

                <!-- Security Notice -->
                <div class="alert alert-warning mt-4" role="alert">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="alert-heading">Penting!</h6>
                            <p class="mb-0">
                                Verifikasi ini hanya memastikan bahwa dokumen adalah asli dari sistem CekPenyewa.com. 
                                Untuk keperluan hukum atau bisnis, pastikan untuk melakukan verifikasi tambahan sesuai kebutuhan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
