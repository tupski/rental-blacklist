@extends('layouts.admin')

@section('title', 'Detail Paket Sponsor')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-eye mr-2"></i>
                    Detail Paket Sponsor: {{ $sponsorPackage->name }}
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.paket-sponsor.edit', $sponsorPackage) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('admin.paket-sponsor.indeks') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Informasi Dasar -->
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Nama Paket:</th>
                                <td>{{ $sponsorPackage->name }}</td>
                            </tr>
                            <tr>
                                <th>Harga:</th>
                                <td>{{ $sponsorPackage->formatted_price }}</td>
                            </tr>
                            <tr>
                                <th>Masa Berlaku:</th>
                                <td>{{ $sponsorPackage->formatted_duration }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    @if($sponsorPackage->is_active)
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-secondary">Nonaktif</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Badge Populer:</th>
                                <td>
                                    @if($sponsorPackage->is_popular)
                                        <span class="badge badge-warning">
                                            <i class="fas fa-star"></i> Populer
                                        </span>
                                    @else
                                        <span class="badge badge-light">Tidak</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Urutan Tampil:</th>
                                <td>{{ $sponsorPackage->sort_order }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Pengaturan Logo -->
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="50%">Maksimal Ukuran Logo:</th>
                                <td>{{ number_format($sponsorPackage->max_logo_size_kb) }} KB</td>
                            </tr>
                            <tr>
                                <th>Ukuran Logo Direkomendasikan:</th>
                                <td>{{ $sponsorPackage->recommended_logo_size }} px</td>
                            </tr>
                            <tr>
                                <th>Dibuat:</th>
                                <td>{{ $sponsorPackage->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Diperbarui:</th>
                                <td>{{ $sponsorPackage->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Deskripsi -->
                    @if($sponsorPackage->description)
                    <div class="col-12">
                        <h5>Deskripsi Paket</h5>
                        <div class="alert alert-info">
                            {{ $sponsorPackage->description }}
                        </div>
                    </div>
                    @endif

                    <!-- Keuntungan -->
                    <div class="col-md-6">
                        <h5>Keuntungan yang Didapat</h5>
                        <ul class="list-group">
                            @foreach($sponsorPackage->benefits as $benefit)
                                <li class="list-group-item">
                                    <i class="fas fa-check text-success mr-2"></i>
                                    {{ $benefit }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Opsi Penempatan -->
                    <div class="col-md-6">
                        <h5>Opsi Penempatan</h5>
                        <ul class="list-group">
                            @foreach($sponsorPackage->placement_options as $placement)
                                <li class="list-group-item">
                                    <i class="fas fa-map-marker-alt text-primary mr-2"></i>
                                    @switch($placement)
                                        @case('home_top')
                                            Beranda Atas
                                            @break
                                        @case('home_bottom')
                                            Beranda Bawah
                                            @break
                                        @case('footer')
                                            Footer
                                            @break
                                        @case('sidebar')
                                            Sidebar
                                            @break
                                        @default
                                            {{ ucfirst($placement) }}
                                    @endswitch
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Statistik Pembelian -->
                    <div class="col-12 mt-4">
                        <h5>Statistik Pembelian</h5>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info">
                                        <i class="fas fa-shopping-cart"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Pembelian</span>
                                        <span class="info-box-number">{{ $sponsorPackage->purchases->count() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success">
                                        <i class="fas fa-check-circle"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Aktif</span>
                                        <span class="info-box-number">{{ $sponsorPackage->purchases->where('payment_status', 'confirmed')->where('expires_at', '>', now())->count() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-warning">
                                        <i class="fas fa-clock"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Pending</span>
                                        <span class="info-box-number">{{ $sponsorPackage->purchases->where('payment_status', 'pending')->count() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box">
                                    <span class="info-box-icon bg-primary">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Pendapatan</span>
                                        <span class="info-box-number">Rp {{ number_format($sponsorPackage->purchases->where('payment_status', 'confirmed')->sum('amount'), 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
