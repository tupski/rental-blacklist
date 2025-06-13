@extends('layouts.main')

@section('title', 'Topup Saldo')

@section('content')
<div class="bg-light min-vh-100">
    <div class="container py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <h1 class="display-6 fw-bold text-dark mb-2">
                                    <i class="fas fa-wallet text-success me-3"></i>
                                    Topup Saldo
                                </h1>
                                <p class="text-muted mb-1">
                                    Isi saldo untuk melihat detail data blacklist
                                </p>
                                <p class="text-muted small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Saldo Anda saat ini: <strong class="text-success">Rp {{ number_format($currentBalance, 0, ',', '.') }}</strong>
                                </p>
                            </div>
                            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                                <a href="{{ route('balance.history') }}" class="btn btn-outline-primary me-2">
                                    <i class="fas fa-history me-2"></i>
                                    Riwayat Saldo
                                </a>
                                <a href="{{ route('topup.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus me-2"></i>
                                    Topup Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Pricing Info -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white border-0">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-tags me-2"></i>
                            Harga Detail
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach($detailPrices as $category => $price)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">{{ $category }}</span>
                            <span class="fw-bold text-primary">Rp {{ number_format($price, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                        
                        <hr>
                        <div class="alert alert-info mb-0">
                            <small>
                                <i class="fas fa-info-circle me-1"></i>
                                Detail yang sudah dilihat dapat diakses kembali tanpa memotong saldo
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Balance Calculator -->
                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-header bg-success text-white border-0">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-calculator me-2"></i>
                            Kalkulator Saldo
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select class="form-select" id="categorySelect">
                                @foreach($detailPrices as $category => $price)
                                <option value="{{ $price }}">{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah Detail</label>
                            <input type="number" class="form-control" id="detailCount" value="10" min="1">
                        </div>
                        <div class="alert alert-light border">
                            <strong>Total Biaya: <span id="totalCost">Rp 15.000</span></strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Topup Packages -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light border-0">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-gift text-warning me-2"></i>
                            Paket Topup
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach($packages as $package)
                            <div class="col-md-6">
                                <div class="card border {{ $package['popular'] ? 'border-warning' : '' }} h-100">
                                    @if($package['popular'])
                                    <div class="card-header bg-warning text-dark text-center border-0">
                                        <small class="fw-bold">
                                            <i class="fas fa-star me-1"></i>
                                            PALING POPULER
                                        </small>
                                    </div>
                                    @endif
                                    <div class="card-body text-center">
                                        <h6 class="card-title fw-bold">{{ $package['name'] }}</h6>
                                        <div class="mb-3">
                                            <div class="h4 text-primary mb-0">Rp {{ number_format($package['amount'], 0, ',', '.') }}</div>
                                            @if($package['bonus'] > 0)
                                            <small class="text-success">+ Bonus Rp {{ number_format($package['bonus'], 0, ',', '.') }}</small>
                                            @endif
                                        </div>
                                        <div class="mb-3">
                                            <div class="h5 text-success">Total: Rp {{ number_format($package['total'], 0, ',', '.') }}</div>
                                            <small class="text-muted">{{ $package['description'] }}</small>
                                        </div>
                                        <a href="{{ route('topup.create', ['amount' => $package['total']]) }}" 
                                           class="btn {{ $package['popular'] ? 'btn-warning' : 'btn-outline-primary' }} w-100">
                                            <i class="fas fa-shopping-cart me-2"></i>
                                            Pilih Paket
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('topup.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                Topup Jumlah Custom
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Topups -->
        @if($recentTopups->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light border-0">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-clock text-info me-2"></i>
                            Topup Terbaru
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Invoice</th>
                                        <th>Jumlah</th>
                                        <th>Metode</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTopups as $topup)
                                    <tr>
                                        <td>
                                            <code>{{ $topup->invoice_number }}</code>
                                        </td>
                                        <td>
                                            <strong>{{ $topup->formatted_amount }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ ucfirst($topup->payment_method) }}</span>
                                            @if($topup->payment_channel)
                                                <br><small class="text-muted">{{ $topup->payment_channel }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $topup->status_color }}">
                                                {{ $topup->status_text }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>{{ $topup->created_at->format('d/m/Y H:i') }}</small>
                                        </td>
                                        <td>
                                            @if($topup->canBePaid())
                                                <a href="{{ route('topup.confirm', $topup->invoice_number) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-credit-card me-1"></i>
                                                    Bayar
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('categorySelect');
    const detailCount = document.getElementById('detailCount');
    const totalCost = document.getElementById('totalCost');

    function calculateCost() {
        const price = parseInt(categorySelect.value);
        const count = parseInt(detailCount.value) || 0;
        const total = price * count;
        totalCost.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    categorySelect.addEventListener('change', calculateCost);
    detailCount.addEventListener('input', calculateCost);
});
</script>
@endsection
