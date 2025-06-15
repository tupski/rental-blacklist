<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berbagi Laporan - {{ $sharedReport->blacklist->nama_lengkap }} - CekPenyewa.com</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .header {
            background: linear-gradient(135deg, #da3544, #b82d3c);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }
        .card-header {
            background: #fff;
            border-bottom: 2px solid #da3544;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.5rem;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        .info-item {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 10px;
            border-left: 4px solid #da3544;
        }
        .info-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        .info-value {
            color: #212529;
        }
        .warning-banner {
            background: linear-gradient(135deg, #ffc107, #e0a800);
            color: #212529;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            text-align: center;
            font-weight: 600;
        }
        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }
        .media-item {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            overflow: hidden;
            background: white;
        }
        .media-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        .media-item-info {
            padding: 0.75rem;
        }
        .no-print {
            display: block;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
            }
            .card {
                box-shadow: none !important;
                border: 1px solid #dee2e6 !important;
            }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <div class="header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="logo">
                            <i class="fas fa-shield-alt me-2"></i>
                            CekPenyewa.com
                        </div>
                        <p class="mb-0">Berbagi Laporan - Data Sensitif</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <small>
                            Dibagikan oleh: <b>{{ $sharedReport->user->name }}</b><br>
                            Berlaku hingga: <b>{{ $sharedReport->formatted_expiry }}</b>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="warning-banner">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>PERINGATAN:</strong> Ini adalah data sensitif dan rahasia. Dilarang mencetak, mengunduh, atau membagikan kepada pihak yang tidak berwenang.
        </div>
    </div>

    <div class="container">
        @php
            $blacklist = $sharedReport->blacklist;

            // Helper function untuk sensor data
            function censorData($data, $showUncensored) {
                if ($showUncensored) {
                    return $data;
                }

                if (strlen($data) <= 3) {
                    return str_repeat('*', strlen($data));
                }

                $firstChar = substr($data, 0, 1);
                $lastChar = substr($data, -1);
                $middle = str_repeat('*', strlen($data) - 2);

                return $firstChar . $middle . $lastChar;
            }
        @endphp

        <!-- Data Penyewa -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>
                    Data Penyewa
                </h5>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">NIK</div>
                        <div class="info-value">{{ censorData($blacklist->nik ?? 'Tidak ada data', $showUncensored) }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Nama Lengkap</div>
                        <div class="info-value">{{ censorData($blacklist->nama_lengkap ?? 'Tidak ada data', $showUncensored) }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Jenis Kelamin</div>
                        <div class="info-value">{{ $blacklist->jenis_kelamin ?? 'Tidak ada data' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">No. HP</div>
                        <div class="info-value">{{ censorData($blacklist->no_hp ?? 'Tidak ada data', $showUncensored) }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Alamat</div>
                        <div class="info-value">{{ censorData($blacklist->alamat ?? 'Tidak ada data', $showUncensored) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Laporan -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-file-alt me-2"></i>
                    Detail Laporan
                </h5>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Jenis Rental</div>
                        <div class="info-value">{{ $blacklist->jenis_rental ?? 'Tidak ada data' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Jenis Laporan</div>
                        <div class="info-value">
                            @if($blacklist->jenis_laporan && is_array($blacklist->jenis_laporan))
                                @foreach($blacklist->jenis_laporan as $jenis)
                                    <span class="badge bg-warning text-dark me-1">{{ $jenis }}</span>
                                @endforeach
                            @else
                                Tidak ada data
                            @endif
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tanggal Kejadian</div>
                        <div class="info-value">{{ $blacklist->tanggal_kejadian ? $blacklist->tanggal_kejadian->format('d/m/Y') : 'Tidak ada data' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Status Validitas</div>
                        <div class="info-value">
                            <span class="badge bg-{{ $blacklist->status_validitas === 'Valid' ? 'success' : 'warning' }}">
                                {{ $blacklist->status_validitas ?? 'Tidak ada data' }}
                            </span>
                        </div>
                    </div>
                </div>

                @if($blacklist->kronologi)
                    <div class="mt-4">
                        <div class="info-label">Kronologi Kejadian</div>
                        <div class="border p-3 bg-light rounded">
                            {{ $blacklist->kronologi }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Bukti/Media -->
        @if($blacklist->bukti && (is_array($blacklist->bukti) ? count($blacklist->bukti) > 0 : count(json_decode($blacklist->bukti, true) ?? []) > 0))
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-paperclip me-2"></i>
                        Bukti/Media
                    </h5>
                </div>
                <div class="card-body">
                    <div class="media-grid">
                        @php
                            $buktiArray = is_array($blacklist->bukti) ? $blacklist->bukti : json_decode($blacklist->bukti, true) ?? [];
                        @endphp
                        @foreach($buktiArray as $bukti)
                            @php
                                $fileName = basename($bukti);
                                $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            @endphp
                            <div class="media-item">
                                @if($isImage)
                                    <img src="{{ asset('storage/' . $bukti) }}" alt="{{ $fileName }}" class="img-fluid">
                                @else
                                    <div class="d-flex align-items-center justify-content-center" style="height: 150px; background: #f8f9fa;">
                                        <i class="fas fa-file fa-3x text-muted"></i>
                                    </div>
                                @endif
                                <div class="media-item-info">
                                    <small class="text-muted">{{ $fileName }}</small>
                                    <div class="no-print">
                                        <a href="{{ asset('storage/' . $bukti) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Informasi Pelapor -->
        @if($blacklist->nama_perusahaan_rental || $blacklist->nama_penanggung_jawab)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-building me-2"></i>
                        Informasi Pelapor
                    </h5>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        @if($blacklist->nama_perusahaan_rental)
                            <div class="info-item">
                                <div class="info-label">Nama Perusahaan</div>
                                <div class="info-value">{{ $blacklist->nama_perusahaan_rental }}</div>
                            </div>
                        @endif
                        @if($blacklist->nama_penanggung_jawab)
                            <div class="info-item">
                                <div class="info-label">Penanggung Jawab</div>
                                <div class="info-value">{{ $blacklist->nama_penanggung_jawab }}</div>
                            </div>
                        @endif
                        @if($blacklist->no_wa_pelapor)
                            <div class="info-item">
                                <div class="info-label">No. WhatsApp</div>
                                <div class="info-value">{{ $blacklist->no_wa_pelapor }}</div>
                            </div>
                        @endif
                        @if($blacklist->email_pelapor)
                            <div class="info-item">
                                <div class="info-label">Email</div>
                                <div class="info-value">{{ $blacklist->email_pelapor }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <div class="no-print text-center mt-4 mb-5">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Catatan:</strong> Laporan ini tidak dapat dicetak atau diunduh untuk menjaga keamanan data.
            </div>
            <a href="{{ route('beranda') }}" class="btn btn-primary">
                <i class="fas fa-home me-2"></i>
                Kembali ke Beranda
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Disable right-click context menu
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });

        // Disable F12, Ctrl+Shift+I, Ctrl+U
        document.addEventListener('keydown', function(e) {
            if (e.key === 'F12' ||
                (e.ctrlKey && e.shiftKey && e.key === 'I') ||
                (e.ctrlKey && e.key === 'u')) {
                e.preventDefault();
            }
        });

        // Disable print shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                alert('Pencetakan tidak diizinkan untuk laporan berbagi ini.');
            }
        });

        // Disable text selection
        document.onselectstart = function() {
            return false;
        };
        document.onmousedown = function() {
            return false;
        };
    </script>
</body>
</html>
