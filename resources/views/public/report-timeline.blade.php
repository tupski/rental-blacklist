@extends('layouts.main')

@section('title', 'Timeline Laporan - ' . ($showUncensored ? $terlapor->nama_lengkap : $terlapor->sensored_nama))
@section('meta_description', 'Timeline lengkap laporan blacklist untuk ' . ($showUncensored ? $terlapor->nama_lengkap : $terlapor->sensored_nama) . ' dengan total ' . $totalReports . ' laporan.')

@push('styles')
<style>
    :root {
        --primary-color: #da3544;
        --primary-dark: #c62d42;
        --primary-darker: #b02a37;
        --shadow-light: rgba(218, 53, 68, 0.1);
        --shadow-medium: rgba(218, 53, 68, 0.2);
        --shadow-dark: rgba(218, 53, 68, 0.3);
    }

    .timeline-hero {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 50%, var(--primary-darker) 100%);
        color: white;
        padding: 5rem 0;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px var(--shadow-dark);
    }

    .timeline-hero::before {
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

    .timeline-hero .container {
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

    .timeline-card {
        border: none;
        border-radius: 24px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.08);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        margin-bottom: 2rem;
        background: white;
        border: 1px solid rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .timeline-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 20px 60px rgba(0,0,0,0.12);
        border-color: var(--shadow-light);
    }

    .card-header-modern {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 1.5rem;
        border: none;
        position: relative;
    }

    .card-header-modern::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    }

    .timeline-main {
        position: relative;
        padding-left: 4rem;
    }

    .timeline-main::before {
        content: '';
        position: absolute;
        left: 24px;
        top: 0;
        bottom: 0;
        width: 6px;
        background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-dark) 50%, var(--primary-darker) 100%);
        border-radius: 3px;
        box-shadow: 0 0 20px var(--shadow-medium);
    }

    .timeline-item {
        position: relative;
        margin-bottom: 4rem;
        background: white;
        border-radius: 24px;
        padding: 0;
        box-shadow: 0 12px 40px rgba(0,0,0,0.08);
        border: 1px solid rgba(0,0,0,0.05);
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .timeline-item:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 60px rgba(0,0,0,0.12);
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -3rem;
        top: 2.5rem;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        border: 6px solid white;
        box-shadow: 0 0 0 4px var(--primary-color), 0 4px 12px var(--shadow-medium);
        z-index: 10;
    }

    .timeline-date {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 700;
        display: inline-block;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 16px var(--shadow-light);
        border: 2px solid rgba(255,255,255,0.2);
    }

    .timeline-content {
        padding: 2rem;
    }

    .timeline-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1.5rem 2rem;
        margin: 0 -2rem 2rem -2rem;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        border: 1px solid rgba(0,0,0,0.05);
        height: 100%;
        transition: all 0.3s ease;
        box-shadow: 0 8px 32px rgba(0,0,0,0.06);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 48px rgba(0,0,0,0.1);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-label {
        color: #6c757d;
        font-weight: 600;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .reporter-badge {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: transform 0.2s ease;
    }

    .reporter-badge:hover {
        color: white;
        transform: scale(1.05);
    }

    .danger-level {
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .btn-detail {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border: none;
        border-radius: 20px;
        padding: 1rem 2rem;
        font-weight: 700;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 8px 24px var(--shadow-light);
        color: white;
        text-decoration: none;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: relative;
        overflow: hidden;
    }

    .btn-detail::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .btn-detail:hover::before {
        left: 100%;
    }

    .btn-detail:hover {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-darker) 100%);
        transform: translateY(-3px);
        box-shadow: 0 16px 40px var(--shadow-medium);
        color: white;
    }

    .problem-badge {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 600;
        margin: 0.25rem;
        display: inline-block;
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    }

    .info-section {
        background: #f8f9fa;
        border-radius: 16px;
        padding: 1.5rem;
        border-left: 4px solid var(--primary-color);
    }

    .danger-low { background: #d4edda; color: #155724; }
    .danger-medium { background: #fff3cd; color: #856404; }
    .danger-high { background: #f8d7da; color: #721c24; }
</style>
@endpush

@section('content')
<!-- Timeline Hero Section -->
<section class="timeline-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-4">
                    <div class="me-4">
                        <div class="hero-icon">
                            <i class="fas fa-history text-white" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="display-4 fw-bold mb-3">Timeline Laporan</h1>
                        <h2 class="mb-3 opacity-90">{{ $showUncensored ? $terlapor->nama_lengkap : $terlapor->sensored_nama }}</h2>
                        <div class="d-flex align-items-center flex-wrap gap-3">
                            <span class="badge bg-white bg-opacity-20 text-white px-3 py-2 rounded-pill">
                                <i class="fas fa-id-card me-2"></i>
                                NIK: {{ $showUncensored ? $terlapor->nik : $terlapor->sensored_nik }}
                            </span>
                            <span class="badge bg-white bg-opacity-20 text-white px-3 py-2 rounded-pill">
                                <i class="fas fa-phone me-2"></i>
                                HP: {{ $showUncensored ? $terlapor->no_hp : $terlapor->sensored_no_hp }}
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
                    Riwayat lengkap laporan blacklist diurutkan berdasarkan tanggal kejadian dari yang terlama hingga terbaru.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="stat-card bg-white bg-opacity-15 text-white border-0">
                    <div class="stat-number text-white">{{ $totalReports }}</div>
                    <div class="stat-label text-white opacity-75">Total Laporan</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-4 mb-5">
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-number">{{ $totalReports }}</div>
                    <div class="stat-label">Total Laporan</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-number">{{ $reportsByType->count() }}</div>
                    <div class="stat-label">Jenis Rental</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-number">{{ $reportsByCategory->count() }}</div>
                    <div class="stat-label">Kategori Masalah</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-number">
                        {{ $reports->first()->tanggal_kejadian->diffInDays($reports->last()->tanggal_kejadian) }}
                    </div>
                    <div class="stat-label">Hari Rentang</div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <!-- Statistik Jenis Rental -->
            <div class="col-lg-6">
                <div class="timeline-card">
                    <div class="card-header-modern">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-chart-pie me-2"></i>
                            Berdasarkan Jenis Rental
                        </h5>
                    </div>
                    <div class="p-4">
                        @foreach($reportsByType as $type => $count)
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="fw-semibold fs-6">{{ $type }}</span>
                                <div class="d-flex align-items-center">
                                    <div class="progress me-3" style="width: 120px; height: 10px; border-radius: 5px;">
                                        <div class="progress-bar"
                                             style="width: {{ ($count / $totalReports) * 100 }}%; background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); border-radius: 5px;"></div>
                                    </div>
                                    <span class="badge bg-primary rounded-pill px-3 py-2">{{ $count }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Statistik Kategori Masalah -->
            <div class="col-lg-6">
                <div class="timeline-card">
                    <div class="card-header-modern">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Kategori Masalah
                        </h5>
                    </div>
                    <div class="p-4">
                        @foreach($reportsByCategory as $category => $count)
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="fw-semibold fs-6">{{ $category }}</span>
                                <div class="d-flex align-items-center">
                                    <div class="progress me-3" style="width: 120px; height: 10px; border-radius: 5px;">
                                        <div class="progress-bar bg-warning"
                                             style="width: {{ ($count / $reportsByCategory->sum()) * 100 }}%; border-radius: 5px;"></div>
                                    </div>
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2">{{ $count }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Timeline Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="text-center mb-5">
                    <i class="fas fa-history text-primary me-2"></i>
                    Timeline Laporan Lengkap
                </h3>

                <div class="timeline-main">
                    @foreach($reports as $index => $report)
                        <div class="timeline-item">
                            <div class="timeline-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="timeline-date">
                                        <i class="fas fa-calendar me-2"></i>
                                        {{ \App\Helpers\DateHelper::formatIndonesian($report->tanggal_kejadian, 'd F Y') }}
                                    </div>
                                    <span class="badge bg-primary rounded-pill px-3 py-2">
                                        Laporan #{{ $totalReports - $index }}
                                    </span>
                                </div>
                            </div>

                            <div class="timeline-content">
                                <div class="row g-4">
                                    <div class="col-lg-8">
                                        <h4 class="fw-bold text-primary mb-3 d-flex align-items-center">
                                            <i class="fas fa-file-alt me-2"></i>
                                            {{ $report->jenis_rental }}
                                        </h4>

                                        <div class="mb-4">
                                            <h6 class="fw-bold mb-3 text-muted">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Jenis Masalah:
                                            </h6>
                                            <div class="d-flex flex-wrap gap-2">
                                                @if(is_array($report->jenis_laporan))
                                                    @foreach($report->jenis_laporan as $jenis)
                                                        <span class="problem-badge">{{ $jenis }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="problem-badge">{{ $report->jenis_laporan }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        @if($report->kronologi)
                                            <div class="mb-4">
                                                <h6 class="fw-bold mb-3 text-muted">
                                                    <i class="fas fa-file-text me-2"></i>
                                                    Kronologi:
                                                </h6>
                                                <div class="info-section">
                                                    <p class="mb-0 lh-lg">{{ Str::limit($report->kronologi, 250) }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="info-section h-100">
                                            <h6 class="fw-bold mb-4 text-primary">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Detail Laporan
                                            </h6>

                                            <div class="mb-3">
                                                <small class="text-muted fw-semibold">Pelapor:</small><br>
                                                @if($report->user && $report->user->role === 'rental_owner')
                                                    <a href="{{ route('rental.profil', $report->user->id) }}" class="reporter-badge text-decoration-none">
                                                        <i class="fas fa-building me-1"></i>
                                                        {{ $report->user->name }}
                                                        <span class="badge bg-light text-success ms-2">Verified</span>
                                                    </a>
                                                @elseif($report->user && $report->user->role === 'admin')
                                                    <span class="badge bg-danger fs-6 px-3 py-2">
                                                        <i class="fas fa-shield-alt me-1"></i>
                                                        {{ $report->user->name }} (Admin)
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary fs-6 px-3 py-2">{{ $report->user->name ?? 'Tidak diketahui' }}</span>
                                                @endif
                                            </div>

                                            <div class="mb-3">
                                                <small class="text-muted fw-semibold">Tanggal Lapor:</small><br>
                                                <span class="fw-medium">{{ \App\Helpers\DateHelper::formatDenganWaktu($report->created_at) }}</span>
                                            </div>

                                            <div class="mb-3">
                                                <small class="text-muted fw-semibold">Status:</small><br>
                                                <span class="badge bg-success fs-6 px-3 py-2">{{ $report->status_validitas }}</span>
                                            </div>

                                            @php
                                                $dangerLevel = 'low';
                                                $dangerText = 'Rendah';
                                                if($report->jenis_laporan && is_array($report->jenis_laporan)) {
                                                    $highRiskCategories = ['Tidak Mengembalikan', 'Merusak Barang', 'Tidak Bayar', 'Kabur'];
                                                    $hasHighRisk = !empty(array_intersect($report->jenis_laporan, $highRiskCategories));
                                                    if($hasHighRisk) {
                                                        $dangerLevel = 'high';
                                                        $dangerText = 'Tinggi';
                                                    } elseif(count($report->jenis_laporan) > 1) {
                                                        $dangerLevel = 'medium';
                                                        $dangerText = 'Sedang';
                                                    }
                                                }
                                            @endphp

                                            <div class="mb-4">
                                                <small class="text-muted fw-semibold">Tingkat Risiko:</small><br>
                                                <span class="danger-level danger-{{ $dangerLevel }}">
                                                    <i class="fas fa-shield-alt me-1"></i>
                                                    {{ $dangerText }}
                                                </span>
                                            </div>

                                            <div class="d-grid">
                                                <a href="{{ route('laporan.detail', $report->id) }}"
                                                   class="btn-detail text-center">
                                                    <i class="fas fa-eye me-2"></i>
                                                    Lihat Detail Lengkap
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Add smooth animations for timeline items
    $('.timeline-item').each(function(index) {
        $(this).css('opacity', '0').css('transform', 'translateY(30px)');
        $(this).delay(index * 100).animate({
            opacity: 1
        }, 500).css('transform', 'translateY(0)');
    });

    // Hover effects
    $('.timeline-item').hover(
        function() {
            $(this).addClass('shadow-lg');
        },
        function() {
            $(this).removeClass('shadow-lg');
        }
    );
});
</script>
@endpush
