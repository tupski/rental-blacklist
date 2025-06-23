@extends('layouts.admin')

@section('title', 'Tambah ' . $types[$type])

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus me-2"></i>
                        Tambah {{ $types[$type] }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.atribut.indeks', ['type' => $type]) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Kembali
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.atribut.simpan') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Type -->
                                <div class="mb-3">
                                    <label for="type" class="form-label">Tipe Atribut <span class="text-danger">*</span></label>
                                    <select class="form-select @error('type') is-invalid @enderror" 
                                            id="type" 
                                            name="type" 
                                            required>
                                        @foreach($types as $typeKey => $typeLabel)
                                            <option value="{{ $typeKey }}" {{ old('type', $type) === $typeKey ? 'selected' : '' }}>
                                                {{ $typeLabel }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Name -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Tampilan <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Nama yang akan ditampilkan di form dan dropdown
                                    </small>
                                </div>

                                <!-- Value -->
                                <div class="mb-3">
                                    <label for="value" class="form-label">Value/Kode <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('value') is-invalid @enderror" 
                                           id="value" 
                                           name="value" 
                                           value="{{ old('value') }}" 
                                           required>
                                    @error('value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Value unik yang akan disimpan di database (gunakan format: huruf_kecil_dengan_underscore)
                                    </small>
                                </div>

                                <!-- Description -->
                                <div class="mb-3">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="3" 
                                              placeholder="Deskripsi opsional untuk atribut ini">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <!-- Settings -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Pengaturan</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Order -->
                                        <div class="mb-3">
                                            <label for="order" class="form-label">Urutan</label>
                                            <input type="number" 
                                                   class="form-control @error('order') is-invalid @enderror" 
                                                   id="order" 
                                                   name="order" 
                                                   value="{{ old('order', 0) }}" 
                                                   min="0">
                                            @error('order')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Angka lebih kecil akan tampil lebih dulu.
                                            </small>
                                        </div>

                                        <!-- Checkboxes -->
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="is_active" 
                                                   name="is_active" 
                                                   value="1" 
                                                   {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                Aktif
                                            </label>
                                            <small class="form-text text-muted d-block">
                                                Atribut aktif akan ditampilkan di form
                                            </small>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="is_default" 
                                                   name="is_default" 
                                                   value="1" 
                                                   {{ old('is_default') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_default">
                                                Default
                                            </label>
                                            <small class="form-text text-muted d-block">
                                                Atribut ini akan dipilih secara default di form
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Preview -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Preview</h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted small mb-2">Tampilan di form:</p>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" disabled>
                                            <label class="form-check-label" id="preview-name">
                                                [Nama akan muncul di sini]
                                            </label>
                                        </div>
                                        <p class="text-muted small mt-2 mb-0">
                                            Value: <code id="preview-value">[value]</code>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.atribut.indeks', ['type' => $type]) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Simpan Atribut
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto generate value from name
    $('#name').on('input', function() {
        if ($('#value').val() === '' || $('#value').data('auto-generated')) {
            let value = $(this).val()
                .toLowerCase()
                .replace(/[^a-z0-9\s]/g, '')
                .replace(/\s+/g, '_')
                .replace(/_+/g, '_')
                .trim('_');
            $('#value').val(value).data('auto-generated', true);
        }
        updatePreview();
    });

    // Mark value as manually edited
    $('#value').on('input', function() {
        $(this).data('auto-generated', false);
        updatePreview();
    });

    // Update preview
    function updatePreview() {
        const name = $('#name').val() || '[Nama akan muncul di sini]';
        const value = $('#value').val() || '[value]';
        
        $('#preview-name').text(name);
        $('#preview-value').text(value);
    }

    // Initial preview update
    updatePreview();
});
</script>
@endpush
