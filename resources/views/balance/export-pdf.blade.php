<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi - {{ $user->name }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #da3544;
            padding-bottom: 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 25px;
            border-radius: 8px;
        }
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #da3544;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .domain {
            font-size: 18px;
            color: #da3544;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
            font-weight: 500;
        }
        .user-info {
            margin-top: 15px;
            font-size: 11px;
            color: #666;
            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .filters-info {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 11px;
        }
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        .summary-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            text-align: center;
        }
        .summary-card .title {
            font-size: 10px;
            color: #666;
            margin-bottom: 5px;
            text-transform: uppercase;
            font-weight: 600;
        }
        .summary-card .amount {
            font-size: 14px;
            font-weight: bold;
            color: #da3544;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th,
        .table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }
        .table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: 500;
        }
        .badge-success { background-color: #d4edda; color: #155724; }
        .badge-info { background-color: #d1ecf1; color: #0c5460; }
        .badge-warning { background-color: #fff3cd; color: #856404; }
        .badge-danger { background-color: #f8d7da; color: #721c24; }
        .text-success { color: #28a745; }
        .text-danger { color: #dc3545; }
        .text-info { color: #17a2b8; }
        .text-warning { color: #ffc107; }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 2px solid #da3544;
            padding-top: 20px;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">CekPenyewa</div>
        <div class="domain">cekpenyewa.com</div>
        <div class="subtitle">Riwayat Transaksi Saldo</div>
        <div class="user-info">
            <strong>Nama Pengguna:</strong> {{ $user->name }}<br>
            <strong>Email:</strong> {{ $user->email }}<br>
            <strong>Tanggal Export:</strong> {{ \App\Helpers\DateHelper::formatIndonesian(now(), 'l, d F Y') }} - {{ now()->format('H:i') }} WIB
        </div>
    </div>

    @if($filters['type'] || $filters['date_from'] || $filters['date_to'])
    <div class="filters-info">
        <strong>Filter yang Diterapkan:</strong><br>
        @if($filters['type'])
            <strong>Tipe Transaksi:</strong> 
            @switch($filters['type'])
                @case('topup') Topup @break
                @case('usage') Penggunaan @break
                @case('refund') Refund @break
                @default {{ ucfirst($filters['type']) }}
            @endswitch
            <br>
        @endif
        @if($filters['date_from'])
            <strong>Dari Tanggal:</strong> {{ \Carbon\Carbon::parse($filters['date_from'])->format('d/m/Y') }}<br>
        @endif
        @if($filters['date_to'])
            <strong>Sampai Tanggal:</strong> {{ \Carbon\Carbon::parse($filters['date_to'])->format('d/m/Y') }}<br>
        @endif
    </div>
    @endif

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <div class="title">Total Topup</div>
            <div class="amount text-success">Rp {{ number_format($totals['total_topup'], 0, ',', '.') }}</div>
        </div>
        <div class="summary-card">
            <div class="title">Total Penggunaan</div>
            <div class="amount text-danger">Rp {{ number_format($totals['total_usage'], 0, ',', '.') }}</div>
        </div>
        <div class="summary-card">
            <div class="title">Total Refund</div>
            <div class="amount text-info">Rp {{ number_format($totals['total_refund'], 0, ',', '.') }}</div>
        </div>
        <div class="summary-card">
            <div class="title">Total Keseluruhan</div>
            <div class="amount">Rp {{ number_format($totals['total_amount'], 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Transactions Table -->
    <table class="table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Tipe</th>
                <th>Deskripsi</th>
                <th>Jumlah</th>
                <th>Saldo Sebelum</th>
                <th>Saldo Sesudah</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
            <tr>
                <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    @switch($transaction->type)
                        @case('topup')
                            <span class="badge badge-success">Topup</span>
                            @break
                        @case('usage')
                            <span class="badge badge-danger">Penggunaan</span>
                            @break
                        @case('refund')
                            <span class="badge badge-info">Refund</span>
                            @break
                        @default
                            <span class="badge badge-warning">{{ ucfirst($transaction->type) }}</span>
                    @endswitch
                </td>
                <td>{{ $transaction->description }}</td>
                <td class="
                    @if($transaction->type === 'topup' || $transaction->type === 'refund') text-success
                    @else text-danger
                    @endif
                ">
                    @if($transaction->type === 'topup' || $transaction->type === 'refund')
                        +Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                    @else
                        -Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                    @endif
                </td>
                <td>Rp {{ number_format($transaction->balance_before, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($transaction->balance_after, 0, ',', '.') }}</td>
                <td>
                    @if($transaction->reference_type === 'topup_request')
                        @php
                            $topup = \App\Models\TopupRequest::find($transaction->reference_id);
                        @endphp
                        @if($topup)
                            @switch($topup->status)
                                @case('pending')
                                    <span class="badge badge-warning">Pending</span>
                                    @break
                                @case('approved')
                                    <span class="badge badge-success">Disetujui</span>
                                    @break
                                @case('rejected')
                                    <span class="badge badge-danger">Ditolak</span>
                                    @break
                                @default
                                    <span class="badge badge-warning">{{ ucfirst($topup->status) }}</span>
                            @endswitch
                        @else
                            <span class="badge badge-success">Selesai</span>
                        @endif
                    @else
                        <span class="badge badge-success">Selesai</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 20px; color: #999;">
                    Tidak ada transaksi ditemukan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div style="margin-bottom: 15px;">
            <strong style="color: #da3544; font-size: 14px;">CekPenyewa.com</strong><br>
            <strong>Platform Terpercaya untuk Verifikasi Penyewa Rental</strong>
        </div>
        <div style="margin-bottom: 10px;">
            Dikembangkan oleh <strong>PT. Indo Web Solution</strong><br>
            Solusi Digital Terdepan untuk Industri Rental Indonesia
        </div>
        <div style="font-size: 9px; color: #999;">
            Dokumen ini digenerate secara otomatis pada {{ \App\Helpers\DateHelper::formatIndonesian(now(), 'l, d F Y') }} pukul {{ now()->format('H:i') }} WIB<br>
            Total {{ count($transactions) }} transaksi dalam periode yang dipilih<br>
            © {{ date('Y') }} PT. Indo Web Solution - Hak Cipta Dilindungi
        </div>
    </div>
</body>
</html>
