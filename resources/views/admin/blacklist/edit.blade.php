@extends('layouts.admin')

@section('title', 'Edit Blacklist')
@section('page-title', 'Edit Blacklist')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.daftar-hitam.indeks') }}">Daftar Blacklist</a></li>
    <li class="breadcrumb-item active">Edit Blacklist</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Form Edit Blacklist</h3>
            </div>
            <form action="{{ route('admin.daftar-hitam.perbarui', $blacklist->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_lengkap">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror"
                                       id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $blacklist->nama_lengkap) }}" required>
                                @error('nama_lengkap')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nik">NIK <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nik') is-invalid @enderror"
                                       id="nik" name="nik" value="{{ old('nik', $blacklist->nik) }}" required>
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
                                    <option value="L" {{ old('jenis_kelamin', $blacklist->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin', $blacklist->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
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
                                       id="no_hp" name="no_hp" value="{{ old('no_hp', $blacklist->no_hp) }}" required>
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
                                    <option value="Rental Mobil" {{ old('jenis_rental', $blacklist->jenis_rental) == 'Rental Mobil' ? 'selected' : '' }}>Rental Mobil</option>
                                    <option value="Rental Motor" {{ old('jenis_rental', $blacklist->jenis_rental) == 'Rental Motor' ? 'selected' : '' }}>Rental Motor</option>
                                    <option value="Kamera" {{ old('jenis_rental', $blacklist->jenis_rental) == 'Kamera' ? 'selected' : '' }}>Kamera</option>
                                    <option value="Lainnya" {{ old('jenis_rental', $blacklist->jenis_rental) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
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
                                $selectedJenisLaporan = old('jenis_laporan', $blacklist->jenis_laporan ?? []);
                            @endphp
                            @foreach($jenisLaporan as $jenis)
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input @error('jenis_laporan') is-invalid @enderror"
                                           type="checkbox" name="jenis_laporan[]" value="{{ $jenis }}"
                                           id="jenis_{{ $loop->index }}"
                                           {{ in_array($jenis, $selectedJenisLaporan) ? 'checked' : '' }}>
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
                                  id="alamat" name="alamat" rows="3">{{ old('alamat', $blacklist->alamat) }}</textarea>
                        @error('alamat')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="kronologi">Kronologi Masalah <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('kronologi') is-invalid @enderror"
                                  id="kronologi" name="kronologi" rows="4" required>{{ old('kronologi', $blacklist->kronologi) }}</textarea>
                        @error('kronologi')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    @if($blacklist->bukti && count($blacklist->bukti) > 0)
                    <div class="form-group">
                        <label>Bukti Saat Ini:</label>
                        <div class="row">
                            @foreach($blacklist->bukti as $index => $bukti)
                            <div class="col-md-3 mb-2">
                                @if(in_array(pathinfo($bukti, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                                    <img src="{{ asset('storage/bukti/' . $bukti) }}" class="img-fluid img-thumbnail" style="max-height: 100px;">
                                @else
                                    <div class="text-center">
                                        <i class="fas fa-file fa-2x text-muted"></i>
                                        <p class="small">{{ $bukti }}</p>
                                    </div>
                                @endif
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="delete_bukti[]" value="{{ $index }}" id="delete_{{ $index }}">
                                    <label class="form-check-label" for="delete_{{ $index }}">Hapus</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="form-group">
                        <label for="bukti">Tambah Bukti Baru (Foto/Dokumen)</label>
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
                            <option value="Pending" {{ old('status_validitas', $blacklist->status_validitas) == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Valid" {{ old('status_validitas', $blacklist->status_validitas) == 'Valid' ? 'selected' : '' }}>Valid</option>
                            <option value="Invalid" {{ old('status_validitas', $blacklist->status_validitas) == 'Invalid' ? 'selected' : '' }}>Invalid</option>
                        </select>
                        @error('status_validitas')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="catatan_admin">Catatan Admin</label>
                        <textarea class="form-control @error('catatan_admin') is-invalid @enderror"
                                  id="catatan_admin" name="catatan_admin" rows="3">{{ old('catatan_admin', $blacklist->catatan_admin) }}</textarea>
                        @error('catatan_admin')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Update
                    </button>
                    <a href="{{ route('admin.daftar-hitam.tampil', $blacklist->id) }}" class="btn btn-secondary">
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
                <p><strong>Petunjuk Edit:</strong></p>
                <ul class="list-unstyled">
                    <li><i class="fas fa-info text-info"></i> Centang "Hapus" untuk menghapus bukti lama</li>
                    <li><i class="fas fa-info text-info"></i> Upload file baru jika diperlukan</li>
                    <li><i class="fas fa-info text-info"></i> Ubah status validitas sesuai kebutuhan</li>
                    <li><i class="fas fa-info text-info"></i> Tambahkan catatan admin jika perlu</li>
                </ul>

                <hr>

                <p><strong>Data Terakhir Update:</strong></p>
                <p class="text-muted">{{ $blacklist->updated_at->format('d/m/Y H:i:s') }}</p>

                <p><strong>Dibuat oleh:</strong></p>
                <p class="text-muted">{{ $blacklist->user->name ?? 'N/A' }}</p>
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
