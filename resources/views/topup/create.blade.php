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
                                    <i class="fas fa-plus-circle text-success me-3"></i>
                                    Topup Saldo
                                </h1>
                                <p class="text-muted mb-1">
                                    Isi saldo untuk mengakses data blacklist lengkap
                                </p>
                                <p class="text-muted small">
                                    <i class="fas fa-wallet me-1"></i>
                                    Saldo Saat Ini: <strong class="text-success">Rp {{ number_format($currentBalance, 0, ',', '.') }}</strong>
                                </p>
                            </div>
                            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                                <a href="{{ route('topup.index') }}" class="btn btn-outline-primary me-2">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Kembali
                                </a>
                                <a href="{{ route('balance.history') }}" class="btn btn-info">
                                    <i class="fas fa-history me-2"></i>
                                    Riwayat
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

        <form method="POST" action="{{ route('topup.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- Left Column - Package Selection -->
                <div class="col-lg-8">
                    <!-- Quick Packages -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light border-0">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-box text-primary me-2"></i>
                                Paket Topup Cepat
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach($packages as $index => $package)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 {{ $package['popular'] ? 'border-warning' : 'border-light' }} position-relative">
                                        @if($package['popular'])
                                        <div class="position-absolute top-0 start-50 translate-middle">
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-star me-1"></i>
                                                POPULER
                                            </span>
                                        </div>
                                        @endif
                                        <div class="card-body text-center">
                                            <h6 class="card-title">{{ $package['name'] }}</h6>
                                            <div class="mb-2">
                                                <div class="h5 text-primary mb-0">Rp {{ number_format($package['amount'], 0, ',', '.') }}</div>
                                                @if($package['bonus'] > 0)
                                                <small class="text-success">+ Bonus Rp {{ number_format($package['bonus'], 0, ',', '.') }}</small>
                                                @endif
                                            </div>
                                            <div class="mb-3">
                                                <div class="h6 text-success">Total: Rp {{ number_format($package['total'], 0, ',', '.') }}</div>
                                                <small class="text-muted">{{ $package['description'] }}</small>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input package-radio" type="radio" name="package"
                                                       id="package{{ $index }}" value="{{ $package['total'] }}"
                                                       {{ request('amount') == $package['total'] ? 'checked' : '' }}>
                                                <label class="form-check-label" for="package{{ $index }}">
                                                    Pilih Paket
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Custom Amount -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light border-0">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-edit text-info me-2"></i>
                                Jumlah Custom
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-end">
                                <div class="col-md-6">
                                    <label class="form-label">Jumlah Topup (Rp)</label>
                                    <input type="number" class="form-control" name="custom_amount" id="customAmount"
                                           min="10000" step="1000" placeholder="Minimal Rp 10.000"
                                           value="{{ request('amount') && !in_array(request('amount'), array_column($packages, 'total')) ? request('amount') : '' }}">
                                    <div class="form-text">Minimal topup Rp 10.000</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="package" id="customPackage" value="custom"
                                               {{ request('amount') && !in_array(request('amount'), array_column($packages, 'total')) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="customPackage">
                                            Gunakan Jumlah Custom
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light border-0">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-credit-card text-warning me-2"></i>
                                Metode Pembayaran
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="payment_method" id="manual" value="manual" checked>
                                        <label class="form-check-label" for="manual">
                                            <i class="fas fa-university me-2"></i>
                                            Transfer Manual
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Manual Payment Channels -->
                            <div id="manualChannels" class="mt-3">
                                <h6 class="mb-3">Pilih Rekening Tujuan:</h6>
                                <div class="row g-3">
                                    @foreach($paymentMethods['manual']['channels'] as $key => $channel)
                                    <div class="col-md-6">
                                        <div class="card border">
                                            <div class="card-body">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="payment_channel"
                                                           id="channel{{ $key }}" value="{{ $key }}">
                                                    <label class="form-check-label w-100" for="channel{{ $key }}">
                                                        <div class="d-flex align-items-center">
                                                            <div class="me-3">
                                                                <i class="fas fa-university fa-2x text-primary"></i>
                                                            </div>
                                                            <div>
                                                                <div class="fw-bold">{{ $channel['name'] }}</div>
                                                                <div class="text-muted small">{{ $channel['account'] }}</div>
                                                                <div class="text-muted small">a.n {{ $channel['holder'] }}</div>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Summary & Submit -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-receipt me-2"></i>
                                Ringkasan Topup
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span>Jumlah Topup:</span>
                                    <span id="summaryAmount" class="fw-bold">Rp 0</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span>Bonus:</span>
                                    <span id="summaryBonus" class="text-success">Rp 0</span>
                                </div>
                            </div>
                            <hr>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">Total Saldo Diterima:</span>
                                    <span id="summaryTotal" class="fw-bold text-success">Rp 0</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Metode: <span id="summaryMethod">-</span>
                                </small>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-credit-card me-1"></i>
                                    Channel: <span id="summaryChannel">-</span>
                                </small>
                            </div>

                            <button type="submit" class="btn btn-success w-100" id="submitBtn" disabled>
                                <i class="fas fa-paper-plane me-2"></i>
                                Buat Permintaan Topup
                            </button>
                        </div>
                    </div>

                    <!-- Price Info -->
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-header bg-info text-white">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-tags me-2"></i>
                                Harga Detail
                            </h6>
                        </div>
                        <div class="card-body">
                            @foreach($detailPrices as $category => $price)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="small">{{ $category }}:</span>
                                <span class="small fw-bold">Rp {{ number_format($price, 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const packages = @json($packages);

    function updateSummary() {
        let amount = 0;
        let bonus = 0;
        let total = 0;

        // Get selected package or custom amount
        const selectedPackage = $('input[name="package"]:checked').val();

        if (selectedPackage === 'custom') {
            amount = parseInt($('#customAmount').val()) || 0;
            bonus = 0;
            total = amount;
        } else if (selectedPackage) {
            const packageData = packages.find(p => p.total == selectedPackage);
            if (packageData) {
                amount = packageData.amount;
                bonus = packageData.bonus;
                total = packageData.total;
            }
        }

        // Update summary display
        $('#summaryAmount').text('Rp ' + amount.toLocaleString('id-ID'));
        $('#summaryBonus').text('Rp ' + bonus.toLocaleString('id-ID'));
        $('#summaryTotal').text('Rp ' + total.toLocaleString('id-ID'));

        // Update payment method
        const paymentMethod = $('input[name="payment_method"]:checked').next('label').text().trim();
        $('#summaryMethod').text(paymentMethod || '-');

        // Update payment channel
        const paymentChannel = $('input[name="payment_channel"]:checked').next('label').find('.fw-bold').text();
        $('#summaryChannel').text(paymentChannel || '-');

        // Enable/disable submit button
        const canSubmit = total > 0 && $('input[name="payment_method"]:checked').length > 0 && $('input[name="payment_channel"]:checked').length > 0;
        $('#submitBtn').prop('disabled', !canSubmit);
    }

    // Event listeners
    $('input[name="package"]').on('change', function() {
        if ($(this).val() === 'custom') {
            $('#customAmount').focus();
        }
        updateSummary();
    });

    $('#customAmount').on('input', function() {
        if ($(this).val()) {
            $('#customPackage').prop('checked', true);
        }
        updateSummary();
    });

    $('input[name="payment_method"], input[name="payment_channel"]').on('change', updateSummary);

    // Initial update
    updateSummary();

    // Form validation
    $('form').on('submit', function(e) {
        const selectedPackage = $('input[name="package"]:checked').val();
        let amount = 0;

        if (selectedPackage === 'custom') {
            amount = parseInt($('#customAmount').val()) || 0;
            if (amount < 10000) {
                e.preventDefault();
                alert('Jumlah topup minimal Rp 10.000');
                return false;
            }
        } else if (!selectedPackage) {
            e.preventDefault();
            alert('Silakan pilih paket atau masukkan jumlah custom');
            return false;
        }

        if (!$('input[name="payment_method"]:checked').length) {
            e.preventDefault();
            alert('Silakan pilih metode pembayaran');
            return false;
        }

        if (!$('input[name="payment_channel"]:checked').length) {
            e.preventDefault();
            alert('Silakan pilih channel pembayaran');
            return false;
        }
    });
});
</script>
@endpush
