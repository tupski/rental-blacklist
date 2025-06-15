<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Laporan - CekPenyewa.com</title>
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
            background: linear-gradient(135deg, #da3544, #b82d3c);
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
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #da3544;
            box-shadow: 0 0 0 0.2rem rgba(218, 53, 68, 0.25);
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
        .alert {
            border-radius: 10px;
            border: none;
        }
        .info-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #da3544;
        }
        .warning-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
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
                        <p class="mb-0">Akses Laporan Berbagi</p>
                    </div>
                    <div class="card-body">
                        <div class="info-box">
                            <h6 class="mb-2">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                Informasi Laporan
                            </h6>
                            <p class="mb-1"><strong>Nama:</strong> {{ $sharedReport->blacklist->nama_lengkap }}</p>
                            <p class="mb-1"><strong>Dibagikan oleh:</strong> {{ $sharedReport->user->name }}</p>
                            <p class="mb-1"><strong>Berlaku hingga:</strong> {{ $sharedReport->formatted_expiry }}</p>
                            @if($sharedReport->one_time_view)
                                <p class="mb-0 text-warning">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    <strong>Sekali Lihat:</strong> Link akan kadaluarsa setelah dibuka
                                </p>
                            @endif
                        </div>

                        <div class="warning-box">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                <div>
                                    <strong>Peringatan:</strong> Ini adalah data sensitif. Jangan bagikan password atau screenshot kepada pihak yang tidak berwenang.
                                </div>
                            </div>
                        </div>

                        @if(session('info'))
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                {{ session('info') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('shared.verify', $sharedReport->token) }}">
                            @csrf
                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>
                                    Password
                                </label>
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       placeholder="Masukkan password untuk mengakses laporan"
                                       required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-unlock me-2"></i>
                                    Akses Laporan
                                </button>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt me-1"></i>
                                Laporan ini dilindungi dengan enkripsi end-to-end
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
