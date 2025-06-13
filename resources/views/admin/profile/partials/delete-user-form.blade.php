<div class="alert alert-danger">
    <h5><i class="fas fa-exclamation-triangle"></i> Hapus Akun</h5>
    <p class="mb-3">
        Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen. 
        Sebelum menghapus akun, silakan unduh data atau informasi yang ingin Anda simpan.
    </p>
    
    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmUserDeletion">
        <i class="fas fa-trash"></i> Hapus Akun
    </button>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmUserDeletion" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white">
                    <i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus Akun
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <strong>Peringatan!</strong> Tindakan ini tidak dapat dibatalkan.
                    </div>
                    
                    <p>
                        Apakah Anda yakin ingin menghapus akun Anda? Setelah akun dihapus, 
                        semua sumber daya dan data akan dihapus secara permanen.
                    </p>
                    
                    <div class="form-group">
                        <label for="password">Masukkan password untuk konfirmasi:</label>
                        <input type="password" 
                               class="form-control @error('password', 'userDeletion') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="Password"
                               required>
                        @error('password', 'userDeletion')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Ya, Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
