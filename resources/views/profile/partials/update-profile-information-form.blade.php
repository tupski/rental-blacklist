<div>
    <p class="text-muted mb-4">
        Perbarui informasi profil dan alamat email akun Anda.
    </p>

    <form id="send-verification" method="post" action="{{ route('verifikasi.kirim') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profil.perbarui') }}">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   id="name" name="name" value="{{ old('name', $user->name) }}"
                   required autofocus autocomplete="name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email', $user->email) }}"
                   required autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <div class="alert alert-warning">
                        <small>
                            Alamat email Anda belum diverifikasi.
                            <button form="send-verification" class="btn btn-link p-0 text-decoration-underline">
                                Klik di sini untuk mengirim ulang email verifikasi.
                            </button>
                        </small>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success">
                            <small>Link verifikasi baru telah dikirim ke alamat email Anda.</small>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>
                Simpan
            </button>

            @if (session('status') === 'profile-updated')
                <div class="alert alert-success mb-0 py-2 px-3" id="saved-message">
                    <small><i class="fas fa-check me-1"></i>Tersimpan.</small>
                </div>
                <script>
                    setTimeout(() => {
                        document.getElementById('saved-message')?.remove();
                    }, 3000);
                </script>
            @endif
        </div>
    </form>
</div>
