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

        <!-- 2. Foto Penyewa -->
        <div class="section">
            <h3>📷 Foto Penyewa</h3>
            @if($blacklist->foto_penyewa && is_array($blacklist->foto_penyewa) && count($blacklist->foto_penyewa) > 0)
                @foreach($blacklist->foto_penyewa as $foto)
                    @php
                        $extension = strtolower(pathinfo($foto, PATHINFO_EXTENSION));
                        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                    @endphp
                    @if($isImage)
                        <div style="margin-bottom: 10px;">
                            <img src="{{ asset('storage/' . $foto) }}" alt="Foto Penyewa" class="media-image">
                            <br><small>📸 {{ basename($foto) }}</small>
                        </div>
                    @else
                        <p>📸 {{ basename($foto) }}</p>
                    @endif
                @endforeach
            @elseif($blacklist->foto_penyewa && is_string($blacklist->foto_penyewa) && count(json_decode($blacklist->foto_penyewa, true)) > 0)
                @foreach(json_decode($blacklist->foto_penyewa, true) as $foto)
                    @php
                        $extension = strtolower(pathinfo($foto, PATHINFO_EXTENSION));
                        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                    @endphp
                    @if($isImage)
                        <div style="margin-bottom: 10px;">
                            <img src="{{ asset('storage/' . $foto) }}" alt="Foto Penyewa" class="media-image">
                            <br><small>📸 {{ basename($foto) }}</small>
                        </div>
                    @else
                        <p>📸 {{ basename($foto) }}</p>
                    @endif
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
                    @php
                        $extension = strtolower(pathinfo($foto, PATHINFO_EXTENSION));
                        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                    @endphp
                    @if($isImage)
                        <div style="margin-bottom: 10px;">
                            <img src="{{ asset('storage/' . $foto) }}" alt="Foto KTP/SIM" class="media-image">
                            <br><small>🆔 {{ basename($foto) }}</small>
                        </div>
                    @else
                        <p>🆔 {{ basename($foto) }}</p>
                    @endif
                @endforeach
            @elseif($blacklist->foto_ktp_sim && is_string($blacklist->foto_ktp_sim) && count(json_decode($blacklist->foto_ktp_sim, true)) > 0)
                @foreach(json_decode($blacklist->foto_ktp_sim, true) as $foto)
                    @php
                        $extension = strtolower(pathinfo($foto, PATHINFO_EXTENSION));
                        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                    @endphp
                    @if($isImage)
                        <div style="margin-bottom: 10px;">
                            <img src="{{ asset('storage/' . $foto) }}" alt="Foto KTP/SIM" class="media-image">
                            <br><small>🆔 {{ basename($foto) }}</small>
                        </div>
                    @else
                        <p>🆔 {{ basename($foto) }}</p>
                    @endif
                @endforeach
            @else
                <p><em>Tidak ada foto KTP/SIM</em></p>
            @endif
        </div>

        <!-- 4. Informasi Pelapor -->
        <div class="section">
            <h3>🏢 Informasi Pelapor</h3>
            <div class="info-grid">
                <div class="info-item">
                    <strong>Nama Perusahaan Rental</strong>
                    {{ $blacklist->nama_perusahaan_rental ?: 'Tidak ada data' }}
                </div>
                <div class="info-item">
                    <strong>Nama Penanggung Jawab</strong>
                    {{ $blacklist->nama_penanggung_jawab ?: 'Tidak ada data' }}
                </div>
                <div class="info-item">
                    <strong>No. WhatsApp</strong>
                    {{ $blacklist->no_wa_pelapor ?: 'Tidak ada data' }}
                </div>
                <div class="info-item">
                    <strong>Email</strong>
                    {{ $blacklist->email_pelapor ?: 'Tidak ada data' }}
                </div>
                <div class="info-item full-width">
                    <strong>Alamat Usaha</strong>
                    {{ $blacklist->alamat_usaha ?: 'Tidak ada data' }}
                </div>
                <div class="info-item full-width">
                    <strong>Website Usaha</strong>
                    {{ $blacklist->website_usaha ?: 'Tidak ada data' }}
                </div>
            </div>
        </div>

        <!-- 5. Detail Masalah -->
        <div class="section">
            <h3>🚨 Detail Masalah</h3>
            <div class="info-grid">
                <div class="info-item">
                    <strong>Kategori Rental</strong>
                    {{ $blacklist->jenis_rental ?: 'Tidak ada data' }}
                </div>
                <div class="info-item">
                    <strong>Tanggal Sewa</strong>
                    {{ $blacklist->tanggal_sewa ? $blacklist->tanggal_sewa->format('d/m/Y') : 'Tidak ada data' }}
                </div>
                <div class="info-item">
                    <strong>Tanggal Kejadian</strong>
                    {{ $blacklist->tanggal_kejadian ? $blacklist->tanggal_kejadian->format('d/m/Y') : 'Tidak ada data' }}
                </div>
                <div class="info-item">
                    <strong>Jenis Kendaraan/Barang</strong>
                    {{ $blacklist->jenis_kendaraan ?: 'Tidak ada data' }}
                </div>
                <div class="info-item">
                    <strong>Nomor Polisi</strong>
                    {{ $blacklist->nomor_polisi ?: 'Tidak ada data' }}
                </div>
                <div class="info-item">
                    <strong>Nilai Kerugian</strong>
                    {{ $blacklist->nilai_kerugian ? 'Rp ' . number_format($blacklist->nilai_kerugian, 0, ',', '.') : 'Tidak ada data' }}
                </div>
                <div class="info-item full-width">
                    <strong>Jenis Laporan</strong>
                    @if($blacklist->jenis_laporan && is_array($blacklist->jenis_laporan) && count($blacklist->jenis_laporan) > 0)
                        @foreach($blacklist->jenis_laporan as $jenis)
                            <span class="badge">
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
                    @elseif($blacklist->jenis_laporan && is_string($blacklist->jenis_laporan) && count(json_decode($blacklist->jenis_laporan, true)) > 0)
                        @foreach(json_decode($blacklist->jenis_laporan, true) as $jenis)
                            <span class="badge">
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
                        Tidak ada data
                    @endif
                </div>
                <div class="info-item full-width">
                    <strong>Kronologi Kejadian</strong>
                    {{ $blacklist->kronologi ?: 'Tidak ada data' }}
                </div>
            </div>
        </div>

        <!-- 6. Status Penanganan -->
        <div class="section">
            <h3>⚖️ Status Penanganan</h3>
            <div class="info-grid">
                <div class="info-item">
                    <strong>Status Penanganan</strong>
                    @if($blacklist->status_penanganan && is_array($blacklist->status_penanganan) && count($blacklist->status_penanganan) > 0)
                        @foreach($blacklist->status_penanganan as $status)
                            <span class="badge">
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
                    @elseif($blacklist->status_penanganan && is_string($blacklist->status_penanganan) && count(json_decode($blacklist->status_penanganan, true)) > 0)
                        @foreach(json_decode($blacklist->status_penanganan, true) as $status)
                            <span class="badge">
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
                        Tidak ada data
                    @endif
                </div>
                <div class="info-item">
                    <strong>Status Lainnya</strong>
                    {{ $blacklist->status_lainnya ?: 'Tidak ada data' }}
                </div>
            </div>
        </div>

        <!-- 7. Bukti Pendukung -->
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

                    @if($isImage)
                        <div style="margin-bottom: 10px;">
                            <img src="{{ asset('storage/' . $bukti) }}" alt="Bukti Pendukung" class="media-image">
                            <br><small>📸 {{ $fileName }}</small>
                        </div>
                    @elseif($isVideo)
                        <div class="video-thumbnail" style="margin-bottom: 10px;">
                            <video width="200" height="150" style="border: 1px solid #ddd; border-radius: 4px;">
                                <source src="{{ asset('storage/' . $bukti) }}" type="video/{{ $extension }}">
                                Video tidak dapat ditampilkan
                            </video>
                            <div class="video-play-icon">▶</div>
                            <br><small>🎥 {{ $fileName }}</small>
                            <br><small><strong>Link:</strong> {{ url('/storage/' . $bukti) }}</small>
                        </div>
                    @elseif($isPdf)
                        <p>📄 {{ $fileName }} - Link: {{ url('/storage/' . $bukti) }}</p>
                    @else
                        <p>📁 {{ $fileName }} - Link: {{ url('/storage/' . $bukti) }}</p>
                    @endif
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

                    @if($isImage)
                        <div style="margin-bottom: 10px;">
                            <img src="{{ asset('storage/' . $bukti) }}" alt="Bukti Pendukung" class="media-image">
                            <br><small>📸 {{ $fileName }}</small>
                        </div>
                    @elseif($isVideo)
                        <div class="video-thumbnail" style="margin-bottom: 10px;">
                            <video width="200" height="150" style="border: 1px solid #ddd; border-radius: 4px;">
                                <source src="{{ asset('storage/' . $bukti) }}" type="video/{{ $extension }}">
                                Video tidak dapat ditampilkan
                            </video>
                            <div class="video-play-icon">▶</div>
                            <br><small>🎥 {{ $fileName }}</small>
                            <br><small><strong>Link:</strong> {{ url('/storage/' . $bukti) }}</small>
                        </div>
                    @elseif($isPdf)
                        <p>📄 {{ $fileName }} - Link: {{ url('/storage/' . $bukti) }}</p>
                    @else
                        <p>📁 {{ $fileName }} - Link: {{ url('/storage/' . $bukti) }}</p>
                    @endif
                @endforeach
            @else
                <p><em>Tidak ada bukti pendukung</em></p>
            @endif
        </div>

        <!-- 8. Persetujuan dan Tanda Tangan -->
        <div class="section">
            <h3>✍️ Persetujuan dan Tanda Tangan</h3>
            <div class="info-grid">
                <div class="info-item">
                    <strong>Persetujuan</strong>
                    {{ $blacklist->persetujuan ? 'Ya' : 'Tidak' }}
                </div>
                <div class="info-item">
                    <strong>Nama Pelapor (TTD)</strong>
                    {{ $blacklist->nama_pelapor_ttd ?: 'Tidak ada data' }}
                </div>
                <div class="info-item">
                    <strong>Tanggal Pelaporan</strong>
                    {{ $blacklist->tanggal_pelaporan ? $blacklist->tanggal_pelaporan->format('d/m/Y') : 'Tidak ada data' }}
                </div>
                <div class="info-item">
                    <strong>Tipe Pelapor</strong>
                    {{ $blacklist->tipe_pelapor === 'rental_owner' ? 'Pemilik Rental' : 'Tamu' }}
                </div>
            </div>
        </div>

        <!-- 9. Informasi Sistem -->
        <div class="section">
            <h3>💻 Informasi Sistem</h3>
            <div class="info-grid">
                <div class="info-item">
                    <strong>Status Validitas</strong>
                    {{ $blacklist->status_validitas }}
                </div>
                <div class="info-item">
                    <strong>Jumlah Laporan (NIK ini)</strong>
                    {{ App\Models\RentalBlacklist::countReportsByNik($blacklist->nik) }} laporan
                </div>
                <div class="info-item">
                    <strong>Pelapor</strong>
                    {{ $blacklist->user->name }}
                </div>
                <div class="info-item">
                    <strong>Tanggal Dibuat</strong>
                    {{ $blacklist->created_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>

        <div class="footer">
            <p>
                @if($blacklist->status_validitas === 'Valid')
                    Data ini <strong>valid dan telah diverifikasi</strong> dan dapat digunakan sebagai referensi untuk keputusan rental
                @elseif($blacklist->status_validitas === 'Pending')
                    Data ini <strong>belum diverifikasi</strong> dan dapat digunakan sebagai referensi untuk keputusan rental
                @else
                    Data ini <strong>invalid</strong> dan dapat digunakan sebagai referensi untuk keputusan rental
                @endif
            </p>

            <!-- Verification Section -->
            <div style="margin: 20px 0; padding: 15px; border: 2px solid #dc3545; border-radius: 8px; background-color: #f8f9fa; text-align: center;">
                <h4 style="color: #dc3545; margin-bottom: 10px;">🔐 Verifikasi Keaslian Dokumen</h4>

                <!-- QR Code -->
                <div style="margin: 15px 0;">
                    <img src="data:image/png;base64,{{ $qrCodeBase64 }}" alt="QR Code Verifikasi" style="width: 120px; height: 120px; border: 1px solid #ddd; border-radius: 8px;">
                </div>

                <!-- Verification Code -->
                <div style="margin: 10px 0;">
                    <strong>Kode Verifikasi:</strong><br>
                    <code style="font-size: 16px; font-weight: bold; color: #dc3545; background: white; padding: 5px 10px; border-radius: 4px; border: 1px solid #dc3545;">{{ $verificationCode }}</code>
                </div>

                <!-- Verification URL -->
                <div style="margin: 10px 0; font-size: 12px; color: #666;">
                    <strong>Link Verifikasi:</strong><br>
                    <span style="font-size: 11px; word-break: break-all;">{{ $verificationUrl }}</span>
                </div>

                <!-- Instructions -->
                <div style="margin-top: 15px; font-size: 12px; color: #666;">
                    <p><strong>Cara Verifikasi:</strong></p>
                    <p>1. <strong>Scan QR Code</strong> dengan kamera HP untuk verifikasi otomatis</p>
                    <p>2. Atau kunjungi: <strong>cekpenyewa.com/verifikasi-dokumen</strong></p>
                    <p>3. Masukkan kode verifikasi di atas secara manual</p>
                </div>

                <p style="margin-top: 10px; font-size: 11px; color: #999;">
                    Dokumen dicetak pada: {{ now()->format('d/m/Y H:i:s') }} WIB
                </p>
            </div>

            <p>
                Untuk informasi lebih lanjut, kunjungi website resmi kami:<br>
                <a href="https://cekpenyewa.com" style="color: #dc3545; text-decoration: none;">cekpenyewa.com</a>
            </p>
            <p>
                Platform Dikembangkan oleh <strong><a href="https://indowebsolution.com" style="color: #dc3545; text-decoration: none;">PT. Indo Web Solution</a></strong>
            </p>
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
