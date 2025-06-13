<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div class="form-group">
        <label for="name">Nama Lengkap</label>
        <input type="text" 
               class="form-control @error('name') is-invalid @enderror" 
               id="name" 
               name="name" 
               value="{{ old('name', $user->name) }}" 
               required 
               autofocus 
               autocomplete="name">
        @error('name')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" 
               class="form-control @error('email') is-invalid @enderror" 
               id="email" 
               name="email" 
               value="{{ old('email', $user->email) }}" 
               required 
               autocomplete="username">
        @error('email')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-2">
                <p class="text-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    Email Anda belum diverifikasi.
                    <button form="send-verification" class="btn btn-link p-0 text-decoration-underline">
                        Klik di sini untuk mengirim ulang email verifikasi.
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="text-success">
                        <i class="fas fa-check"></i>
                        Link verifikasi baru telah dikirim ke alamat email Anda.
                    </p>
                @endif
            </div>
        @endif
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan Perubahan
        </button>

        @if (session('status') === 'profile-updated')
            <span class="text-success ml-2">
                <i class="fas fa-check"></i> Profil berhasil diperbarui.
            </span>
        @endif
    </div>
</form>
