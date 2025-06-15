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
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section h3 {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 10px 15px;
            font-weight: bold;
            margin-bottom: 15px;
            border-radius: 5px;
            font-size: 14px;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            background-color: #dc3545;
            color: white;
            border-radius: 4px;
            font-size: 10px;
            margin-right: 5px;
            margin-bottom: 3px;
            font-weight: 500;
        }
        .badge-success { background-color: #28a745; }
        .badge-warning { background-color: #ffc107; color: #000; }
        .badge-danger { background-color: #dc3545; }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(218, 53, 68, 0.15);
            font-weight: bold;
            z-index: 1000;
            pointer-events: none;
            opacity: 0.3;
        }
        .media-image {
            max-width: 200px;
            max-height: 150px;
            margin: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .video-thumbnail {
            position: relative;
            display: inline-block;
            margin: 5px;
        }
        .video-thumbnail img {
            max-width: 200px;
            max-height: 150px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .video-play-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 30px;
            color: white;
            background: rgba(0,0,0,0.7);
            border-radius: 50%;
            padding: 10px;
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">🖨️ Print</button>
    <div class="watermark">CEKPENYEWA.COM</div>

    <div class="header">
        <div class="logo">CEKPENYEWA.COM</div>
        <div class="subtitle">Platform Terpercaya untuk Verifikasi Penyewa Rental di Indonesia</div>
        <div class="print-info">
            <strong>Tanggal Print:</strong> {{ \App\Helpers\DateHelper::formatIndonesian(now(), 'l, d F Y') }} - {{ now()->format('H:i') }} |
            <strong>Status:</strong>
            @if($blacklist->status_validitas === 'Valid')
                <span class="badge badge-success">Valid</span>
            @elseif($blacklist->status_validitas === 'Pending')
                <span class="badge badge-warning">Dalam Verifikasi</span>
            @else
                <span class="badge badge-danger">Invalid</span>
            @endif
        </div>
    </div>

        <div class="warning">
            <strong>PERINGATAN:</strong> Dokumen ini berisi informasi sensitif dan hanya untuk keperluan verifikasi rental.
            Dilarang menyebarluaskan atau menggunakan data ini untuk tujuan lain.
        </div>

        <!-- 1. Informasi Penyewa -->
        <div class="section">
            <h3>📋 Informasi Penyewa</h3>
            <div class="info-grid">
                <div class="info-item">
                    <strong>Nama Lengkap</strong>
                    {{ $blacklist->nama_lengkap ?: 'Tidak ada data' }}
                </div>
                <div class="info-item">
                    <strong>NIK</strong>
                    {{ $blacklist->nik ?: 'Tidak ada data' }}
                </div>
                <div class="info-item">
                    <strong>Jenis Kelamin</strong>
                    {{ $blacklist->jenis_kelamin ? ($blacklist->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan') : 'Tidak ada data' }}
                </div>
                <div class="info-item">
                    <strong>No. HP</strong>
                    {{ $blacklist->no_hp ?: 'Tidak ada data' }}
                </div>
                <div class="info-item full-width">
                    <strong>Alamat</strong>
                    {{ $blacklist->alamat ?: 'Tidak ada data' }}
                </div>
            </div>
        </div>

        <!-- 2. Detail Masalah -->
        <div class="section">
            <h3>🚨 Detail Masalah</h3>
            <div class="info-grid">
                <div class="info-item">
                    <strong>Kategori Rental</strong>
                    {{ $blacklist->jenis_rental ?: 'Tidak ada data' }}
                </div>
                <div class="info-item">
                    <strong>Tanggal Kejadian</strong>
                    {{ $blacklist->tanggal_kejadian ? $blacklist->tanggal_kejadian->format('d/m/Y') : 'Tidak ada data' }}
                </div>
                <div class="info-item full-width">
                    <strong>Jenis Laporan</strong>
                    @if($blacklist->jenis_laporan && is_array($blacklist->jenis_laporan) && count($blacklist->jenis_laporan) > 0)
                        @foreach($blacklist->jenis_laporan as $jenis)
                            <span class="badge">{{ $jenis }}</span>
                        @endforeach
                    @else
                        Tidak ada data
                    @endif
                </div>
                <div class="info-item full-width">
                    <strong>Kronologi Kejadian</strong>
                    {{ $blacklist->kronologi ?: 'Tidak ada data' }}
                </div>
            </div>
        </div>

        <!-- 3. Bukti Pendukung -->
        <div class="section">
            <h3>📎 Bukti Pendukung</h3>
            @if($blacklist->bukti && is_array($blacklist->bukti) && count($blacklist->bukti) > 0)
                @foreach($blacklist->bukti as $bukti)
                    @php
                        $fileName = basename($bukti);
                        $extension = strtolower(pathinfo($bukti, PATHINFO_EXTENSION));
                        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                        $isVideo = in_array($extension, ['mp4', 'avi', 'mov', 'wmv', 'flv']);
                    @endphp
                    @if($isImage)
                        <div style="margin-bottom: 10px;">
                            <img src="{{ asset('storage/' . $bukti) }}" alt="Bukti Pendukung" class="media-image">
                            <br><small>📸 {{ $fileName }}</small>
                        </div>
                    @elseif($isVideo)
                        <div style="margin-bottom: 10px;">
                            <div class="video-thumbnail">
                                <div style="width: 200px; height: 150px; background: #f0f0f0; border: 1px solid #ddd; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                    <div class="video-play-icon">▶</div>
                                </div>
                            </div>
                            <br><small>🎥 {{ $fileName }}</small>
                        </div>
                    @else
                        <p>📎 {{ $fileName }}</p>
                    @endif
                @endforeach
            @else
                <p><em>Tidak ada bukti pendukung</em></p>
            @endif
        </div>

        <!-- 4. Informasi Pelapor -->
        <div class="section">
            <h3>👤 Informasi Pelapor</h3>
            <div class="info-grid">
                <div class="info-item">
                    <strong>Nama Pelapor</strong>
                    {{ $blacklist->user->name ?? 'Tidak ada data' }}
                </div>
                <div class="info-item">
                    <strong>Email</strong>
                    {{ $blacklist->user->email ?? 'Tidak ada data' }}
                </div>
                <div class="info-item">
                    <strong>Tanggal Laporan</strong>
                    {{ $blacklist->created_at ? $blacklist->created_at->format('d/m/Y H:i') : 'Tidak ada data' }}
                </div>
                <div class="info-item">
                    <strong>Status Validitas</strong>
                    @if($blacklist->status_validitas === 'Valid')
                        <span class="badge badge-success">Valid</span>
                    @elseif($blacklist->status_validitas === 'Pending')
                        <span class="badge badge-warning">Dalam Verifikasi</span>
                    @else
                        <span class="badge badge-danger">Invalid</span>
                    @endif
                </div>
            </div>
        </div>

    <div class="footer">
        <p><strong>CekPenyewa.com</strong> - Platform Verifikasi Penyewa Rental Terpercaya</p>
        <p>Dokumen ini digenerate secara otomatis pada {{ now()->format('d/m/Y H:i:s') }}</p>
        <p><em>Untuk verifikasi keaslian dokumen, silakan kunjungi website resmi kami</em></p>
    </div>
</body>
</html>
