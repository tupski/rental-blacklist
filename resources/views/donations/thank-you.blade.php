@extends('layouts.main')

@section('title', 'Terima Kasih atas Donasi Anda - CekPenyewa.com')

@section('content')
<!-- Thank You Header -->
<section class="py-5" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center text-white">
                <div class="mb-4">
                    <i class="fas fa-check-circle fa-5x mb-3"></i>
                </div>
                <h1 class="display-4 fw-bold mb-3">Terima Kasih!</h1>
                <p class="lead opacity-90 mb-4">
                    Donasi Anda telah berhasil dikonfirmasi dan sangat berarti bagi pengembangan platform CekPenyewa.com
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Donation Details -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Success Message -->
                <div class="card border-0 shadow-lg mb-4">
                    <div class="card-body text-center p-5">
                        <h3 class="text-success fw-bold mb-3">
                            <i class="fas fa-heart me-2"></i>
                            Donasi Berhasil Diterima
                        </h3>
                        <p class="text-muted mb-4">
                            Kami telah menerima konfirmasi pembayaran donasi Anda. Tim kami akan memverifikasi pembayaran dalam 1x24 jam.
                        </p>
                        
                        <!-- Donation Summary -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="fw-bold text-primary mb-2">Detail Donatur</h6>
                                        <p class="mb-1"><strong>Nama:</strong> {{ $donation->donor_name }}</p>
                                        @if($donation->company_name)
                                            <p class="mb-1"><strong>Perusahaan:</strong> {{ $donation->company_name }}</p>
                                        @endif
                                        <p class="mb-1"><strong>Email:</strong> {{ $donation->donor_email }}</p>
                                        <p class="mb-0"><strong>Domisili:</strong> {{ $donation->donor_city }}, {{ $donation->donor_province }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="fw-bold text-success mb-2">Detail Donasi</h6>
                                        <p class="mb-1"><strong>Jumlah:</strong> <span class="text-success fw-bold">{{ $donation->formatted_amount }}</span></p>
                                        <p class="mb-1"><strong>Metode:</strong> {{ $donation->payment_method }}</p>
                                        <p class="mb-1"><strong>Referensi:</strong> {{ $donation->payment_reference }}</p>
                                        <p class="mb-0"><strong>Tanggal:</strong> {{ $donation->paid_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($donation->message)
                            <div class="alert alert-info">
                                <h6 class="fw-bold mb-2">Pesan Anda:</h6>
                                <p class="mb-0 fst-italic">"{{ $donation->message }}"</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- What's Next -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-clock me-2"></i>
                            Langkah Selanjutnya
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-4 text-center">
                                <div class="mb-3">
                                    <div class="rounded-circle bg-warning text-white mx-auto d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                        <i class="fas fa-search fa-lg"></i>
                                    </div>
                                </div>
                                <h6 class="fw-bold">Verifikasi</h6>
                                <p class="text-muted small">Tim kami akan memverifikasi pembayaran Anda dalam 1x24 jam</p>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="mb-3">
                                    <div class="rounded-circle bg-info text-white mx-auto d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                        <i class="fas fa-envelope fa-lg"></i>
                                    </div>
                                </div>
                                <h6 class="fw-bold">Email Konfirmasi</h6>
                                <p class="text-muted small">Anda akan menerima email konfirmasi setelah verifikasi selesai</p>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="mb-3">
                                    <div class="rounded-circle bg-success text-white mx-auto d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                        <i class="fas fa-rocket fa-lg"></i>
                                    </div>
                                </div>
                                <h6 class="fw-bold">Pengembangan</h6>
                                <p class="text-muted small">Donasi Anda akan langsung digunakan untuk pengembangan platform</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Impact Message -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <h5 class="fw-bold text-primary mb-3">Dampak Donasi Anda</h5>
                        <p class="text-muted mb-4">
                            Dengan donasi sebesar <strong>{{ $donation->formatted_amount }}</strong>, Anda telah membantu:
                        </p>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="text-center">
                                    <i class="fas fa-server fa-2x text-primary mb-2"></i>
                                    <p class="small mb-0">Biaya server untuk {{ ceil($donation->amount / 50000) }} hari</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <i class="fas fa-shield-alt fa-2x text-success mb-2"></i>
                                    <p class="small mb-0">Melindungi {{ ceil($donation->amount / 1000) }}+ rental</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <i class="fas fa-code fa-2x text-warning mb-2"></i>
                                    <p class="small mb-0">Pengembangan fitur baru</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="text-center mt-4">
                    <a href="{{ route('beranda') }}" class="btn btn-primary me-3">
                        <i class="fas fa-home me-2"></i>
                        Kembali ke Beranda
                    </a>
                    <a href="{{ route('donasi.indeks') }}" class="btn btn-outline-primary">
                        <i class="fas fa-heart me-2"></i>
                        Donasi Lagi
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Share Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h4 class="fw-bold mb-3">Bagikan Dukungan Anda</h4>
                <p class="text-muted mb-4">
                    Ajak teman dan keluarga untuk ikut mendukung pengembangan platform CekPenyewa.com
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="https://wa.me/?text=Saya%20baru%20saja%20berdonasi%20untuk%20pengembangan%20platform%20CekPenyewa.com.%20Mari%20dukung%20bersama!%20{{ urlencode(route('donasi.indeks')) }}" 
                       target="_blank" 
                       class="btn btn-success">
                        <i class="fab fa-whatsapp me-2"></i>
                        WhatsApp
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('donasi.indeks')) }}" 
                       target="_blank" 
                       class="btn btn-primary">
                        <i class="fab fa-facebook me-2"></i>
                        Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=Saya%20baru%20saja%20berdonasi%20untuk%20pengembangan%20platform%20CekPenyewa.com&url={{ urlencode(route('donasi.indeks')) }}" 
                       target="_blank" 
                       class="btn btn-info">
                        <i class="fab fa-twitter me-2"></i>
                        Twitter
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
