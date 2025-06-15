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
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .info-row {
            display: table-row;
        }
        .info-item {
            display: table-cell;
            border: 1px solid #ddd;
            padding: 15px;
            background: #f9f9f9;
            width: 50%;
            vertical-align: top;
        }
        .info-item.full-width {
            display: table-cell;
            width: 100%;
        }
        .info-item strong {
            color: #dc3545;
            display: block;
            margin-bottom: 5px;
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
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .media-info {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px;
            border-radius: 5px;
            margin: 5px 0;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="watermark">CEKPENYEWA.COM</div>

    <div class="header">
        <div class="logo">CEKPENYEWA.COM</div>
        <div class="subtitle">Platform Terpercaya untuk Verifikasi Penyewa Rental di Indonesia</div>
        <div class="print-info">
            <strong>Tanggal Export:</strong> {{ \App\Helpers\DateHelper::formatIndonesian(now(), 'l, d F Y') }} - {{ now()->format('H:i') }} |
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
            <div class="info-row">
                <div class="info-item">
                    <strong>Nama Lengkap</strong>
                    {{ $blacklist->nama_lengkap ?: 'Tidak ada data' }}
                </div>
                <div class="info-item">
                    <strong>NIK</strong>
                    {{ $blacklist->nik ?: 'Tidak ada data' }}
                </div>
            </div>
            <div class="info-row">
                <div class="info-item">
                    <strong>Jenis Kelamin</strong>
                    {{ $blacklist->jenis_kelamin ? ($blacklist->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan') : 'Tidak ada data' }}
                </div>
                <div class="info-item">
                    <strong>No. HP</strong>
                    {{ $blacklist->no_hp ?: 'Tidak ada data' }}
                </div>
            </div>
            <div class="info-row">
                <div class="info-item full-width" colspan="2">
                    <strong>Alamat</strong>
                    {{ $blacklist->alamat ?: 'Tidak ada data' }}
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Foto Penyewa -->
    <div class="section">
        <h3>📷 Foto Penyewa</h3>
        @if($blacklist->foto_penyewa && is_array($blacklist->foto_penyewa) && count($blacklist->foto_penyewa) > 0)
            @foreach($blacklist->foto_penyewa as $foto)
                <div class="media-info">
                    📸 <strong>Foto Penyewa:</strong> {{ basename($foto) }}
                    <br><small>Link: {{ asset('storage/' . $foto) }}</small>
                </div>
            @endforeach
        @elseif($blacklist->foto_penyewa && is_string($blacklist->foto_penyewa) && count(json_decode($blacklist->foto_penyewa, true)) > 0)
            @foreach(json_decode($blacklist->foto_penyewa, true) as $foto)
                <div class="media-info">
                    📸 <strong>Foto Penyewa:</strong> {{ basename($foto) }}
                    <br><small>Link: {{ asset('storage/' . $foto) }}</small>
                </div>
            @endforeach
        @else
            <p><em>Tidak ada foto penyewa</em></p>
        @endif
    </div>

    <!-- 3. Foto KTP/SIM -->
    <div class="section">
        <h3>🆔 Foto KTP/SIM</h3>
        @if($blacklist->foto_ktp_sim && is_array($blacklist->foto_ktp_sim) && count($blacklist->foto_ktp_sim) > 0)
            @foreach($blacklist->foto_ktp_sim as $foto)
                <div class="media-info">
                    🆔 <strong>Foto KTP/SIM:</strong> {{ basename($foto) }}
                    <br><small>Link: {{ asset('storage/' . $foto) }}</small>
                </div>
            @endforeach
        @elseif($blacklist->foto_ktp_sim && is_string($blacklist->foto_ktp_sim) && count(json_decode($blacklist->foto_ktp_sim, true)) > 0)
            @foreach(json_decode($blacklist->foto_ktp_sim, true) as $foto)
                <div class="media-info">
                    🆔 <strong>Foto KTP/SIM:</strong> {{ basename($foto) }}
                    <br><small>Link: {{ asset('storage/' . $foto) }}</small>
                </div>
            @endforeach
        @else
            <p><em>Tidak ada foto KTP/SIM</em></p>
        @endif
    </div>

    <!-- 4. Detail Masalah -->
    <div class="section">
        <h3>🚨 Detail Masalah</h3>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-item">
                    <strong>Kategori Rental</strong>
                    {{ $blacklist->jenis_rental ?: 'Tidak ada data' }}
                </div>
                <div class="info-item">
                    <strong>Tanggal Sewa</strong>
                    {{ $blacklist->tanggal_sewa ? $blacklist->tanggal_sewa->format('d/m/Y') : 'Tidak ada data' }}
                </div>
            </div>
            <div class="info-row">
                <div class="info-item">
                    <strong>Tanggal Kejadian</strong>
                    {{ $blacklist->tanggal_kejadian ? $blacklist->tanggal_kejadian->format('d/m/Y') : 'Tidak ada data' }}
                </div>
                <div class="info-item">
                    <strong>Jenis Kendaraan/Barang</strong>
                    {{ $blacklist->jenis_kendaraan ?: 'Tidak ada data' }}
                </div>
            </div>
            <div class="info-row">
                <div class="info-item">
                    <strong>Nomor Polisi</strong>
                    {{ $blacklist->nomor_polisi ?: 'Tidak ada data' }}
                </div>
                <div class="info-item">
                    <strong>Nilai Kerugian</strong>
                    {{ $blacklist->nilai_kerugian ? 'Rp ' . number_format($blacklist->nilai_kerugian, 0, ',', '.') : 'Tidak ada data' }}
                </div>
            </div>
            <div class="info-row">
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
            </div>
            <div class="info-row">
                <div class="info-item full-width">
                    <strong>Kronologi Kejadian</strong>
                    {{ $blacklist->kronologi ?: 'Tidak ada data' }}
                </div>
            </div>
        </div>
    </div>

    <!-- 5. Bukti Pendukung -->
    <div class="section">
        <h3>📎 Bukti Pendukung</h3>
        @if($blacklist->bukti && is_array($blacklist->bukti) && count($blacklist->bukti) > 0)
            @foreach($blacklist->bukti as $bukti)
                @php
                    $fileName = basename($bukti);
                    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                    $isVideo = in_array($extension, ['mp4', 'avi', 'mov', 'wmv', 'mkv']);
                    $isPdf = $extension === 'pdf';
                @endphp
                <div class="media-info">
                    @if($isImage)
                        📸 <strong>Gambar:</strong> {{ $fileName }}
                        <br><small>Link: {{ asset('storage/' . $bukti) }}</small>
                    @elseif($isVideo)
                        🎥 <strong>Video:</strong> {{ $fileName }}
                        <br><small>Link: {{ asset('storage/' . $bukti) }}</small>
                    @elseif($isPdf)
                        📄 <strong>PDF:</strong> {{ $fileName }}
                        <br><small>Link: {{ asset('storage/' . $bukti) }}</small>
                    @else
                        📎 <strong>File:</strong> {{ $fileName }}
                        <br><small>Link: {{ asset('storage/' . $bukti) }}</small>
                    @endif
                </div>
            @endforeach
        @elseif($blacklist->bukti && is_string($blacklist->bukti) && count(json_decode($blacklist->bukti, true)) > 0)
            @foreach(json_decode($blacklist->bukti, true) as $bukti)
                @php
                    $fileName = basename($bukti);
                    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                    $isVideo = in_array($extension, ['mp4', 'avi', 'mov', 'wmv', 'mkv']);
                    $isPdf = $extension === 'pdf';
                @endphp
                <div class="media-info">
                    @if($isImage)
                        📸 <strong>Gambar:</strong> {{ $fileName }}
                        <br><small>Link: {{ asset('storage/' . $bukti) }}</small>
                    @elseif($isVideo)
                        🎥 <strong>Video:</strong> {{ $fileName }}
                        <br><small>Link: {{ asset('storage/' . $bukti) }}</small>
                    @elseif($isPdf)
                        📄 <strong>PDF:</strong> {{ $fileName }}
                        <br><small>Link: {{ asset('storage/' . $bukti) }}</small>
                    @else
                        📎 <strong>File:</strong> {{ $fileName }}
                        <br><small>Link: {{ asset('storage/' . $bukti) }}</small>
                    @endif
                </div>
            @endforeach
        @else
            <p><em>Tidak ada bukti pendukung</em></p>
        @endif
    </div>

    <!-- 4. Informasi Pelapor -->
    <div class="section">
        <h3>👤 Informasi Pelapor</h3>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-item">
                    <strong>Nama Pelapor</strong>
                    {{ $blacklist->user->name ?? 'Tidak ada data' }}
                </div>
                <div class="info-item">
                    <strong>Email</strong>
                    {{ $blacklist->user->email ?? 'Tidak ada data' }}
                </div>
            </div>
            <div class="info-row">
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
    </div>

    <div class="footer">
        <p><strong>CekPenyewa.com</strong> - Platform Verifikasi Penyewa Rental Terpercaya</p>
        <p>Dokumen ini digenerate secara otomatis pada {{ now()->format('d/m/Y H:i:s') }}</p>
        <p><em>Untuk verifikasi keaslian dokumen, silakan kunjungi website resmi kami</em></p>
    </div>
</body>
</html>
