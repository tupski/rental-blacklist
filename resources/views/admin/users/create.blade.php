@extends('layouts.admin')

@section('title', 'Tambah User')
@section('page-title', 'Tambah User')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.pengguna.indeks') }}">Manajemen User</a></li>
    <li class="breadcrumb-item active">Tambah User</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Form Tambah User</h3>
            </div>
            <form action="{{ route('admin.pengguna.simpan') }}" method="POST">
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
                    <a href="{{ route('admin.pengguna.indeks') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Catatan Penting</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Informasi:</strong>
                    <ul class="mb-0 mt-2">
                        <li>User yang dibuat akan memiliki role <strong>Pengusaha Rental</strong></li>
                        <li>Password akan dikirim via email jika notifikasi diaktifkan</li>
                        <li>User dapat mengubah password setelah login</li>
                        <li>Email harus unik dalam sistem</li>
                    </ul>
                </div>
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
                url: '{{ route("admin.pengguna.cek-email") }}',
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
