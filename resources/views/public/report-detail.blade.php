@extends('layouts.main')

@section('title', 'Detail Laporan - ' . ($showUncensored ? $report->nama_lengkap : $report->sensored_nama))
@section('meta_description', 'Detail lengkap laporan blacklist untuk ' . ($showUncensored ? $report->nama_lengkap : $report->sensored_nama) . ' - ' . $report->jenis_rental)

@push('styles')
<style>
    :root {
        --primary-color: #da3544;
        --primary-dark: #c62d42;
        --primary-darker: #b02a37;
        --shadow-light: rgba(218, 53, 68, 0.1);
        --shadow-medium: rgba(218, 53, 68, 0.2);
        --shadow-dark: rgba(218, 53, 68, 0.3);
        --border-radius: 24px;
        --border-radius-sm: 16px;
    }

    .detail-hero {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 50%, var(--primary-darker) 100%);
        color: white;
        padding: 5rem 0;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px var(--shadow-dark);
    }

    .detail-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 30% 20%, rgba(255,255,255,0.1) 0%, transparent 50%),
                    radial-gradient(circle at 70% 80%, rgba(255,255,255,0.05) 0%, transparent 50%);
        z-index: 1;
    }

    .detail-hero .container {
        position: relative;
        z-index: 2;
    }

    .hero-icon {
        width: 100px;
        height: 100px;
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(10px);
        border-radius: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid rgba(255,255,255,0.2);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .detail-card {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: 0 12px 40px rgba(0,0,0,0.08);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        margin-bottom: 2rem;
        background: white;
        border: 1px solid rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .detail-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 24px 60px rgba(0,0,0,0.12);
        border-color: var(--shadow-light);
    }

    .section-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 1.5rem 2rem;
        border-radius: var(--border-radius) var(--border-radius) 0 0;
        margin: 0;
        position: relative;
    }

    .section-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    }

    .danger-level {
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 1rem;
        display: inline-block;
    }

    .danger-low {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
        border: 2px solid #b8dacc;
    }
    .danger-medium {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        color: #856404;
        border: 2px solid #f4d03f;
    }
    .danger-high {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        color: #721c24;
        border: 2px solid #f1b0b7;
    }

    .info-badge {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        color: #0d47a1;
        padding: 0.5rem 1rem;
        border-radius: 15px;
        font-weight: 600;
        border: 2px solid #90caf9;
    }

    .reporter-badge {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: transform 0.2s ease;
        border: 2px solid #1e7e34;
    }

    .reporter-badge:hover {
        color: white;
        transform: scale(1.05);
    }

    .back-button {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
        padding: 1rem 2rem;
        border-radius: 20px;
        text-decoration: none;
        font-weight: 700;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        display: inline-block;
        box-shadow: 0 8px 24px rgba(108, 117, 125, 0.3);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.9rem;
    }

    .back-button:hover {
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 16px 40px rgba(108, 117, 125, 0.4);
        background: linear-gradient(135deg, #495057 0%, #343a40 100%);
    }

    .related-card {
        background: linear-gradient(145deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #dee2e6;
        border-radius: 15px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        margin-bottom: 1rem;
    }

    .related-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        border-color: rgba(218, 53, 68, 0.2);
    }

    .image-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .image-item {
        position: relative;
        border-radius: var(--border-radius-sm);
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        background: white;
    }

    .image-item:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .image-item img {
        width: 100%;
        height: 220px;
        object-fit: cover;
        cursor: pointer;
        transition: transform 0.4s ease;
    }

    .image-item:hover img {
        transform: scale(1.05);
    }

    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(0,0,0,0.3), transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .image-item:hover .image-overlay {
        opacity: 1;
    }

    .file-item {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 2px solid #dee2e6;
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .file-item:hover {
        border-color: rgba(218, 53, 68, 0.3);
        transform: translateY(-2px);
    }

    .info-item {
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: var(--border-radius-sm);
        border-left: 4px solid var(--primary-color);
        transition: all 0.3s ease;
    }

    .info-item:hover {
        background: #e9ecef;
        transform: translateX(4px);
    }

    .content-section {
        padding: 2rem;
        border-radius: var(--border-radius-sm);
        background: #f8f9fa;
        border-left: 4px solid var(--primary-color);
        margin: 1.5rem 0;
    }

    .badge-modern {
        padding: 0.75rem 1.5rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
        margin: 0.25rem;
        display: inline-block;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .status-badge {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 20px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 16px rgba(40, 167, 69, 0.3);
    }

    @media (max-width: 768px) {
        .hero-icon {
            width: 80px;
            height: 80px;
        }

        .detail-hero {
            padding: 3rem 0;
        }

        .timeline-main {
            padding-left: 2rem;
        }

        .timeline-main::before {
            left: 16px;
            width: 4px;
        }

        .timeline-item::before {
            left: -2rem;
            width: 20px;
            height: 20px;
        }
    }
</style>
@endpush

@section('content')
<!-- Detail Hero Section -->
<section class="detail-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-4">
                    <div class="me-4">
                        <div class="hero-icon">
                            <i class="fas fa-file-alt text-white" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="display-4 fw-bold mb-3">Detail Laporan Blacklist</h1>
                        <h2 class="mb-3 opacity-90">{{ $showUncensored ? $report->nama_lengkap : $report->sensored_nama }}</h2>
                        <div class="d-flex align-items-center flex-wrap gap-3">
                            <span class="badge bg-white bg-opacity-20 text-white px-3 py-2 rounded-pill">
                                <i class="fas fa-id-card me-2"></i>
                                NIK: {{ $showUncensored ? $report->nik : $report->sensored_nik }}
                            </span>
                            <span class="badge bg-white bg-opacity-20 text-white px-3 py-2 rounded-pill">
                                <i class="fas fa-phone me-2"></i>
                                HP: {{ $showUncensored ? $report->no_hp : $report->sensored_no_hp }}
                            </span>
                            @if($showUncensored)
                                <span class="badge bg-success px-3 py-2 rounded-pill">
                                    <i class="fas fa-eye me-2"></i>
                                    Data Lengkap
                                </span>
                            @else
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                    <i class="fas fa-eye-slash me-2"></i>
                                    Data Disensor
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <p class="lead mb-0 opacity-90 fs-5">
                    Detail lengkap laporan blacklist untuk {{ $report->jenis_rental }} -
                    {{ \App\Helpers\DateHelper::formatIndonesian($report->tanggal_kejadian) }}
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="danger-level danger-{{ $dangerLevel }} bg-white bg-opacity-15 text-white border-0">
                    <i class="fas fa-shield-alt me-2"></i>
                    Tingkat Risiko: {{ $dangerText }}
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Navigation -->
<section class="py-4" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <a href="{{ route('laporan.timeline', $report->nik) }}" class="back-button">
                <i class="fas fa-arrow-left me-2"></i>
                Kembali ke Timeline
            </a>
            <div class="info-badge">
                <i class="fas fa-calendar me-2"></i>
                Laporan dari {{ $totalReports }} total laporan
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Informasi Pelapor -->
            @if($report->tipe_pelapor === 'guest')
            <div class="col-12">
                <div class="detail-card">
                    <div class="section-header">
                        <h5 class="mb-0">
                            <i class="fas fa-building me-2"></i>
                            Informasi Pelapor (Rental)
                        </h5>
                    </div>
                    <div class="p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="form-label fw-bold text-muted mb-2">
                                        <i class="fas fa-building me-2 text-primary"></i>
                                        Nama Perusahaan
                                    </label>
                                    <p class="mb-0 fs-5 fw-semibold">{{ $report->nama_perusahaan_rental ?: 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="form-label fw-bold text-muted mb-2">
                                        <i class="fas fa-user-tie me-2 text-primary"></i>
                                        Penanggung Jawab
                                    </label>
                                    <p class="mb-0 fs-5 fw-semibold">{{ $report->nama_penanggung_jawab ?: 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="form-label fw-bold text-muted mb-2">
                                        <i class="fab fa-whatsapp me-2 text-success"></i>
                                        No. WhatsApp
                                    </label>
                                    <p class="mb-0 fs-5 fw-semibold font-monospace">{{ $report->no_wa_pelapor ?: 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="form-label fw-bold text-muted mb-2">
                                        <i class="fas fa-envelope me-2 text-info"></i>
                                        Email
                                    </label>
                                    <p class="mb-0 fs-5 fw-semibold">{{ $report->email_pelapor ?: 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="form-label fw-bold text-muted mb-2">
                                        <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                                        Alamat Usaha
                                    </label>
                                    <p class="mb-0 lh-lg">{{ $report->alamat_usaha ?: 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="form-label fw-bold text-muted mb-2">
                                        <i class="fas fa-globe me-2 text-warning"></i>
                                        Website/Instagram
                                    </label>
                                    <p class="mb-0">
                                        @if($report->website_usaha)
                                            <a href="{{ $report->website_usaha }}" target="_blank"
                                               class="text-decoration-none fw-semibold text-primary">
                                                {{ $report->website_usaha }}
                                                <i class="fas fa-external-link-alt ms-1"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="col-12">
                <div class="detail-card">
                    <div class="section-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>
                            Informasi Pelapor
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            @if($report->user && $report->user->role === 'rental_owner')
                                <a href="{{ route('rental.profil', $report->user->id) }}" class="reporter-badge me-3">
                                    <i class="fas fa-building me-1"></i>
                                    {{ $report->user->name }}
                                    <span class="badge bg-light text-success ms-2">Verified</span>
                                </a>
                            @elseif($report->user && $report->user->role === 'admin')
                                <span class="badge bg-danger fs-6 me-3">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    {{ $report->user->name }} (Administrator)
                                </span>
                            @else
                                <span class="badge bg-secondary fs-6 me-3">
                                    <i class="fas fa-user me-1"></i>
                                    {{ $report->user->name ?? 'Tidak diketahui' }}
                                </span>
                            @endif
                            <div>
                                <small class="text-muted">Dilaporkan pada:</small><br>
                                <span class="fw-medium">{{ \App\Helpers\DateHelper::formatDenganWaktu($report->created_at) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Data Penyewa -->
            <div class="col-lg-6">
                <div class="detail-card h-100">
                    <div class="section-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user-circle me-2"></i>
                            Data Penyewa
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold text-muted">Nama Lengkap</label>
                                <p class="mb-0 fs-4 fw-bold text-primary">{{ $showUncensored ? $report->nama_lengkap : $report->sensored_nama }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted">Jenis Kelamin</label>
                                <p class="mb-0 fs-5">{{ $report->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted">NIK</label>
                                <p class="mb-0 fs-5 font-monospace">{{ $showUncensored ? $report->nik : $report->sensored_nik }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted">No. HP</label>
                                <p class="mb-0 fs-5 font-monospace">{{ $showUncensored ? $report->no_hp : $report->sensored_no_hp }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted">Status Validitas</label>
                                <p class="mb-0">
                                    <span class="badge bg-success fs-6">{{ $report->status_validitas }}</span>
                                </p>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold text-muted">Alamat</label>
                                <p class="mb-0">{{ $showUncensored ? $report->alamat : $report->sensored_alamat }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Masalah -->
            <div class="col-lg-6">
                <div class="detail-card h-100">
                    <div class="section-header">
                        <h5 class="mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Detail Masalah
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold text-muted">Kategori Rental</label>
                                <p class="mb-0">
                                    <span class="badge bg-info fs-6">{{ $report->jenis_rental }}</span>
                                </p>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold text-muted">Jenis Masalah</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @if($report->jenis_laporan && is_array($report->jenis_laporan))
                                        @foreach($report->jenis_laporan as $jenis)
                                            <span class="badge bg-danger fs-6">{{ $jenis }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Tidak ada data</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted">Tanggal Sewa</label>
                                <p class="mb-0 fs-5">{{ $report->tanggal_sewa ? \App\Helpers\DateHelper::formatIndonesian($report->tanggal_sewa) : 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted">Tanggal Kejadian</label>
                                <p class="mb-0 fs-5">{{ $report->tanggal_kejadian ? \App\Helpers\DateHelper::formatIndonesian($report->tanggal_kejadian) : 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted">Jenis Kendaraan/Barang</label>
                                <p class="mb-0 fs-5">{{ $report->jenis_kendaraan ?: 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted">Nomor Polisi</label>
                                <p class="mb-0 fs-5 font-monospace">{{ $report->nomor_polisi ?: 'N/A' }}</p>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold text-muted">Nilai Kerugian</label>
                                <p class="mb-0">
                                    @if($report->nilai_kerugian)
                                        <span class="fs-4 fw-bold text-danger">Rp {{ number_format($report->nilai_kerugian, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-muted">Tidak ada data</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kronologi Kejadian -->
        <div class="row g-4 mt-2">
            <div class="col-12">
                <div class="detail-card">
                    <div class="section-header">
                        <h5 class="mb-0">
                            <i class="fas fa-file-alt me-2"></i>
                            Kronologi Kejadian
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="bg-light p-4 rounded-3">
                            <p class="mb-0 fs-5 lh-lg">{{ $report->kronologi }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Foto Penyewa -->
        @if($report->foto_penyewa && count($report->foto_penyewa) > 0)
        <div class="row g-4 mt-2">
            <div class="col-12">
                <div class="detail-card">
                    <div class="section-header">
                        <h5 class="mb-0">
                            <i class="fas fa-camera me-2"></i>
                            Foto Penyewa ({{ count($report->foto_penyewa) }} foto)
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="image-gallery">
                            @foreach($report->foto_penyewa as $foto)
                            <div class="image-item">
                                <img src="{{ asset('storage/' . $foto) }}"
                                     alt="Foto Penyewa"
                                     onclick="showImageModal('{{ asset('storage/' . $foto) }}')">
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-dark bg-opacity-75">
                                        <i class="fas fa-expand-alt"></i>
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Foto KTP/SIM -->
        @if($report->foto_ktp_sim && count($report->foto_ktp_sim) > 0)
        <div class="row g-4 mt-2">
            <div class="col-12">
                <div class="detail-card">
                    <div class="section-header">
                        <h5 class="mb-0">
                            <i class="fas fa-id-card me-2"></i>
                            Foto KTP/SIM ({{ count($report->foto_ktp_sim) }} foto)
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="image-gallery">
                            @foreach($report->foto_ktp_sim as $foto)
                            <div class="image-item">
                                <img src="{{ asset('storage/' . $foto) }}"
                                     alt="Foto KTP/SIM"
                                     onclick="showImageModal('{{ asset('storage/' . $foto) }}')">
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-dark bg-opacity-75">
                                        <i class="fas fa-expand-alt"></i>
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Bukti Pendukung -->
        @if($report->bukti && count($report->bukti) > 0)
        <div class="row g-4 mt-2">
            <div class="col-12">
                <div class="detail-card">
                    <div class="section-header">
                        <h5 class="mb-0">
                            <i class="fas fa-paperclip me-2"></i>
                            Bukti Pendukung ({{ count($report->bukti) }} file)
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="image-gallery">
                            @foreach($report->bukti as $bukti)
                            <div class="file-item">
                                @if(in_array(pathinfo($bukti, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                    <img src="{{ asset('storage/' . $bukti) }}"
                                         alt="Bukti Pendukung"
                                         style="width: 100%; height: 200px; object-fit: cover; border-radius: 10px; cursor: pointer;"
                                         onclick="showImageModal('{{ asset('storage/' . $bukti) }}')">
                                @else
                                    @if(in_array(pathinfo($bukti, PATHINFO_EXTENSION), ['pdf']))
                                        <i class="fas fa-file-pdf fa-4x text-danger mb-3"></i>
                                    @elseif(in_array(pathinfo($bukti, PATHINFO_EXTENSION), ['doc', 'docx']))
                                        <i class="fas fa-file-word fa-4x text-primary mb-3"></i>
                                    @elseif(in_array(pathinfo($bukti, PATHINFO_EXTENSION), ['mp4', 'avi', 'mov']))
                                        <i class="fas fa-file-video fa-4x text-info mb-3"></i>
                                    @else
                                        <i class="fas fa-file fa-4x text-muted mb-3"></i>
                                    @endif
                                    <p class="mb-2 fw-medium">{{ basename($bukti) }}</p>
                                    <a href="{{ asset('storage/' . $bukti) }}"
                                       class="btn btn-outline-primary btn-sm"
                                       target="_blank">
                                        <i class="fas fa-download me-1"></i>
                                        Download
                                    </a>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Status Penanganan -->
        @if($report->status_penanganan && is_array($report->status_penanganan))
        <div class="row g-4 mt-2">
            <div class="col-12">
                <div class="detail-card">
                    <div class="section-header">
                        <h5 class="mb-0">
                            <i class="fas fa-tasks me-2"></i>
                            Status Penanganan
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap gap-3 mb-3">
                            @foreach($report->status_penanganan as $status)
                                @if($status === 'dilaporkan_polisi')
                                    <span class="badge bg-danger fs-6 px-3 py-2">
                                        <i class="fas fa-shield-alt me-1"></i>
                                        Sudah dilaporkan ke polisi
                                    </span>
                                @elseif($status === 'tidak_ada_respon')
                                    <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                        <i class="fas fa-phone-slash me-1"></i>
                                        Tidak ada respon
                                    </span>
                                @elseif($status === 'proses_penyelesaian')
                                    <span class="badge bg-info fs-6 px-3 py-2">
                                        <i class="fas fa-hourglass-half me-1"></i>
                                        Proses penyelesaian
                                    </span>
                                @elseif($status === 'lainnya')
                                    <span class="badge bg-secondary fs-6 px-3 py-2">
                                        <i class="fas fa-ellipsis-h me-1"></i>
                                        Lainnya
                                    </span>
                                @endif
                            @endforeach
                        </div>

                        @if($report->status_lainnya)
                            <div class="alert alert-light border">
                                <h6 class="alert-heading">
                                    <i class="fas fa-info-circle text-info me-2"></i>
                                    Keterangan Lainnya
                                </h6>
                                <p class="mb-0">{{ $report->status_lainnya }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Laporan Terkait -->
        @if($relatedReports->count() > 0)
        <div class="row g-4 mt-2">
            <div class="col-12">
                <div class="detail-card">
                    <div class="section-header">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            Laporan Terkait ({{ $relatedReports->count() }} laporan lainnya)
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @foreach($relatedReports as $related)
                        <div class="related-card">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold text-primary mb-2">
                                        {{ $related->jenis_rental }} -
                                        {{ \App\Helpers\DateHelper::formatIndonesian($related->tanggal_kejadian) }}
                                    </h6>
                                    <div class="mb-2">
                                        @if($related->jenis_laporan && is_array($related->jenis_laporan))
                                            @foreach($related->jenis_laporan as $jenis)
                                                <span class="badge bg-danger me-1">{{ $jenis }}</span>
                                            @endforeach
                                        @endif
                                    </div>
                                    <p class="text-muted mb-2">
                                        {{ Str::limit($related->kronologi, 150) }}
                                    </p>
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>
                                        Dilaporkan oleh: {{ $related->user->name ?? 'N/A' }} -
                                        {{ \App\Helpers\DateHelper::formatRelatif($related->created_at) }}
                                    </small>
                                </div>
                                <div class="ms-3">
                                    <a href="{{ route('laporan.detail', $related->id) }}"
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <div class="text-center mt-4">
                            <a href="{{ route('laporan.timeline', $report->nik) }}"
                               class="btn btn-primary">
                                <i class="fas fa-history me-2"></i>
                                Lihat Timeline Lengkap
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Lihat Gambar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid" alt="Gambar">
            </div>
            <div class="modal-footer">
                <a id="downloadLink" href="" class="btn btn-primary" download>
                    <i class="fas fa-download"></i> Download
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('downloadLink').href = imageSrc;

    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
}

// Add smooth animations
$(document).ready(function() {
    $('.detail-card').each(function(index) {
        $(this).css('opacity', '0').css('transform', 'translateY(30px)');
        $(this).delay(index * 100).animate({
            opacity: 1
        }, 600).css('transform', 'translateY(0)');
    });
});
</script>
@endpush
@endsection
