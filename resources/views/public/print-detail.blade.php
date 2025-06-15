<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Blacklist - {{ $blacklist->nama_lengkap }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #dc3545;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #dc3545;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .content {
            max-width: 800px;
            margin: 0 auto;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        .info-item {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            background: #f9f9f9;
        }
        .info-item strong {
            color: #dc3545;
            display: block;
            margin-bottom: 5px;
        }
        .full-width {
            grid-column: 1 / -1;
        }
        .badges {
            margin: 10px 0;
        }
        .badge {
            display: inline-block;
            background: #ffc107;
            color: #000;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            margin-right: 5px;
            margin-bottom: 5px;
        }
        .kronologi {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        @media print {
            body { margin: 0; }
            .section { page-break-inside: avoid; }
            .no-print { display: none; }
        }
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #da3544;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .print-button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">🖨️ Print</button>
    <div class="watermark">CEKPENYEWA.COM</div>

    <div class="header">
        <div class="logo">CekPenyewa</div>
        <div class="domain">cekpenyewa.com</div>
        <div class="subtitle">Sistem Informasi Blacklist Rental Nasional</div>
        <div class="company-info">
            Dikembangkan oleh <strong>PT. Indo Web Solution</strong><br>
            Platform Terpercaya untuk Verifikasi Penyewa Rental di Indonesia
        </div>
        <div class="print-info">
            <strong>Tanggal Print:</strong> {{ \App\Helpers\DateHelper::formatIndonesian(now(), 'l, d F Y') }} - {{ now()->format('H:i') }} WIB<br>
            <strong>ID Laporan:</strong> #{{ $blacklist->id }} | <strong>Status:</strong>
            <span class="badge badge-{{ $blacklist->status_validitas === 'Valid' ? 'success' : ($blacklist->status_validitas === 'Pending' ? 'warning' : 'danger') }}">
                {{ $blacklist->status_validitas }}
            </span>
        </div>
    </div>

        <div class="warning">
            <strong>PERINGATAN:</strong> Dokumen ini berisi informasi sensitif dan hanya untuk keperluan verifikasi rental.
            Dilarang menyebarluaskan atau menggunakan data ini untuk tujuan lain.
        </div>

        <div class="info-grid">
            <div class="info-item">
                <strong>Nama Lengkap</strong>
                {{ $blacklist->nama_lengkap }}
            </div>
            <div class="info-item">
                <strong>NIK</strong>
                {{ $blacklist->nik }}
            </div>
            <div class="info-item">
                <strong>No HP</strong>
                {{ $blacklist->no_hp }}
            </div>
            <div class="info-item">
                <strong>Jenis Kelamin</strong>
                {{ $blacklist->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
            </div>
            <div class="info-item">
                <strong>Jenis Rental</strong>
                {{ $blacklist->jenis_rental }}
            </div>
            <div class="info-item">
                <strong>Tanggal Kejadian</strong>
                {{ $blacklist->tanggal_kejadian->format('d/m/Y') }}
            </div>
            <div class="info-item">
                <strong>Status Validitas</strong>
                {{ $blacklist->status_validitas }}
            </div>
            <div class="info-item">
                <strong>Jumlah Laporan</strong>
                {{ App\Models\RentalBlacklist::countReportsByNik($blacklist->nik) }} laporan
            </div>
            <div class="info-item full-width">
                <strong>Alamat</strong>
                {{ $blacklist->alamat }}
            </div>
            <div class="info-item full-width">
                <strong>Jenis Laporan</strong>
                <div class="badges">
                    @foreach($blacklist->jenis_laporan as $laporan)
                        <span class="badge">
                            @switch($laporan)
                                @case('percobaan_penipuan')
                                    Percobaan Penipuan
                                    @break
                                @case('penipuan')
                                    Penipuan
                                    @break
                                @case('tidak_mengembalikan_barang')
                                    Tidak Mengembalikan Barang
                                    @break
                                @case('identitas_palsu')
                                    Identitas Palsu
                                    @break
                                @case('sindikat')
                                    Sindikat
                                    @break
                                @case('merusak_barang')
                                    Merusak Barang
                                    @break
                                @default
                                    {{ $laporan }}
                            @endswitch
                        </span>
                    @endforeach
                </div>
            </div>
            <div class="info-item full-width">
                <strong>Kronologi Kejadian</strong>
                <div class="kronologi">
                    {{ $blacklist->kronologi }}
                </div>
            </div>
            <div class="info-item full-width">
                <strong>Dilaporkan Oleh</strong>
                {{ $blacklist->user->name }}
                <br><small>Tanggal Laporan: {{ $blacklist->created_at->format('d/m/Y H:i:s') }}</small>
            </div>
        </div>

        <div class="footer">
            <p><strong>Sistem Blacklist Rental Indonesia</strong></p>
            <p>Data ini telah diverifikasi dan dapat digunakan sebagai referensi untuk keputusan rental</p>
            <p>Untuk informasi lebih lanjut, kunjungi website resmi kami</p>
        </div>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
