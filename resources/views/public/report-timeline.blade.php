@extends('layouts.main')

@section('title', 'Timeline Laporan - ' . $terlapor->sensored_nama)
@section('meta_description', 'Timeline lengkap laporan blacklist untuk ' . $terlapor->sensored_nama . ' dengan total ' . $totalReports . ' laporan.')

@push('styles')
<style>
    .timeline-hero {
        background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%);
        color: white;
        padding: 4rem 0;
    }
    
    .timeline-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
        margin-bottom: 2rem;
    }
    
    .timeline-card:hover {
        transform: translateY(-5px);
    }
    
    .timeline-main {
        position: relative;
        padding-left: 2rem;
    }
    
    .timeline-main::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(to bottom, #da3544, #b02a37);
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 3rem;
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -2.5rem;
        top: 2rem;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #da3544;
        border: 4px solid white;
        box-shadow: 0 0 0 3px #da3544;
    }
    
    .timeline-date {
        background: linear-gradient(135deg, #da3544 0%, #b02a37 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 1rem;
    }
    
    .stat-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 10px;
        padding: 1.5rem;
        text-align: center;
        border: none;
        height: 100%;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: #da3544;
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
                <div class="d-flex align-items-center mb-3">
                    <div class="me-4">
                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-user-times text-danger" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="display-5 fw-bold mb-2">Timeline Laporan</h1>
                        <h3 class="mb-2">{{ $terlapor->sensored_nama }}</h3>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-light text-dark me-3">
                                <i class="fas fa-id-card me-1"></i>
                                NIK: {{ $terlapor->sensored_nik }}
                            </span>
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-phone me-1"></i>
                                HP: {{ $terlapor->sensored_no_hp }}
                            </span>
                        </div>
                    </div>
                </div>
                <p class="lead mb-0">
                    Riwayat lengkap laporan blacklist diurutkan berdasarkan tanggal kejadian.
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
                    <div class="stat-number">{{ $reportsByCategory->count() }}</div>
                    <div class="text-muted">Kategori Masalah</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-number">
                        {{ $reports->first()->tanggal_kejadian->diffInDays($reports->last()->tanggal_kejadian) }}
                    </div>
                    <div class="text-muted">Hari Rentang</div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <!-- Statistik Jenis Rental -->
            <div class="col-lg-6">
                <div class="timeline-card card">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-pie text-primary me-2"></i>
                            Berdasarkan Jenis Rental
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach($reportsByType as $type => $count)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-medium">{{ $type }}</span>
                                <div class="d-flex align-items-center">
                                    <div class="progress me-3" style="width: 100px; height: 8px;">
                                        <div class="progress-bar bg-primary" 
                                             style="width: {{ ($count / $totalReports) * 100 }}%"></div>
                                    </div>
                                    <span class="badge bg-primary">{{ $count }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Statistik Kategori Masalah -->
            <div class="col-lg-6">
                <div class="timeline-card card">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                            Kategori Masalah
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach($reportsByCategory as $category => $count)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-medium">{{ $category }}</span>
                                <div class="d-flex align-items-center">
                                    <div class="progress me-3" style="width: 100px; height: 8px;">
                                        <div class="progress-bar bg-warning" 
                                             style="width: {{ ($count / $reportsByCategory->sum()) * 100 }}%"></div>
                                    </div>
                                    <span class="badge bg-warning text-dark">{{ $count }}</span>
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
                            <div class="timeline-date">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $report->tanggal_kejadian->format('d F Y') }}
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-lg-8">
                                    <h5 class="fw-bold text-primary mb-3">
                                        Laporan #{{ $totalReports - $index }} - {{ $report->jenis_rental }}
                                    </h5>
                                    
                                    <div class="mb-3">
                                        <h6 class="fw-bold mb-2">Jenis Masalah:</h6>
                                        @if(is_array($report->jenis_laporan))
                                            @foreach($report->jenis_laporan as $jenis)
                                                <span class="badge bg-danger me-1 mb-1">{{ $jenis }}</span>
                                            @endforeach
                                        @else
                                            <span class="badge bg-danger">{{ $report->jenis_laporan }}</span>
                                        @endif
                                    </div>
                                    
                                    @if($report->kronologi)
                                        <div class="mb-3">
                                            <h6 class="fw-bold mb-2">Kronologi:</h6>
                                            <div class="bg-light p-3 rounded">
                                                {{ Str::limit($report->kronologi, 200) }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="col-lg-4">
                                    <div class="bg-light p-3 rounded">
                                        <h6 class="fw-bold mb-3">Detail Laporan</h6>
                                        
                                        <div class="mb-2">
                                            <small class="text-muted">Pelapor:</small><br>
                                            @if($report->user && $report->user->role === 'pengusaha_rental')
                                                <a href="{{ route('rental.profil', $report->user->id) }}" class="reporter-badge">
                                                    <i class="fas fa-building me-1"></i>
                                                    {{ $report->user->name }}
                                                </a>
                                            @else
                                                <span class="badge bg-secondary">{{ $report->user->name ?? 'Tidak diketahui' }}</span>
                                            @endif
                                        </div>
                                        
                                        <div class="mb-2">
                                            <small class="text-muted">Tanggal Lapor:</small><br>
                                            <span class="fw-medium">{{ $report->created_at->format('d M Y H:i') }}</span>
                                        </div>
                                        
                                        <div class="mb-2">
                                            <small class="text-muted">Status:</small><br>
                                            <span class="badge bg-success">{{ $report->status_validitas }}</span>
                                        </div>
                                        
                                        @php
                                            $dangerLevel = 'low';
                                            $dangerText = 'Rendah';
                                            if($report->jenis_laporan && is_array($report->jenis_laporan)) {
                                                $highRiskCategories = ['Penipuan', 'Tidak Mengembalikan', 'Merusak Barang'];
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
                                        
                                        <div>
                                            <small class="text-muted">Tingkat Risiko:</small><br>
                                            <span class="danger-level danger-{{ $dangerLevel }}">{{ $dangerText }}</span>
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
