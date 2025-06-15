<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Kadaluarsa - CekPenyewa.com</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
            background: rgba(255,255,255,0.95);
        }
        .card-header {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            border: none;
            padding: 2rem;
            text-align: center;
        }
        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .card-body {
            padding: 2rem;
            text-align: center;
        }
        .expired-icon {
            font-size: 4rem;
            color: #dc3545;
            margin-bottom: 1rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #da3544, #b82d3c);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(218, 53, 68, 0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <div class="logo">
                            <i class="fas fa-shield-alt me-2"></i>
                            CekPenyewa.com
                        </div>
                        <p class="mb-0">Link Kadaluarsa</p>
                    </div>
                    <div class="card-body">
                        <div class="expired-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        
                        <h4 class="mb-3">Link Sudah Kadaluarsa</h4>
                        
                        <p class="text-muted mb-4">
                            Maaf, link berbagi laporan ini sudah tidak dapat diakses karena:
                        </p>

                        <div class="alert alert-warning text-start">
                            @if($sharedReport->expires_at->isPast())
                                <i class="fas fa-clock me-2"></i>
                                <strong>Waktu habis:</strong> Link ini kadaluarsa pada {{ $sharedReport->formatted_expiry }}
                            @elseif($sharedReport->one_time_view && $sharedReport->is_accessed)
                                <i class="fas fa-eye me-2"></i>
                                <strong>Sekali lihat:</strong> Link ini sudah pernah diakses dan tidak dapat dibuka lagi
                            @endif
                        </div>

                        <div class="mb-4">
                            <p class="small text-muted">
                                Jika Anda memerlukan akses ke laporan ini, silakan hubungi pihak yang membagikan link untuk membuat link baru.
                            </p>
                        </div>

                        <a href="{{ route('beranda') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i>
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
