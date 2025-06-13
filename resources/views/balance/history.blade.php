@extends('layouts.main')

@section('title', 'Riwayat Saldo')

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
                                    <i class="fas fa-history text-info me-3"></i>
                                    Riwayat Saldo
                                </h1>
                                <p class="text-muted mb-1">
                                    Lihat semua transaksi saldo Anda
                                </p>
                                <p class="text-muted small">
                                    <i class="fas fa-wallet me-1"></i>
                                    Saldo Saat Ini: <strong class="text-success">Rp {{ number_format($currentBalance, 0, ',', '.') }}</strong>
                                </p>
                            </div>
                            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                                <a href="{{ route('topup.index') }}" class="btn btn-success">
                                    <i class="fas fa-plus me-2"></i>
                                    Topup Saldo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <div class="text-success mb-2">
                            <i class="fas fa-arrow-up fa-2x"></i>
                        </div>
                        <h6 class="card-title">Total Topup</h6>
                        <h5 class="text-success">Rp {{ number_format($stats['total_topup'], 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <div class="text-danger mb-2">
                            <i class="fas fa-arrow-down fa-2x"></i>
                        </div>
                        <h6 class="card-title">Total Penggunaan</h6>
                        <h5 class="text-danger">Rp {{ number_format($stats['total_usage'], 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <div class="text-info mb-2">
                            <i class="fas fa-undo fa-2x"></i>
                        </div>
                        <h6 class="card-title">Total Refund</h6>
                        <h5 class="text-info">Rp {{ number_format($stats['total_refund'], 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <div class="text-warning mb-2">
                            <i class="fas fa-calendar fa-2x"></i>
                        </div>
                        <h6 class="card-title">Bulan Ini</h6>
                        <h5 class="text-warning">Rp {{ number_format($stats['this_month_usage'], 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Tipe Transaksi</label>
                                <select name="type" class="form-select">
                                    <option value="">Semua</option>
                                    <option value="topup" {{ request('type') == 'topup' ? 'selected' : '' }}>Topup</option>
                                    <option value="usage" {{ request('type') == 'usage' ? 'selected' : '' }}>Penggunaan</option>
                                    <option value="refund" {{ request('type') == 'refund' ? 'selected' : '' }}>Refund</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Dari Tanggal</label>
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Sampai Tanggal</label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter me-2"></i>
                                        Filter
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light border-0">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list text-primary me-2"></i>
                            Transaksi ({{ $transactions->total() }})
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @if($transactions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Tipe</th>
                                            <th>Deskripsi</th>
                                            <th>Jumlah</th>
                                            <th>Saldo Sebelum</th>
                                            <th>Saldo Sesudah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transactions as $transaction)
                                        <tr>
                                            <td>
                                                <div>{{ $transaction->created_at->format('d/m/Y') }}</div>
                                                <small class="text-muted">{{ $transaction->created_at->format('H:i') }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $transaction->type_color }}">
                                                    <i class="{{ $transaction->type_icon }} me-1"></i>
                                                    {{ ucfirst($transaction->type) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div>{{ $transaction->description }}</div>
                                                @if($transaction->reference_type && $transaction->reference_id)
                                                    <small class="text-muted">
                                                        Ref: {{ class_basename($transaction->reference_type) }} #{{ $transaction->reference_id }}
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-bold text-{{ $transaction->type_color }}">
                                                    {{ $transaction->type === 'usage' ? '-' : '+' }}{{ $transaction->formatted_amount }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-muted">Rp {{ number_format($transaction->balance_before, 0, ',', '.') }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-bold">Rp {{ number_format($transaction->balance_after, 0, ',', '.') }}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            @if($transactions->hasPages())
                            <div class="card-footer bg-light border-0">
                                {{ $transactions->links() }}
                            </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="fas fa-history text-muted" style="font-size: 3rem;"></i>
                                </div>
                                <h5 class="text-muted">Belum Ada Transaksi</h5>
                                <p class="text-muted">Mulai dengan topup saldo untuk melihat riwayat transaksi</p>
                                <a href="{{ route('topup.index') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>
                                    Topup Sekarang
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
