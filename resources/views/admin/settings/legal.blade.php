@extends('layouts.admin')

@section('title', 'Pengaturan Halaman Legal')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pengaturan Halaman Legal</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Pengaturan Legal</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('admin.pengaturan.legal.perbarui') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-gavel mr-2"></i>
                            Pengaturan Halaman Legal
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Terms of Service -->
                            <div class="col-12 mb-4">
                                <div class="form-group">
                                    <label for="terms_of_service" class="form-label fw-bold">
                                        <i class="fas fa-file-contract text-primary me-2"></i>
                                        Syarat dan Ketentuan
                                    </label>
                                    <textarea 
                                        name="settings[terms_of_service]" 
                                        id="terms_of_service" 
                                        class="form-control" 
                                        rows="15"
                                        placeholder="Masukkan konten syarat dan ketentuan..."
                                    >{{ old('settings.terms_of_service', \App\Models\Setting::get('terms_of_service', '')) }}</textarea>
                                    <small class="form-text text-muted">
                                        Konten ini akan ditampilkan di halaman Syarat dan Ketentuan. Anda dapat menggunakan HTML untuk formatting.
                                    </small>
                                </div>
                            </div>

                            <!-- Privacy Policy -->
                            <div class="col-12 mb-4">
                                <div class="form-group">
                                    <label for="privacy_policy" class="form-label fw-bold">
                                        <i class="fas fa-shield-alt text-success me-2"></i>
                                        Kebijakan Privasi
                                    </label>
                                    <textarea 
                                        name="settings[privacy_policy]" 
                                        id="privacy_policy" 
                                        class="form-control" 
                                        rows="15"
                                        placeholder="Masukkan konten kebijakan privasi..."
                                    >{{ old('settings.privacy_policy', \App\Models\Setting::get('privacy_policy', '')) }}</textarea>
                                    <small class="form-text text-muted">
                                        Konten ini akan ditampilkan di halaman Kebijakan Privasi. Anda dapat menggunakan HTML untuk formatting.
                                    </small>
                                </div>
                            </div>

                            <!-- Display Settings -->
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-eye text-info me-2"></i>
                                        Tampilkan Link Syarat & Ketentuan
                                    </label>
                                    <div class="form-check form-switch">
                                        <input 
                                            class="form-check-input" 
                                            type="checkbox" 
                                            name="settings[show_terms_link]" 
                                            id="show_terms_link"
                                            value="1"
                                            {{ \App\Models\Setting::get('show_terms_link', '1') == '1' ? 'checked' : '' }}
                                        >
                                        <label class="form-check-label" for="show_terms_link">
                                            Tampilkan link di footer
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-eye text-info me-2"></i>
                                        Tampilkan Link Kebijakan Privasi
                                    </label>
                                    <div class="form-check form-switch">
                                        <input 
                                            class="form-check-input" 
                                            type="checkbox" 
                                            name="settings[show_privacy_link]" 
                                            id="show_privacy_link"
                                            value="1"
                                            {{ \App\Models\Setting::get('show_privacy_link', '1') == '1' ? 'checked' : '' }}
                                        >
                                        <label class="form-check-label" for="show_privacy_link">
                                            Tampilkan link di footer
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Simpan Pengaturan
                        </button>
                        <a href="{{ route('syarat-ketentuan') }}" target="_blank" class="btn btn-outline-info">
                            <i class="fas fa-external-link-alt me-2"></i>
                            Preview Syarat & Ketentuan
                        </a>
                        <a href="{{ route('kebijakan-privasi') }}" target="_blank" class="btn btn-outline-success">
                            <i class="fas fa-external-link-alt me-2"></i>
                            Preview Kebijakan Privasi
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize CKEditor for Terms of Service
    ClassicEditor
        .create(document.querySelector('#terms_of_service'), {
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'link', '|',
                'bulletedList', 'numberedList', '|',
                'outdent', 'indent', '|',
                'blockQuote', 'insertTable', '|',
                'undo', 'redo'
            ],
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                ]
            }
        })
        .catch(error => {
            console.error('Error initializing CKEditor for Terms:', error);
        });

    // Initialize CKEditor for Privacy Policy
    ClassicEditor
        .create(document.querySelector('#privacy_policy'), {
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'link', '|',
                'bulletedList', 'numberedList', '|',
                'outdent', 'indent', '|',
                'blockQuote', 'insertTable', '|',
                'undo', 'redo'
            ],
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                ]
            }
        })
        .catch(error => {
            console.error('Error initializing CKEditor for Privacy:', error);
        });
});
</script>
@endpush
@endsection
