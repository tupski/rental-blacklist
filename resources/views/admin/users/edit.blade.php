@extends('layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.pengguna.indeks') }}">Manajemen User</a></li>
    <li class="breadcrumb-item active">Edit User</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit User: {{ $user->name }}</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.pengguna.tampil', $user->id) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                    <a href="{{ route('admin.pengguna.indeks') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <form action="{{ route('admin.pengguna.perbarui', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role">Role <span class="text-danger">*</span></label>
                                <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
                                    <option value="">Pilih Role</option>
                                    <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                                    <option value="pengusaha_rental" {{ old('role', $user->role) === 'pengusaha_rental' ? 'selected' : '' }}>Pengusaha Rental</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nik">NIK</label>
                                <input type="text" class="form-control @error('nik') is-invalid @enderror"
                                       id="nik" name="nik" value="{{ old('nik', $user->nik) }}" maxlength="16">
                                @error('nik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="no_hp">No. HP</label>
                                <input type="text" class="form-control @error('no_hp') is-invalid @enderror"
                                       id="no_hp" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" maxlength="15">
                                @error('no_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email_verified">Status Email</label>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="email_verified" name="email_verified"
                                           {{ $user->email_verified_at ? 'checked' : '' }}>
                                    <label class="form-check-label" for="email_verified">
                                        Email sudah terverifikasi
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror"
                                  id="alamat" name="alamat" rows="3">{{ old('alamat', $user->alamat) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="reset_password" name="reset_password">
                            <label class="form-check-label" for="reset_password">
                                Reset password ke "password123"
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.pengguna.tampil', $user->id) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Info User -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi User</h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td><strong>ID:</strong></td>
                        <td>{{ $user->id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Terdaftar:</strong></td>
                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Update Terakhir:</strong></td>
                        <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Saldo:</strong></td>
                        <td>{{ $user->getFormattedBalance() }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Peringatan -->
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Peringatan</h3>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li><i class="fas fa-exclamation-triangle text-warning"></i> Perubahan email akan memerlukan verifikasi ulang</li>
                    <li><i class="fas fa-exclamation-triangle text-warning"></i> Reset password akan mengirim notifikasi ke user</li>
                    <li><i class="fas fa-exclamation-triangle text-warning"></i> Perubahan role akan mempengaruhi akses user</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Format NIK input
    $('#nik').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Format phone number
    $('#no_hp').on('input', function() {
        let value = this.value.replace(/[^0-9]/g, '');
        if (value.startsWith('62')) {
            value = '0' + value.substring(2);
        }
        this.value = value;
    });

    // Email validation
    $('#email').on('blur', function() {
        let email = $(this).val();
        let userId = {{ $user->id }};

        if (email && email !== '{{ $user->email }}') {
            $.get('{{ route("admin.pengguna.cek-email") }}', {email: email})
                .done(function(data) {
                    if (data.exists) {
                        $('#email').addClass('is-invalid');
                        $('#email').after('<div class="invalid-feedback">Email sudah digunakan</div>');
                    } else {
                        $('#email').removeClass('is-invalid');
                        $('#email').siblings('.invalid-feedback').remove();
                    }
                });
        }
    });
});
</script>
@endpush
