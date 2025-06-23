@auth
@if(Auth::user()->role === 'pengusaha_rental')
    @php
        $wa1 = App\Models\Setting::get('admin_contact_wa1', '0819-1191-9993');
        $wa2 = App\Models\Setting::get('admin_contact_wa2', '0822-1121-9993');

        // Create WhatsApp message
        $message = "Halo Admin CekPenyewa.com,\n\n";
        $message .= "Saya ingin menanyakan status akun saya:\n\n";
        $message .= "Nama: " . Auth::user()->name . "\n";
        $message .= "Email: " . Auth::user()->email . "\n";
        $message .= "Role: Pemilik Rental\n";
        $message .= "Tanggal Daftar: " . Auth::user()->created_at->format('d/m/Y H:i') . "\n\n";
        $message .= "Terima kasih.";

        $encodedMessage = urlencode($message);
        $wa1Link = "https://wa.me/{$wa1}?text={$encodedMessage}";
        $wa2Link = "https://wa.me/{$wa2}?text={$encodedMessage}";
    @endphp

    @if(Auth::user()->isPending())
        <!-- Pending Account Alert -->
        <div class="alert alert-warning alert-dismissible sticky-top shadow-sm border-0"
             style="position: sticky; top: 0; z-index: 1050; margin-bottom: 0; border-radius: 0;">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h6 class="alert-heading mb-1">
                            <i class="fas fa-clock text-warning me-2"></i>
                            <strong>Akun Menunggu Persetujuan</strong>
                        </h6>
                        <p class="mb-0">
                            Akun Anda sedang ditinjau oleh admin dan akan diaktifkan dalam <strong>1x24 jam</strong>.
                            Saat ini Anda tidak dapat mengakses fitur pencarian dan data akan ditampilkan dalam bentuk sensor.
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end mt-2 mt-md-0">
                        <small class="d-block text-muted mb-2">Ada pertanyaan? Hubungi admin:</small>
                        <div class="btn-group" role="group">
                            <a href="{{ $wa1Link }}" target="_blank" class="btn btn-success btn-sm">
                                <i class="fab fa-whatsapp me-1"></i>
                                {{ $wa1 }}
                            </a>
                            <a href="{{ $wa2Link }}" target="_blank" class="btn btn-success btn-sm">
                                <i class="fab fa-whatsapp me-1"></i>
                                {{ $wa2 }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif(Auth::user()->needsRevision())
        <!-- Needs Revision Alert -->
        <div class="alert alert-info alert-dismissible sticky-top shadow-sm border-0"
             style="position: sticky; top: 0; z-index: 1050; margin-bottom: 0; border-radius: 0;">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h6 class="alert-heading mb-1">
                            <i class="fas fa-edit text-info me-2"></i>
                            <strong>Data Pendaftaran Perlu Direvisi</strong>
                        </h6>
                        <p class="mb-0">
                            Admin meminta Anda untuk merevisi data pendaftaran.
                            @if(Auth::user()->revision_notes)
                                <br><strong>Catatan:</strong> {{ Auth::user()->revision_notes }}
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end mt-2 mt-md-0">
                        <a href="{{ route('daftar.revisi') }}" class="btn btn-primary btn-sm me-2">
                            <i class="fas fa-edit me-1"></i> Revisi Data
                        </a>
                        <div class="btn-group" role="group">
                            <a href="{{ $wa1Link }}" target="_blank" class="btn btn-success btn-sm">
                                <i class="fab fa-whatsapp me-1"></i>
                                {{ $wa1 }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif(Auth::user()->isSuspended())
        <!-- Suspended Account Alert -->
        <div class="alert alert-danger alert-dismissible sticky-top shadow-sm border-0"
             style="position: sticky; top: 0; z-index: 1050; margin-bottom: 0; border-radius: 0;">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h6 class="alert-heading mb-1">
                            <i class="fas fa-ban text-danger me-2"></i>
                            <strong>Akun Dibekukan</strong>
                        </h6>
                        <p class="mb-0">
                            Akun Anda telah dibekukan {{ Auth::user()->suspension_type === 'permanent' ? 'secara permanen' : 'sementara' }}.
                            @if(Auth::user()->suspension_reason)
                                <br><strong>Alasan:</strong> {{ Auth::user()->suspension_reason }}
                            @endif
                            @if(Auth::user()->suspension_type === 'temporary' && Auth::user()->suspension_ends_at)
                                <br><strong>Berakhir:</strong> {{ Auth::user()->suspension_ends_at->format('d/m/Y H:i') }}
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end mt-2 mt-md-0">
                        <small class="d-block text-muted mb-2">Hubungi admin untuk banding:</small>
                        <div class="btn-group" role="group">
                            <a href="{{ $wa1Link }}" target="_blank" class="btn btn-success btn-sm">
                                <i class="fab fa-whatsapp me-1"></i>
                                {{ $wa1 }}
                            </a>
                            <a href="{{ $wa2Link }}" target="_blank" class="btn btn-success btn-sm">
                                <i class="fab fa-whatsapp me-1"></i>
                                {{ $wa2 }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(!Auth::user()->hasVerifiedEmail() && \App\Models\Setting::get('require_email_verification', '1') === '1')
        <!-- Email Verification Alert -->
        <div class="alert alert-warning alert-dismissible sticky-top shadow-sm border-0"
             style="position: sticky; top: 0; z-index: 1049; margin-bottom: 0; border-radius: 0;">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h6 class="alert-heading mb-1">
                            <i class="fas fa-envelope text-warning me-2"></i>
                            <strong>Email Belum Terverifikasi</strong>
                        </h6>
                        <p class="mb-0">
                            Silakan verifikasi email Anda untuk mengakses semua fitur.
                            Cek folder inbox dan spam di email Anda.
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end mt-2 mt-md-0">
                        <form method="POST" action="{{ route('verifikasi.kirim') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-paper-plane me-1"></i> Kirim Ulang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@endif
@endauth
