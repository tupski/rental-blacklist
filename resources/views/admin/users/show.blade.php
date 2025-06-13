@extends('layouts.admin')

@section('title', 'Detail User')
@section('page-title', 'Detail User')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Manajemen User</a></li>
    <li class="breadcrumb-item active">Detail User</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi User</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>ID:</strong></td>
                                <td>{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nama:</strong></td>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Role:</strong></td>
                                <td>
                                    @if($user->role === 'admin')
                                        <span class="badge badge-danger">Admin</span>
                                    @elseif($user->role === 'pengusaha_rental')
                                        <span class="badge badge-warning">Pengusaha Rental</span>
                                    @else
                                        <span class="badge badge-info">User</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status Email:</strong></td>
                                <td>
                                    @if($user->email_verified_at)
                                        <span class="badge badge-success">Terverifikasi</span>
                                        <br><small class="text-muted">{{ $user->email_verified_at->format('d/m/Y H:i') }}</small>
                                    @else
                                        <span class="badge badge-secondary">Belum Verifikasi</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>NIK:</strong></td>
                                <td>{{ $user->nik ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>No. HP:</strong></td>
                                <td>{{ $user->no_hp ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Alamat:</strong></td>
                                <td>{{ $user->alamat ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Daftar:</strong></td>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Terakhir Update:</strong></td>
                                <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aktivitas User -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Aktivitas Terbaru</h3>
            </div>
            <div class="card-body">
                @if($user->balanceTransactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Tipe</th>
                                    <th>Jumlah</th>
                                    <th>Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->balanceTransactions->take(10) as $transaction)
                                <tr>
                                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($transaction->type === 'topup')
                                            <span class="badge badge-success">Topup</span>
                                        @else
                                            <span class="badge badge-danger">Penggunaan</span>
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                    <td>{{ $transaction->description }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Belum ada aktivitas</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Statistik Saldo -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Saldo</h3>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <h4 class="text-primary">{{ $user->getFormattedBalance() }}</h4>
                    <p class="text-muted">Saldo Saat Ini</p>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <h6>{{ $user->balanceTransactions->where('type', 'topup')->count() }}</h6>
                        <small class="text-muted">Total Topup</small>
                    </div>
                    <div class="col-6">
                        <h6>{{ $user->balanceTransactions->where('type', 'usage')->count() }}</h6>
                        <small class="text-muted">Total Penggunaan</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aksi Cepat -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Aksi Cepat</h3>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <form action="{{ route('admin.users.reset-password', $user->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-block" 
                                onclick="return confirm('Reset password user ini?')">
                            <i class="fas fa-key"></i> Reset Password
                        </button>
                    </form>
                    
                    @if($user->role !== 'admin')
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block" 
                                onclick="return confirm('Hapus user ini? Tindakan ini tidak dapat dibatalkan!')">
                            <i class="fas fa-trash"></i> Hapus User
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Data Unlock -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data yang Dibuka</h3>
            </div>
            <div class="card-body">
                @if($user->userUnlocks->count() > 0)
                    <p><strong>{{ $user->userUnlocks->count() }}</strong> data telah dibuka</p>
                    <small class="text-muted">
                        Total biaya: Rp {{ number_format($user->userUnlocks->sum('amount_paid'), 0, ',', '.') }}
                    </small>
                @else
                    <p class="text-muted">Belum ada data yang dibuka</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
