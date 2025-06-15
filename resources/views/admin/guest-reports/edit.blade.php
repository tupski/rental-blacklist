@extends('layouts.admin')

@section('title', 'Edit Laporan Tamu')

@section('content_header')
    <h1>Edit Laporan Tamu</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Laporan Tamu: {{ $guestReport->nama_terlapor }}
                </h3>
                <div class="card-tools">
                    <a href="{{ route('admin.laporan-tamu.tampil', $guestReport) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                    <a href="{{ route('admin.laporan-tamu.indeks') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <form action="{{ route('admin.laporan-tamu.perbarui', $guestReport) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <!-- Status -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status Laporan <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                    <option value="pending" {{ $guestReport->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ $guestReport->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ $guestReport->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Admin Notes -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="admin_notes">Catatan Admin</label>
                                <textarea name="admin_notes" id="admin_notes" rows="3" 
                                          class="form-control @error('admin_notes') is-invalid @enderror"
                                          placeholder="Catatan atau alasan untuk status ini...">{{ old('admin_notes', $guestReport->admin_notes) }}</textarea>
                                @error('admin_notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Informasi Pelapor (Read Only) -->
                    <h5 class="text-primary mb-3">
                        <i class="fas fa-user mr-2"></i>
                        Informasi Pelapor
                    </h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nama Pelapor</label>
                                <input type="text" class="form-control" value="{{ $guestReport->nama_pelapor }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Email Pelapor</label>
                                <input type="email" class="form-control" value="{{ $guestReport->email_pelapor }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>No. HP Pelapor</label>
                                <input type="text" class="form-control" value="{{ $guestReport->no_hp_pelapor }}" readonly>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Informasi Terlapor (Read Only) -->
                    <h5 class="text-danger mb-3">
                        <i class="fas fa-user-times mr-2"></i>
                        Informasi Terlapor
                    </h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nama Terlapor</label>
                                <input type="text" class="form-control" value="{{ $guestReport->nama_terlapor }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>NIK</label>
                                <input type="text" class="form-control" value="{{ $guestReport->nik }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>No. HP</label>
                                <input type="text" class="form-control" value="{{ $guestReport->no_hp }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jenis Rental</label>
                                <input type="text" class="form-control" value="{{ $guestReport->jenis_rental }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Kejadian</label>
                                <input type="text" class="form-control" 
                                       value="{{ $guestReport->tanggal_kejadian ? $guestReport->tanggal_kejadian->format('d/m/Y') : '-' }}" readonly>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Detail Laporan (Read Only) -->
                    <h5 class="text-warning mb-3">
                        <i class="fas fa-file-alt mr-2"></i>
                        Detail Laporan
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jenis Laporan</label>
                                <input type="text" class="form-control" 
                                       value="{{ is_array($guestReport->jenis_laporan) ? implode(', ', $guestReport->jenis_laporan) : $guestReport->jenis_laporan }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kerugian Finansial</label>
                                <input type="text" class="form-control" 
                                       value="{{ $guestReport->kerugian_finansial ? 'Rp ' . number_format($guestReport->kerugian_finansial, 0, ',', '.') : '-' }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Kronologi Kejadian</label>
                        <textarea class="form-control" rows="4" readonly>{{ $guestReport->kronologi }}</textarea>
                    </div>

                    @if($guestReport->bukti_pendukung && count($guestReport->bukti_pendukung) > 0)
                    <div class="form-group">
                        <label>Bukti Pendukung</label>
                        <div class="row">
                            @foreach($guestReport->bukti_pendukung as $file)
                                <div class="col-md-3 mb-2">
                                    <div class="card">
                                        <div class="card-body text-center p-2">
                                            @if(in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                                <img src="{{ Storage::url($file) }}" class="img-fluid" style="max-height: 100px;">
                                            @else
                                                <i class="fas fa-file fa-3x text-muted"></i>
                                            @endif
                                            <p class="small mt-1 mb-0">{{ basename($file) }}</p>
                                            <a href="{{ Storage::url($file) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <hr>

                    <!-- Informasi Sistem -->
                    <h5 class="text-info mb-3">
                        <i class="fas fa-info-circle mr-2"></i>
                        Informasi Sistem
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Laporan</label>
                                <input type="text" class="form-control" 
                                       value="{{ $guestReport->created_at ? $guestReport->created_at->format('d/m/Y H:i:s') : '-' }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Terakhir Diupdate</label>
                                <input type="text" class="form-control" 
                                       value="{{ $guestReport->updated_at ? $guestReport->updated_at->format('d/m/Y H:i:s') : '-' }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.laporan-tamu.indeks') }}" class="btn btn-secondary">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            Update Laporan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Show/hide admin notes based on status
    $('#status').on('change', function() {
        const status = $(this).val();
        if (status === 'rejected') {
            $('#admin_notes').attr('required', true);
            $('#admin_notes').attr('placeholder', 'Wajib diisi untuk status rejected - jelaskan alasan penolakan...');
        } else {
            $('#admin_notes').removeAttr('required');
            $('#admin_notes').attr('placeholder', 'Catatan atau alasan untuk status ini...');
        }
    });

    // Trigger change event on page load
    $('#status').trigger('change');
});
</script>
@endpush
