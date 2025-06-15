@auth
    @if(Auth::user()->requiresEmailVerification())
        <div class="alert alert-info alert-dismissible sticky-top shadow-sm border-0" 
             style="position: sticky; top: 0; z-index: 1049; margin-bottom: 0; border-radius: 0;">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h6 class="alert-heading mb-1">
                            <i class="fas fa-envelope text-info me-2"></i>
                            <strong>Verifikasi Email Diperlukan</strong>
                        </h6>
                        <p class="mb-0">
                            Silakan verifikasi alamat email Anda untuk mengakses semua fitur. 
                            Kami telah mengirimkan link verifikasi ke <strong>{{ Auth::user()->email }}</strong>.
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end mt-2 mt-md-0">
                        <div class="btn-group" role="group">
                            <a href="{{ route('verifikasi.pemberitahuan') }}" class="btn btn-info btn-sm">
                                <i class="fas fa-envelope me-1"></i>
                                Verifikasi Email
                            </a>
                            <form method="POST" action="{{ route('verifikasi.kirim') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-redo me-1"></i>
                                    Kirim Ulang
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@endauth
