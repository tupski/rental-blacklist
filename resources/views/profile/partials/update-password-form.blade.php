<div>
    <p class="text-muted mb-4">
        Pastikan akun Anda menggunakan password yang panjang dan acak untuk tetap aman.
    </p>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="current_password" class="form-label">Password Saat Ini</label>
            <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                   id="current_password" name="current_password" autocomplete="current-password">
            @error('current_password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password Baru</label>
            <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                   id="password" name="password" autocomplete="new-password">
            @error('password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <input type="password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                   id="password_confirmation" name="password_confirmation" autocomplete="new-password">
            @error('password_confirmation', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-warning">
                <i class="fas fa-save me-2"></i>
                Ubah Password
            </button>

            @if (session('status') === 'password-updated')
                <div class="alert alert-success mb-0 py-2 px-3" id="password-saved-message">
                    <small><i class="fas fa-check me-1"></i>Password berhasil diubah.</small>
                </div>
                <script>
                    setTimeout(() => {
                        document.getElementById('password-saved-message')?.remove();
                    }, 3000);
                </script>
            @endif
        </div>
    </form>
</div>
