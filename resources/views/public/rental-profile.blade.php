@extends('layouts.main')

@section('title', 'Profil ' . $rental->name)
@section('meta_description', 'Profil publik ' . $rental->name . ' - Pemilik rental terpercaya dengan ' . $totalReports . ' laporan blacklist.')

@push('styles')
<style>
    .profile-hero {
        background: linear-gradient(135deg, #da3544 0%, #b02a37 100%);
        color: white;
        padding: 4rem 0;
    }
    
    .profile-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }
    
    .profile-card:hover {
        transform: translateY(-5px);
    }
    
    .stat-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 10px;
        padding: 1.5rem;
        text-align: center;
        border: none;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: #da3544;
    }
    
    .timeline-item {
        border-left: 3px solid #da3544;
        padding-left: 1rem;
        margin-bottom: 2rem;
        position: relative;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -8px;
        top: 0;
        width: 13px;
        height: 13px;
        border-radius: 50%;
        background: #da3544;
    }
    
    .verified-badge {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<!-- Profile Hero Section -->
<section class="profile-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-3">
                    <div class="me-4">
                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-building text-primary" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="display-5 fw-bold mb-2">{{ $rental->name }}</h1>
                        <div class="d-flex align-items-center">
                            <span class="verified-badge me-3">
                                <i class="fas fa-check-circle me-1"></i>
                                Rental Terverifikasi
                            </span>
                            <small class="opacity-75">
                                <i class="fas fa-calendar me-1"></i>
                                Bergabung {{ $rental->created_at->format('F Y') }}
                            </small>
                        </div>
                    </div>
                </div>
                <p class="lead mb-0">
                    Pemilik rental terpercaya yang telah berkontribusi dalam menjaga keamanan komunitas rental Indonesia.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="stat-card bg-white text-dark">
                    <div class="stat-number">{{ $totalReports }}</div>
                    <div class="text-muted">Total Laporan</div>
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
                    <div class="text-muted">Total Laporan</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-number">{{ $reportsByType->count() }}</div>
                    <div class="text-muted">Jenis Rental</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-number">{{ $monthlyStats->sum('total') }}</div>
                    <div class="text-muted">6 Bulan Terakhir</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-number">{{ $rental->created_at->diffInMonths(now()) }}</div>
                    <div class="text-muted">Bulan Aktif</div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Laporan Berdasarkan Jenis Rental -->
            <div class="col-lg-6">
                <div class="profile-card card h-100">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-pie text-primary me-2"></i>
                            Laporan Berdasarkan Jenis Rental
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($reportsByType->count() > 0)
                            @foreach($reportsByType as $type)
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fw-medium">{{ $type->jenis_rental }}</span>
                                    <div class="d-flex align-items-center">
                                        <div class="progress me-3" style="width: 100px; height: 8px;">
                                            <div class="progress-bar bg-primary" 
                                                 style="width: {{ ($type->total / $totalReports) * 100 }}%"></div>
                                        </div>
                                        <span class="badge bg-primary">{{ $type->total }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted text-center py-3">Belum ada laporan</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Aktivitas 6 Bulan Terakhir -->
            <div class="col-lg-6">
                <div class="profile-card card h-100">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-line text-success me-2"></i>
                            Aktivitas 6 Bulan Terakhir
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($monthlyStats->count() > 0)
                            @foreach($monthlyStats as $stat)
                                @php
                                    $monthName = \Carbon\Carbon::create($stat->year, $stat->month)->format('F Y');
                                @endphp
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fw-medium">{{ $monthName }}</span>
                                    <div class="d-flex align-items-center">
                                        <div class="progress me-3" style="width: 100px; height: 8px;">
                                            <div class="progress-bar bg-success" 
                                                 style="width: {{ $monthlyStats->max('total') > 0 ? ($stat->total / $monthlyStats->max('total')) * 100 : 0 }}%"></div>
                                        </div>
                                        <span class="badge bg-success">{{ $stat->total }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted text-center py-3">Belum ada aktivitas</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Recent Reports Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="profile-card card">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-clock text-warning me-2"></i>
                            Laporan Terbaru
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($recentReports->count() > 0)
                            @foreach($recentReports as $report)
                                <div class="timeline-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1">{{ $report->sensored_nama }}</h6>
                                            <p class="text-muted mb-2">
                                                <i class="fas fa-car me-1"></i>{{ $report->jenis_rental }} • 
                                                <i class="fas fa-calendar me-1"></i>{{ $report->tanggal_kejadian->format('d M Y') }}
                                            </p>
                                            <div class="mb-2">
                                                @if(is_array($report->jenis_laporan))
                                                    @foreach($report->jenis_laporan as $jenis)
                                                        <span class="badge bg-warning text-dark me-1">{{ $jenis }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="badge bg-warning text-dark">{{ $report->jenis_laporan }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ $report->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @endforeach
                            
                            @if($totalReports > 5)
                                <div class="text-center mt-4">
                                    <a href="{{ route('beranda') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-search me-2"></i>
                                        Lihat Semua Laporan
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">Belum ada laporan</h6>
                                <p class="text-muted">Rental ini belum membuat laporan blacklist</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Add smooth animations
    $('.stat-card').hover(
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
