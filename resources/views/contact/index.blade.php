@extends('layouts.main')

@section('title', 'Kontak Kami')
@section('meta_description', 'Hubungi kami untuk pertanyaan, saran, atau bantuan terkait layanan blacklist rental.')

@push('styles')
<style>
    .contact-hero {
        background: linear-gradient(135deg, #da3544 0%, #b02a37 100%);
        color: white;
        padding: 4rem 0;
    }
    
    .contact-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }
    
    .contact-card:hover {
        transform: translateY(-5px);
    }
    
    .contact-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #da3544 0%, #b02a37 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        margin: 0 auto 1rem;
    }
    
    .form-control:focus {
        border-color: #da3544;
        box-shadow: 0 0 0 0.2rem rgba(218, 53, 68, 0.25);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #da3544 0%, #b02a37 100%);
        border: none;
        padding: 12px 30px;
        font-weight: 600;
        border-radius: 8px;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #b02a37 0%, #8e2129 100%);
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="contact-hero">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="fas fa-envelope me-3"></i>
                    Kontak Kami
                </h1>
                <p class="lead mb-0">
                    Kami siap membantu Anda. Hubungi kami untuk pertanyaan, saran, atau bantuan terkait layanan kami.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Info Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-4 mb-5">
            <div class="col-lg-4">
                <div class="contact-card card h-100 text-center p-4">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Alamat</h5>
                    <p class="text-muted mb-0">
                        {{ $settings['contact_address'] ?? 'Indonesia' }}
                    </p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="contact-card card h-100 text-center p-4">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Telepon</h5>
                    <p class="text-muted mb-2">
                        <a href="tel:{{ $settings['contact_phone'] ?? '+62 123 456 789' }}" class="text-decoration-none">
                            {{ $settings['contact_phone'] ?? '+62 123 456 789' }}
                        </a>
                    </p>
                    <small class="text-muted">
                        {{ $settings['contact_hours'] ?? 'Senin - Jumat: 09:00 - 17:00 WIB' }}
                    </small>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="contact-card card h-100 text-center p-4">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Email</h5>
                    <p class="text-muted mb-0">
                        <a href="mailto:{{ $settings['contact_email'] ?? 'info@rentalblacklist.com' }}" class="text-decoration-none">
                            {{ $settings['contact_email'] ?? 'info@rentalblacklist.com' }}
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="contact-card card p-5">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold mb-3">Kirim Pesan</h3>
                        <p class="text-muted">Isi form di bawah ini dan kami akan segera merespons pesan Anda.</p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('kontak.kirim') }}" method="POST" id="contactForm">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="subject" class="form-label fw-bold">Subjek <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                       id="subject" name="subject" value="{{ old('subject') }}" required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="message" class="form-label fw-bold">Pesan <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('message') is-invalid @enderror" 
                                          id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Captcha -->
                            @if(isset($settings['captcha_enabled']) && $settings['captcha_enabled'] == '1' && 
                                isset($settings['captcha_contact']) && $settings['captcha_contact'] == '1')
                                <div class="col-12">
                                    @if(($settings['captcha_type'] ?? 'recaptcha') === 'recaptcha' && !empty($settings['recaptcha_site_key']))
                                        <div class="g-recaptcha" data-sitekey="{{ $settings['recaptcha_site_key'] }}"></div>
                                        @error('g-recaptcha-response')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    @endif
                                </div>
                            @endif

                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Kirim Pesan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
@if(isset($settings['captcha_enabled']) && $settings['captcha_enabled'] == '1' && 
    isset($settings['captcha_contact']) && $settings['captcha_contact'] == '1' &&
    ($settings['captcha_type'] ?? 'recaptcha') === 'recaptcha' && !empty($settings['recaptcha_site_key']))
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endif

<script>
$(document).ready(function() {
    // Form validation
    $('#contactForm').on('submit', function(e) {
        let isValid = true;
        
        // Check required fields
        $(this).find('[required]').each(function() {
            if (!$(this).val().trim()) {
                isValid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $('.is-invalid').first().offset().top - 100
            }, 500);
        }
    });
    
    // Remove invalid class on input
    $('input, textarea').on('input', function() {
        $(this).removeClass('is-invalid');
    });
});
</script>
@endpush
