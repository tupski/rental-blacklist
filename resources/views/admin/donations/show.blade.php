@extends('layouts.admin')

@section('title', 'Detail Donasi')
@section('page-title', 'Detail Donasi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.donasi.indeks') }}">Manajemen Donasi</a></li>
    <li class="breadcrumb-item active">Detail Donasi</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Donasi</h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="30%"><strong>ID Donasi:</strong></td>
                        <td>{{ $donation->id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nama Donatur:</strong></td>
                        <td>{{ $donation->donor_name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>{{ $donation->donor_email }}</td>
                    </tr>
                    <tr>
                        <td><strong>No. Telepon:</strong></td>
                        <td>{{ $donation->donor_phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Provinsi:</strong></td>
                        <td>{{ $donation->donor_province ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Kota:</strong></td>
                        <td>{{ $donation->donor_city ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tipe Donatur:</strong></td>
                        <td>
                            @if($donation->donor_type === 'individual')
                                <span class="badge badge-info">Individual</span>
                            @else
                                <span class="badge badge-warning">Perusahaan</span>
                            @endif
                        </td>
                    </tr>
                    @if($donation->company_name)
                    <tr>
                        <td><strong>Nama Perusahaan:</strong></td>
                        <td>{{ $donation->company_name }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td><strong>Jumlah Donasi:</strong></td>
                        <td><h4 class="text-primary">{{ $donation->formatted_amount }}</h4></td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            @if($donation->status === 'pending')
                                <span class="badge badge-warning px-3 py-2">Pending</span>
                            @elseif($donation->status === 'confirmed')
                                <span class="badge badge-success px-3 py-2">Terkonfirmasi</span>
                            @else
                                <span class="badge badge-danger px-3 py-2">Dibatalkan</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Metode Pembayaran:</strong></td>
                        <td>{{ ucfirst($donation->payment_method) ?? '-' }}</td>
                    </tr>
                    @if($donation->payment_reference)
                    <tr>
                        <td><strong>Referensi Pembayaran:</strong></td>
                        <td>{{ $donation->payment_reference }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td><strong>Tanggal Donasi:</strong></td>
                        <td>{{ $donation->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    @if($donation->paid_at)
                    <tr>
                        <td><strong>Tanggal Bayar:</strong></td>
                        <td>{{ $donation->paid_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    @endif
                    @if($donation->confirmed_at)
                    <tr>
                        <td><strong>Tanggal Konfirmasi:</strong></td>
                        <td>{{ $donation->confirmed_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Dikonfirmasi Oleh:</strong></td>
                        <td>{{ $donation->confirmedBy->name ?? '-' }}</td>
                    </tr>
                    @endif
                </table>

                @if($donation->message)
                <div class="mt-4">
                    <h5>Pesan Donatur:</h5>
                    <div class="alert alert-info">
                        {{ $donation->message }}
                    </div>
                </div>
                @endif

                @if($donation->admin_notes)
                <div class="mt-4">
                    <h5>Catatan Admin:</h5>
                    <div class="alert alert-secondary">
                        {{ $donation->admin_notes }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Aksi</h3>
            </div>
            <div class="card-body">
                @if($donation->status === 'pending')
                    <form action="{{ route('admin.donasi.konfirmasi', $donation) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block"
                                onclick="return confirm('Konfirmasi donasi ini?')">
                            <i class="fas fa-check"></i> Konfirmasi Donasi
                        </button>
                    </form>
                    <form action="{{ route('admin.donasi.tolak', $donation) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-block"
                                onclick="return confirm('Tolak donasi ini?')">
                            <i class="fas fa-times"></i> Tolak Donasi
                        </button>
                    </form>
                @endif

                <a href="{{ route('admin.donasi.indeks') }}" class="btn btn-secondary btn-block">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                </a>

                <hr>

                <form action="{{ route('admin.donasi.hapus', $donation) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-block"
                            onclick="return confirm('Hapus donasi ini secara permanen?\n\nData yang dihapus tidak dapat dikembalikan!')">
                        <i class="fas fa-trash"></i> Hapus Permanen
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Statistik Donatur</h3>
            </div>
            <div class="card-body">
                @php
                    $donorStats = \App\Models\Donation::where('donor_email', $donation->donor_email)
                                                     ->selectRaw('COUNT(*) as total_donations, SUM(amount) as total_amount')
                                                     ->where('status', 'confirmed')
                                                     ->first();
                @endphp
                <div class="text-center">
                    <h4 class="text-primary">{{ $donorStats->total_donations ?? 0 }}</h4>
                    <p class="text-muted">Total Donasi Terkonfirmasi</p>
                </div>
                <hr>
                <div class="text-center">
                    <h4 class="text-success">Rp {{ number_format($donorStats->total_amount ?? 0, 0, ',', '.') }}</h4>
                    <p class="text-muted">Total Nilai Donasi</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
