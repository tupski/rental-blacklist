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
            font-size: 12px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #dc3545;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .header h1 {
            color: #dc3545;
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 3px 0;
            color: #666;
            font-size: 10px;
        }
        .content {
            max-width: 100%;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            border: 1px solid #ddd;
            padding: 8px;
            vertical-align: top;
        }
        .info-table .label {
            background: #f8f9fa;
            font-weight: bold;
            color: #dc3545;
            width: 30%;
        }
        .info-table .value {
            background: #fff;
        }
        .badges {
            margin: 5px 0;
        }
        .badge {
            display: inline-block;
            background: #ffc107;
            color: #000;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            margin-right: 3px;
            margin-bottom: 3px;
        }
        .kronologi {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px;
            border-radius: 3px;
            margin-top: 5px;
            font-size: 11px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 10px;
            border-radius: 3px;
            margin-bottom: 15px;
            font-size: 11px;
        }
        .section-title {
            background: #dc3545;
            color: white;
            padding: 8px;
            margin: 15px 0 5px 0;
            font-weight: bold;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="header">
            <h1>SISTEM BLACKLIST RENTAL INDONESIA</h1>
            <p>Detail Laporan Blacklist</p>
            <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>

        <div class="warning">
            <strong>PERINGATAN:</strong> Dokumen ini berisi informasi sensitif dan hanya untuk keperluan verifikasi rental. 
            Dilarang menyebarluaskan atau menggunakan data ini untuk tujuan lain.
        </div>

        <div class="section-title">INFORMASI PERSONAL</div>
        <table class="info-table">
            <tr>
                <td class="label">Nama Lengkap</td>
                <td class="value">{{ $blacklist->nama_lengkap }}</td>
            </tr>
            <tr>
                <td class="label">NIK</td>
                <td class="value">{{ $blacklist->nik }}</td>
            </tr>
            <tr>
                <td class="label">No HP</td>
                <td class="value">{{ $blacklist->no_hp }}</td>
            </tr>
            <tr>
                <td class="label">Jenis Kelamin</td>
                <td class="value">{{ $blacklist->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td class="value">{{ $blacklist->alamat }}</td>
            </tr>
        </table>

        <div class="section-title">INFORMASI LAPORAN</div>
        <table class="info-table">
            <tr>
                <td class="label">Jenis Rental</td>
                <td class="value">{{ $blacklist->jenis_rental }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Kejadian</td>
                <td class="value">{{ $blacklist->tanggal_kejadian->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="label">Status Validitas</td>
                <td class="value">{{ $blacklist->status_validitas }}</td>
            </tr>
            <tr>
                <td class="label">Jumlah Laporan</td>
                <td class="value">{{ App\Models\RentalBlacklist::countReportsByNik($blacklist->nik) }} laporan untuk NIK ini</td>
            </tr>
            <tr>
                <td class="label">Jenis Laporan</td>
                <td class="value">
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
                </td>
            </tr>
            <tr>
                <td class="label">Kronologi Kejadian</td>
                <td class="value">
                    <div class="kronologi">
                        {{ $blacklist->kronologi }}
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label">Dilaporkan Oleh</td>
                <td class="value">
                    {{ $blacklist->user->name }}
                    <br><small>Tanggal Laporan: {{ $blacklist->created_at->format('d/m/Y H:i:s') }}</small>
                </td>
            </tr>
        </table>

        <div class="footer">
            <p><strong>Sistem Blacklist Rental Indonesia</strong></p>
            <p>Data ini telah diverifikasi dan dapat digunakan sebagai referensi untuk keputusan rental</p>
            <p>Dokumen ini digenerate secara otomatis pada {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
