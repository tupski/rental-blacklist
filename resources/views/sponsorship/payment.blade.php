@extends('layouts.main')

@section('title', 'Pembayaran Sponsor')

@section('content')
<div class="bg-light min-vh-100">
    <div class="container py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-4">
                        <h2 class="fw-bold text-primary mb-2">
                            <i class="fas fa-credit-card me-2"></i>
                            Pembayaran Sponsor
                        </h2>
                        <p class="text-muted mb-0">Kelola pembayaran dan status sponsorship Anda</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Payment List -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-list me-2"></i>
                                Daftar Pembayaran
                            </h5>
                            <a href="{{ route('sponsor.kemitraan') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-2"></i>
                                Beli Paket Baru
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($purchases->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Invoice</th>
                                            <th>Paket</th>
                                            <th>Tanggal Pembelian</th>
                                            <th>Jumlah</th>
                                            <th>Status</th>
                                            <th>Batas Pembayaran</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($purchases as $purchase)
                                            <tr>
                                                <td>
                                                    <strong>{{ $purchase->invoice_number }}</strong>
                                                </td>
                                                <td>
                                                    {{ $purchase->sponsorPackage->name }}
                                                    @if($purchase->sponsorPackage->is_popular)
                                                        <span class="badge bg-warning ms-1">
                                                            <i class="fas fa-star"></i>
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>{{ $purchase->created_at->format('d/m/Y H:i') }}</td>
                                                <td>{{ $purchase->formatted_amount }}</td>
                                                <td>{!! $purchase->status_badge !!}</td>
                                                <td>
                                                    @if($purchase->payment_status === 'pending')
                                                        @if($purchase->isExpired())
                                                            <span class="text-danger">
                                                                <i class="fas fa-times-circle"></i>
                                                                Expired
                                                            </span>
                                                        @else
                                                            <span class="text-warning">
                                                                <i class="fas fa-clock"></i>
                                                                {{ $purchase->payment_deadline->format('d/m/Y H:i') }}
                                                            </span>
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('sponsorship.pembayaran.detail', $purchase) }}" 
                                                           class="btn btn-info btn-sm" title="Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        
                                                        @if($purchase->payment_status === 'confirmed')
                                                            <a href="{{ route('sponsorship.pengaturan', $purchase) }}" 
                                                               class="btn btn-success btn-sm" title="Pengaturan">
                                                                <i class="fas fa-cog"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if($purchases->hasPages())
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $purchases->links() }}
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum Ada Pembelian</h5>
                                <p class="text-muted">Anda belum memiliki pembelian paket sponsor</p>
                                <a href="{{ route('sponsor.kemitraan') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>
                                    Beli Paket Sponsor
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Methods Info -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-university me-2"></i>
                            Informasi Pembayaran
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Transfer Bank</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="border rounded p-2 mb-2 text-center">
                                            <strong>BCA</strong><br>
                                            <small>6050381330</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="border rounded p-2 mb-2 text-center">
                                            <strong>BJB</strong><br>
                                            <small>12345869594939</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="border rounded p-2 mb-2 text-center">
                                            <strong>BRI</strong><br>
                                            <small>208319382834</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">E-Wallet</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="border rounded p-2 mb-2 text-center">
                                            <strong>GoPay/Dana</strong><br>
                                            <small>0819-1191-9993</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="border rounded p-2 mb-2 text-center">
                                            <strong>OVO</strong><br>
                                            <small>0822-1121-9993</small>
                                        </div>
                                    </div>
                                </div>
                                <p class="small text-muted mt-2">
                                    <strong>a.n ANGGA DWY SAPUTRA</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
