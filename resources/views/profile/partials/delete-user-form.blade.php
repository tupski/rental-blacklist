<div>
    <p class="text-muted mb-4">
        Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen.
        Sebelum menghapus akun, silakan unduh data atau informasi yang ingin Anda simpan.
    </p>

    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
        <i class="fas fa-trash me-2"></i>
        Hapus Akun
    </button>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger" id="deleteAccountModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Konfirmasi Hapus Akun
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="{{ route('profil.hapus') }}">
                @csrf
                @method('delete')

                <div class="modal-body">
                    <div class="alert alert-danger">
                        <strong>Peringatan!</strong> Tindakan ini tidak dapat dibatalkan.
                    </div>

                    <p class="mb-3">
                        Apakah Anda yakin ingin menghapus akun Anda? Setelah akun dihapus,
                        semua sumber daya dan data akan dihapus secara permanen.
                    </p>

                    <div class="mb-3">
                        <label for="delete_password" class="form-label">
                            Masukkan password untuk konfirmasi:
                        </label>
                        <input type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                               id="delete_password" name="password" placeholder="Password Anda" required>
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>
                        Ya, Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->userDeletion->isNotEmpty())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteAccountModal'));
        deleteModal.show();
    });
</script>
@endif
