<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Laporan Blacklist - {{ $blacklist->nama_lengkap }}</title>
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
            position: relative;
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
        .company-info {
            font-size: 11px;
            color: #888;
            margin-top: 15px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .print-info {
            margin-top: 15px;
            font-size: 11px;
            color: #666;
            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section-title {
            background: linear-gradient(135deg, #da3544 0%, #c82333 100%);
            color: white;
            padding: 12px 15px;
            font-weight: bold;
            margin-bottom: 15px;
            border-radius: 5px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .info-row {
            display: table-row;
        }
        .info-item {
            display: table-cell;
            border: 1px solid #e0e0e0;
            vertical-align: top;
            width: 50%;
        }
        .info-label {
            background-color: #f8f9fa;
            padding: 10px 12px;
            font-weight: 600;
            color: #495057;
            border-bottom: 1px solid #e0e0e0;
            font-size: 11px;
        }
        .info-value {
            padding: 12px;
            background-color: white;
            min-height: 20px;
        }
        .info-value.empty {
            color: #999;
            font-style: italic;
        }
        .full-width {
            width: 100%;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            background-color: #da3544;
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
        .badge-info { background-color: #17a2b8; }
        .badge-secondary { background-color: #6c757d; }
        .media-list {
            margin-top: 10px;
        }
        .media-item {
            display: inline-block;
            margin: 5px 10px 5px 0;
            padding: 8px 12px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-size: 11px;
        }
        .media-item.image {
            background-color: #e7f3ff;
            border-color: #b3d9ff;
            color: #0056b3;
        }
        .media-item.video {
            background-color: #fff3cd;
            border-color: #ffeaa7;
            color: #856404;
        }
        .media-item.document {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
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
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(218, 53, 68, 0.08);
            font-weight: bold;
            z-index: -1;
            pointer-events: none;
        }
        .warning-box {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 11px;
        }
        @media print {
            body { margin: 0; }
            .section { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
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
            <strong>Tanggal Cetak:</strong> {{ \App\Helpers\DateHelper::formatIndonesian(now(), 'l, d F Y') }} - {{ now()->format('H:i') }} WIB<br>
            <strong>ID Laporan:</strong> #{{ $blacklist->id }} | <strong>Status:</strong>
            <span class="badge badge-{{ $blacklist->status_validitas === 'Valid' ? 'success' : ($blacklist->status_validitas === 'Pending' ? 'warning' : 'danger') }}">
                {{ $blacklist->status_validitas }}
            </span>
        </div>
    </div>

    <div class="warning-box">
        <strong>⚠️ PERINGATAN PENTING:</strong> Dokumen ini berisi informasi sensitif dan hanya untuk keperluan verifikasi rental.
        Dilarang keras menyebarluaskan atau menggunakan data ini untuk tujuan lain di luar keperluan bisnis rental.
    </div>

    <!-- 1. Informasi Penyewa -->
    <div class="section">
        <div class="section-title">📋 Informasi Penyewa</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-item">
                    <div class="info-label">Nama Lengkap</div>
                    <div class="info-value">{{ $blacklist->nama_lengkap ?: 'Tidak ada data' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">NIK</div>
                    <div class="info-value">{{ $blacklist->nik ?: 'Tidak ada data' }}</div>
                </div>
            </div>
            <div class="info-row">
                <div class="info-item">
                    <div class="info-label">Jenis Kelamin</div>
                    <div class="info-value">{{ $blacklist->jenis_kelamin ? ($blacklist->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan') : 'Tidak ada data' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">No. HP</div>
                    <div class="info-value">{{ $blacklist->no_hp ?: 'Tidak ada data' }}</div>
                </div>
            </div>
            <div class="info-row">
                <div class="info-item full-width" colspan="2">
                    <div class="info-label">Alamat</div>
                    <div class="info-value">{{ $blacklist->alamat ?: 'Tidak ada data' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Foto Penyewa -->
    <div class="section">
        <div class="section-title">📷 Foto Penyewa</div>
        <div class="media-list">
            @if($blacklist->foto_penyewa && count(json_decode($blacklist->foto_penyewa, true)) > 0)
                @foreach(json_decode($blacklist->foto_penyewa, true) as $foto)
                    <span class="media-item image">📸 {{ basename($foto) }}</span>
                @endforeach
            @else
                <span class="info-value empty">Tidak ada foto penyewa</span>
            @endif
        </div>
    </div>

    <!-- 3. Foto KTP/SIM -->
    <div class="section">
        <div class="section-title">🆔 Foto KTP/SIM</div>
        <div class="media-list">
            @if($blacklist->foto_ktp_sim && count(json_decode($blacklist->foto_ktp_sim, true)) > 0)
                @foreach(json_decode($blacklist->foto_ktp_sim, true) as $foto)
                    <span class="media-item image">🆔 {{ basename($foto) }}</span>
                @endforeach
            @else
                <span class="info-value empty">Tidak ada foto KTP/SIM</span>
            @endif
        </div>
    </div>

    <!-- 4. Informasi Pelapor -->
    <div class="section">
        <div class="section-title">🏢 Informasi Pelapor</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-item">
                    <div class="info-label">Nama Perusahaan Rental</div>
                    <div class="info-value">{{ $blacklist->nama_perusahaan_rental ?: 'Tidak ada data' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Nama Penanggung Jawab</div>
                    <div class="info-value">{{ $blacklist->nama_penanggung_jawab ?: 'Tidak ada data' }}</div>
                </div>
            </div>
            <div class="info-row">
                <div class="info-item">
                    <div class="info-label">No. WhatsApp</div>
                    <div class="info-value">{{ $blacklist->no_wa_pelapor ?: 'Tidak ada data' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $blacklist->email_pelapor ?: 'Tidak ada data' }}</div>
                </div>
            </div>
            <div class="info-row">
                <div class="info-item full-width">
                    <div class="info-label">Alamat Usaha</div>
                    <div class="info-value">{{ $blacklist->alamat_usaha ?: 'Tidak ada data' }}</div>
                </div>
            </div>
            <div class="info-row">
                <div class="info-item full-width">
                    <div class="info-label">Website Usaha</div>
                    <div class="info-value">{{ $blacklist->website_usaha ?: 'Tidak ada data' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. Detail Masalah -->
    <div class="section">
        <div class="section-title">🚨 Detail Masalah</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-item">
                    <div class="info-label">Kategori Rental</div>
                    <div class="info-value">
                        <span class="badge badge-info">{{ $blacklist->jenis_rental ?: 'Tidak ada data' }}</span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tanggal Sewa</div>
                    <div class="info-value">{{ $blacklist->tanggal_sewa ? $blacklist->tanggal_sewa->format('d/m/Y') : 'Tidak ada data' }}</div>
                </div>
            </div>
            <div class="info-row">
                <div class="info-item">
                    <div class="info-label">Tanggal Kejadian</div>
                    <div class="info-value">{{ $blacklist->tanggal_kejadian ? $blacklist->tanggal_kejadian->format('d/m/Y') : 'Tidak ada data' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Jenis Kendaraan/Barang</div>
                    <div class="info-value">{{ $blacklist->jenis_kendaraan ?: 'Tidak ada data' }}</div>
                </div>
            </div>
            <div class="info-row">
                <div class="info-item">
                    <div class="info-label">Nomor Polisi</div>
                    <div class="info-value">{{ $blacklist->nomor_polisi ?: 'Tidak ada data' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Nilai Kerugian</div>
                    <div class="info-value">{{ $blacklist->nilai_kerugian ? 'Rp ' . number_format($blacklist->nilai_kerugian, 0, ',', '.') : 'Tidak ada data' }}</div>
                </div>
            </div>
            <div class="info-row">
                <div class="info-item full-width">
                    <div class="info-label">Jenis Laporan</div>
                    <div class="info-value">
                        @if($blacklist->jenis_laporan && count(json_decode($blacklist->jenis_laporan, true)) > 0)
                            @foreach(json_decode($blacklist->jenis_laporan, true) as $jenis)
                                <span class="badge badge-warning">
                                    @switch($jenis)
                                        @case('tidak_mengembalikan')
                                            Tidak Mengembalikan
                                            @break
                                        @case('merusak_barang')
                                            Merusak Barang
                                            @break
                                        @case('tidak_membayar')
                                            Tidak Membayar
                                            @break
                                        @case('menyalahgunakan')
                                            Menyalahgunakan
                                            @break
                                        @case('lainnya')
                                            Lainnya
                                            @break
                                        @default
                                            {{ $jenis }}
                                    @endswitch
                                </span>
                            @endforeach
                        @else
                            <span class="info-value empty">Tidak ada data</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="info-row">
                <div class="info-item full-width">
                    <div class="info-label">Kronologi Kejadian</div>
                    <div class="info-value">{{ $blacklist->kronologi ?: 'Tidak ada data' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 6. Status Penanganan -->
    <div class="section">
        <div class="section-title">⚖️ Status Penanganan</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-item">
                    <div class="info-label">Status Penanganan</div>
                    <div class="info-value">
                        @if($blacklist->status_penanganan && count(json_decode($blacklist->status_penanganan, true)) > 0)
                            @foreach(json_decode($blacklist->status_penanganan, true) as $status)
                                <span class="badge badge-info">
                                    @switch($status)
                                        @case('laporan_polisi')
                                            Laporan Polisi
                                            @break
                                        @case('mediasi')
                                            Mediasi
                                            @break
                                        @case('tuntutan_hukum')
                                            Tuntutan Hukum
                                            @break
                                        @case('blacklist_internal')
                                            Blacklist Internal
                                            @break
                                        @case('tidak_ada_tindakan')
                                            Tidak Ada Tindakan
                                            @break
                                        @default
                                            {{ $status }}
                                    @endswitch
                                </span>
                            @endforeach
                        @else
                            <span class="info-value empty">Tidak ada data</span>
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status Lainnya</div>
                    <div class="info-value">{{ $blacklist->status_lainnya ?: 'Tidak ada data' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 7. Bukti Pendukung -->
    <div class="section">
        <div class="section-title">📎 Bukti Pendukung</div>
        <div class="media-list">
            @if($blacklist->bukti && count(json_decode($blacklist->bukti, true)) > 0)
                @foreach(json_decode($blacklist->bukti, true) as $bukti)
                    @php
                        $fileName = basename($bukti);
                        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                        $isVideo = in_array($extension, ['mp4', 'avi', 'mov', 'wmv']);
                        $isPdf = $extension === 'pdf';
                    @endphp

                    @if($isImage)
                        <span class="media-item image">📸 {{ $fileName }}</span>
                    @elseif($isVideo)
                        <span class="media-item video">🎥 {{ $fileName }} - <strong>Link Unduh:</strong> {{ url('/storage/' . $bukti) }}</span>
                    @elseif($isPdf)
                        <span class="media-item document">📄 {{ $fileName }} - <strong>Link Unduh:</strong> {{ url('/storage/' . $bukti) }}</span>
                    @else
                        <span class="media-item document">📁 {{ $fileName }} - <strong>Link Unduh:</strong> {{ url('/storage/' . $bukti) }}</span>
                    @endif
                @endforeach
            @else
                <span class="info-value empty">Tidak ada bukti pendukung</span>
            @endif
        </div>
    </div>

    <!-- 8. Persetujuan dan Tanda Tangan -->
    <div class="section">
        <div class="section-title">✍️ Persetujuan dan Tanda Tangan</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-item">
                    <div class="info-label">Persetujuan</div>
                    <div class="info-value">{{ $blacklist->persetujuan ? 'Ya' : 'Tidak' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Nama Pelapor (TTD)</div>
                    <div class="info-value">{{ $blacklist->nama_pelapor_ttd ?: 'Tidak ada data' }}</div>
                </div>
            </div>
            <div class="info-row">
                <div class="info-item">
                    <div class="info-label">Tanggal Pelaporan</div>
                    <div class="info-value">{{ $blacklist->tanggal_pelaporan ? $blacklist->tanggal_pelaporan->format('d/m/Y') : 'Tidak ada data' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tipe Pelapor</div>
                    <div class="info-value">{{ $blacklist->tipe_pelapor === 'rental_owner' ? 'Pemilik Rental' : 'Tamu' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 9. Informasi Sistem -->
    <div class="section">
        <div class="section-title">💻 Informasi Sistem</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-item">
                    <div class="info-label">Status Validitas</div>
                    <div class="info-value">
                        <span class="badge badge-{{ $blacklist->status_validitas === 'Valid' ? 'success' : ($blacklist->status_validitas === 'Pending' ? 'warning' : 'danger') }}">
                            {{ $blacklist->status_validitas }}
                        </span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Jumlah Laporan (NIK ini)</div>
                    <div class="info-value">
                        <span class="badge badge-success">{{ App\Models\RentalBlacklist::countReportsByNik($blacklist->nik) }} laporan</span>
                    </div>
                </div>
            </div>
            <div class="info-row">
                <div class="info-item">
                    <div class="info-label">Pelapor</div>
                    <div class="info-value">{{ $blacklist->user->name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Tanggal Dibuat</div>
                    <div class="info-value">{{ $blacklist->created_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

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
            Data telah diverifikasi dan dapat digunakan sebagai referensi untuk keputusan rental<br>
            © {{ date('Y') }} PT. Indo Web Solution - Hak Cipta Dilindungi
        </div>
    </div>
</body>
</html>
