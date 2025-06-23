<!-- Modern Footer Component -->
<footer class="footer-modern mt-5">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-4">
                <h4 class="fw-bold mb-3">
                    <i class="fas fa-shield-alt text-danger me-2"></i>
                    {{ $globalSettings['site_name'] ?? 'RentalGuard' }}
                </h4>
                <p class="text-light mb-4">
                    {{ $globalSettings['site_tagline'] ?? 'Sistem Blacklist Rental Indonesia yang terpercaya untuk melindungi bisnis rental Anda.' }}
                </p>
                <div class="social-links">
                    @if($globalSettings['facebook_url'] ?? false)
                    <a href="{{ $globalSettings['facebook_url'] }}" target="_blank" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    @endif
                    @if($globalSettings['twitter_url'] ?? false)
                    <a href="{{ $globalSettings['twitter_url'] }}" target="_blank" title="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    @endif
                    @if($globalSettings['instagram_url'] ?? false)
                    <a href="{{ $globalSettings['instagram_url'] }}" target="_blank" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    @endif
                    @if($globalSettings['whatsapp_number'] ?? false)
                    <a href="https://wa.me/{{ $globalSettings['whatsapp_number'] }}" target="_blank" title="WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    @endif
                </div>
            </div>

            <div class="col-lg-2 col-md-6">
                <h5 class="fw-bold mb-3">Layanan</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('beranda') }}">Cek Blacklist</a></li>
                    <li class="mb-2"><a href="{{ route('daftar') }}">Pendaftaran Rental</a></li>
                    <li class="mb-2"><a href="{{ route('laporan.buat') }}">Lapor Masalah</a></li>
                    <li class="mb-2"><a href="{{ route('api.dokumentasi') }}">API Access</a></li>
                </ul>
            </div>

            <div class="col-lg-2 col-md-6">
                <h5 class="fw-bold mb-3">Bantuan</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#">FAQ</a></li>
                    <li class="mb-2"><a href="#">Kontak</a></li>
                    <li class="mb-2"><a href="#">Kebijakan Privasi</a></li>
                    <li class="mb-2"><a href="#">Syarat & Ketentuan</a></li>
                </ul>
            </div>

            <div class="col-lg-4">
                <h5 class="fw-bold mb-3">Kontak</h5>
                <div class="mb-3">
                    <i class="fas fa-envelope me-2 text-danger"></i>
                    <a href="mailto:{{ $globalSettings['contact_email'] ?? 'support@rentalguard.id' }}">
                        {{ $globalSettings['contact_email'] ?? 'support@rentalguard.id' }}
                    </a>
                </div>
                @if($globalSettings['contact_phone'] ?? false)
                <div class="mb-3">
                    <i class="fas fa-phone me-2 text-danger"></i>
                    <a href="tel:{{ $globalSettings['contact_phone'] }}">
                        {{ $globalSettings['contact_phone'] }}
                    </a>
                </div>
                @endif

                @if(isset($footerSponsors) && $footerSponsors->count() > 0)
                <div class="mt-4">
                    <h6 class="fw-bold mb-3">Didukung oleh:</h6>
                    <div class="d-flex flex-wrap gap-3">
                        @foreach($footerSponsors as $sponsor)
                            <a href="{{ $sponsor->website_url }}" target="_blank" class="text-decoration-none">
                                <img src="{{ $sponsor->logo_url }}"
                                     alt="{{ $sponsor->name }}"
                                     class="img-fluid bg-white rounded p-2"
                                     style="max-height: 40px; max-width: 120px;"
                                     title="{{ $sponsor->name }}">
                            </a>
                        @endforeach
                    </div>
                    <a href="{{ route('sponsor.kemitraan') }}" class="btn btn-outline-light btn-sm mt-3">
                        <i class="fas fa-plus me-1"></i>
                        Jadi Sponsor
                    </a>
                </div>
                @endif
            </div>
        </div>

        <hr class="my-4 border-light opacity-25">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0">&copy; {{ date('Y') }} {{ $globalSettings['site_name'] ?? 'RentalGuard' }}. Semua hak dilindungi.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-0">
                    <small>Dibuat dengan <i class="fas fa-heart text-danger"></i> untuk komunitas rental Indonesia</small>
                </p>
            </div>
        </div>
    </div>
</footer>

<style>
/* Footer Styles */
.footer-modern {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    color: white;
    margin-top: auto;
}

.footer-modern h4, .footer-modern h5 {
    color: white;
}

.footer-modern .text-danger {
    color: #da3544 !important;
}

.footer-modern a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: all 0.3s ease;
}

.footer-modern a:hover {
    color: white;
    transform: translateX(5px);
}

.footer-modern .social-links a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    margin-right: 0.5rem;
    transition: all 0.3s ease;
}

.footer-modern .social-links a:hover {
    background: #da3544;
    transform: translateY(-2px);
}

.footer-modern .btn-outline-light {
    border: 2px solid rgba(255, 255, 255, 0.3);
    color: white;
    font-weight: 600;
    padding: 0.5rem 1.5rem;
    border-radius: 25px;
    transition: all 0.3s ease;
}

.footer-modern .btn-outline-light:hover {
    background: white;
    color: #da3544;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}
</style>
