@extends('layouts.admin')

@section('title', 'Tambah User')
@section('page-title', 'Tambah User')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Manajemen User</a></li>
    <li class="breadcrumb-item active">Tambah User</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Form Tambah User</h3>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="role">Role <span class="text-danger">*</span></label>
                        <select class="form-control @error('role') is-invalid @enderror" 
                                id="role" name="role" required>
                            <option value="">Pilih Role</option>
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User Biasa</option>
                            <option value="pengusaha_rental" {{ old('role') == 'pengusaha_rental' ? 'selected' : '' }}>Pengusaha Rental</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <small class="form-text text-muted">Minimal 8 karakter</small>
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="email_verified" name="email_verified" value="1" {{ old('email_verified') ? 'checked' : '' }}>
                            <label class="form-check-label" for="email_verified">
                                Tandai email sebagai terverifikasi
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="send_notification" name="send_notification" value="1" {{ old('send_notification', '1') ? 'checked' : '' }}>
                            <label class="form-check-label" for="send_notification">
                                Kirim notifikasi email ke user
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Informasi Role</h3>
            </div>
            <div class="card-body">
                <h6><strong>User Biasa:</strong></h6>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check text-success"></i> Dapat melakukan topup saldo</li>
                    <li><i class="fas fa-check text-success"></i> Dapat membuka data blacklist</li>
                    <li><i class="fas fa-check text-success"></i> Akses terbatas</li>
                </ul>

                <h6><strong>Pengusaha Rental:</strong></h6>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check text-success"></i> Dapat menambah data blacklist</li>
                    <li><i class="fas fa-check text-success"></i> Dapat mengelola API key</li>
                    <li><i class="fas fa-check text-success"></i> Akses dashboard rental</li>
                </ul>

                <h6><strong>Admin:</strong></h6>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check text-success"></i> Akses penuh sistem</li>
                    <li><i class="fas fa-check text-success"></i> Dapat mengelola semua data</li>
                    <li><i class="fas fa-check text-success"></i> Dapat validasi blacklist</li>
                </ul>
            </div>
        </div>

        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Catatan Penting</h3>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li><i class="fas fa-exclamation-triangle text-warning"></i> Password akan dikirim via email jika notifikasi diaktifkan</li>
                    <li><i class="fas fa-exclamation-triangle text-warning"></i> User dapat mengubah password setelah login</li>
                    <li><i class="fas fa-exclamation-triangle text-warning"></i> Email harus unik dalam sistem</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle password visibility
    $('#togglePassword').click(function() {
        const passwordField = $('#password');
        const icon = $(this).find('i');
        
        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
    
    // Generate random password
    $('#generatePassword').click(function() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
        let password = '';
        for (let i = 0; i < 12; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        $('#password').val(password);
        $('#password_confirmation').val(password);
    });
    
    // Email validation
    $('#email').on('blur', function() {
        const email = $(this).val();
        if (email) {
            $.ajax({
                url: '{{ route("admin.users.check-email") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    email: email
                },
                success: function(response) {
                    if (response.exists) {
                        $('#email').addClass('is-invalid');
                        $('#email').siblings('.invalid-feedback').text('Email sudah digunakan');
                    } else {
                        $('#email').removeClass('is-invalid');
                    }
                }
            });
        }
    });
});
</script>
@endpush
