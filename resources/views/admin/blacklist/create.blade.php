@extends('layouts.admin')

@section('title', 'Tambah Blacklist')
@section('page-title', 'Tambah Blacklist')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.daftar-hitam.indeks') }}">Daftar Blacklist</a></li>
    <li class="breadcrumb-item active">Tambah Blacklist</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Form Tambah Blacklist</h3>
            </div>
            <form action="{{ route('admin.daftar-hitam.simpan') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror"
                                       id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>
                                @error('nama_lengkap')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nik">NIK <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nik') is-invalid @enderror"
                                       id="nik" name="nik" value="{{ old('nik') }}" required>
                                @error('nik')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select class="form-control @error('jenis_kelamin') is-invalid @enderror"
                                        id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="no_hp">No. HP <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('no_hp') is-invalid @enderror"
                                       id="no_hp" name="no_hp" value="{{ old('no_hp') }}" required>
                                @error('no_hp')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jenis_rental">Jenis Rental <span class="text-danger">*</span></label>
                                <select class="form-control @error('jenis_rental') is-invalid @enderror"
                                        id="jenis_rental" name="jenis_rental" required>
                                    <option value="">Pilih Jenis Rental</option>
                                    <option value="Rental Mobil" {{ old('jenis_rental') == 'Rental Mobil' ? 'selected' : '' }}>Rental Mobil</option>
                                    <option value="Rental Motor" {{ old('jenis_rental') == 'Rental Motor' ? 'selected' : '' }}>Rental Motor</option>
                                    <option value="Kamera" {{ old('jenis_rental') == 'Kamera' ? 'selected' : '' }}>Kamera</option>
                                    <option value="Lainnya" {{ old('jenis_rental') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('jenis_rental')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Jenis Masalah <span class="text-danger">*</span></label>
                        <div class="row">
                            @php
                                $jenisLaporan = ['Tidak Mengembalikan', 'Merusak Barang', 'Tidak Bayar', 'Kabur', 'Lainnya'];
                            @endphp
                            @foreach($jenisLaporan as $jenis)
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input @error('jenis_laporan') is-invalid @enderror"
                                           type="checkbox" name="jenis_laporan[]" value="{{ $jenis }}"
                                           id="jenis_{{ $loop->index }}"
                                           {{ in_array($jenis, old('jenis_laporan', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="jenis_{{ $loop->index }}">
                                        {{ $jenis }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('jenis_laporan')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror"
                                  id="alamat" name="alamat" rows="3">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="kronologi">Kronologi Masalah <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('kronologi') is-invalid @enderror"
                                  id="kronologi" name="kronologi" rows="4" required>{{ old('kronologi') }}</textarea>
                        @error('kronologi')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="bukti">Bukti (Foto/Dokumen)</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('bukti.*') is-invalid @enderror"
                                       id="bukti" name="bukti[]" multiple accept="image/*,.pdf,.doc,.docx">
                                <label class="custom-file-label" for="bukti">Pilih file...</label>
                            </div>
                        </div>
                        <small class="form-text text-muted">
                            Format yang didukung: JPG, PNG, PDF, DOC, DOCX. Maksimal 5 file.
                        </small>
                        @error('bukti.*')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status_validitas">Status Validitas</label>
                        <select class="form-control @error('status_validitas') is-invalid @enderror"
                                id="status_validitas" name="status_validitas">
                            <option value="Pending" {{ old('status_validitas', 'Pending') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Valid" {{ old('status_validitas') == 'Valid' ? 'selected' : '' }}>Valid</option>
                            <option value="Invalid" {{ old('status_validitas') == 'Invalid' ? 'selected' : '' }}>Invalid</option>
                        </select>
                        @error('status_validitas')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="catatan_admin">Catatan Admin</label>
                        <textarea class="form-control @error('catatan_admin') is-invalid @enderror"
                                  id="catatan_admin" name="catatan_admin" rows="3">{{ old('catatan_admin') }}</textarea>
                        @error('catatan_admin')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('admin.daftar-hitam.indeks') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Informasi</h3>
            </div>
            <div class="card-body">
                <p><strong>Petunjuk Pengisian:</strong></p>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check text-success"></i> Pastikan data yang dimasukkan akurat</li>
                    <li><i class="fas fa-check text-success"></i> NIK harus 16 digit</li>
                    <li><i class="fas fa-check text-success"></i> No. HP dalam format 08xxxxxxxxxx</li>
                    <li><i class="fas fa-check text-success"></i> Upload bukti yang jelas dan relevan</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Custom file input
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
    });

    // NIK validation
    $('#nik').on('input', function() {
        let nik = $(this).val();
        if (nik.length > 16) {
            $(this).val(nik.substring(0, 16));
        }
    });

    // Phone number formatting
    $('#no_hp').on('input', function() {
        let phone = $(this).val().replace(/\D/g, '');
        if (phone.length > 0 && !phone.startsWith('08')) {
            if (phone.startsWith('8')) {
                phone = '0' + phone;
            } else if (phone.startsWith('62')) {
                phone = '0' + phone.substring(2);
            }
        }
        $(this).val(phone);
    });
});
</script>
@endpush
