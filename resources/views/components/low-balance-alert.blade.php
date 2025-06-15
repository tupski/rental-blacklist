@auth
    @if(Auth::user()->role === 'user' && (Auth::user()->hasZeroBalance() || Auth::user()->hasLowBalance()))
        @php
            $balance = Auth::user()->getCurrentBalance();
            $isZero = $balance <= 0;
            $alertClass = $isZero ? 'alert-danger' : 'alert-warning';
            $iconClass = $isZero ? 'fas fa-exclamation-triangle' : 'fas fa-coins';
            $iconColor = $isZero ? 'text-danger' : 'text-warning';
        @endphp
        
        <div class="alert {{ $alertClass }} alert-dismissible sticky-top shadow-sm border-0" 
             style="position: sticky; top: 0; z-index: 1048; margin-bottom: 0; border-radius: 0;">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-center text-center">
                    <div class="col-md-8">
                        <h6 class="alert-heading mb-1">
                            <i class="{{ $iconClass }} {{ $iconColor }} me-2"></i>
                            <strong>
                                @if($isZero)
                                    Saldo Habis
                                @else
                                    Saldo Rendah
                                @endif
                            </strong>
                        </h6>
                        <p class="mb-2">
                            @if($isZero)
                                Saldo Anda saat ini <strong>{{ Auth::user()->getFormattedBalance() }}</strong>. 
                                Anda tidak dapat menggunakan fitur pencarian data.
                            @else
                                Saldo Anda saat ini <strong>{{ Auth::user()->getFormattedBalance() }}</strong>. 
                                Segera isi saldo untuk melanjutkan pencarian data.
                            @endif
                        </p>
                        <a href="{{ route('isi-saldo.indeks') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-wallet me-1"></i>
                            Isi Saldo Mulai dari Rp10,000
                        </a>
                    </div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@endauth
