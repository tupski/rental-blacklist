@extends('layouts.admin')

@section('title', 'Pengaturan Email')

@section('content_header')
    <h1>Pengaturan Email</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-envelope mr-2"></i>
                    Pengaturan Email & Template
                </h3>
            </div>
            <div class="card-body">
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs" id="emailTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="smtp-tab" data-toggle="tab" href="#smtp" role="tab" aria-controls="smtp" aria-selected="true">
                            <i class="fas fa-server mr-1"></i> Konfigurasi SMTP
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="templates-tab" data-toggle="tab" href="#templates" role="tab" aria-controls="templates" aria-selected="false">
                            <i class="fas fa-file-alt mr-1"></i> Template Email
                        </a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content mt-3" id="emailTabsContent">
                    <!-- SMTP Configuration Tab -->
                    <div class="tab-pane fade show active" id="smtp" role="tabpanel" aria-labelledby="smtp-tab">
                        <form id="smtpSettingsForm" action="{{ route('admin.pengaturan.email.perbarui') }}" method="POST">
                            @csrf
                            @method('PUT')

                            @if(isset($settings['smtp']) && $settings['smtp']->count() > 0)
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-server mr-2"></i>
                                            Konfigurasi Email Server
                                        </h5>
                                        <div class="card-tools">
                                            <button type="button" id="testSmtp" class="btn btn-info btn-sm">
                                                <i class="fas fa-paper-plane"></i> Test Email
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach($settings['smtp'] as $setting)
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="setting_{{ $setting->key }}">
                                                            {{ $setting->label }}
                                                            @if($setting->description)
                                                                <small class="text-muted d-block">{{ $setting->description }}</small>
                                                            @endif
                                                        </label>

                                                        @switch($setting->type)
                                                            @case('password')
                                                                <input
                                                                    type="password"
                                                                    name="settings[{{ $setting->key }}]"
                                                                    id="setting_{{ $setting->key }}"
                                                                    value="{{ $setting->value }}"
                                                                    class="form-control"
                                                                >
                                                                @break

                                                            @case('email')
                                                                <input
                                                                    type="email"
                                                                    name="settings[{{ $setting->key }}]"
                                                                    id="setting_{{ $setting->key }}"
                                                                    value="{{ $setting->value }}"
                                                                    class="form-control"
                                                                >
                                                                @break

                                                            @case('number')
                                                                <input
                                                                    type="number"
                                                                    name="settings[{{ $setting->key }}]"
                                                                    id="setting_{{ $setting->key }}"
                                                                    value="{{ $setting->value }}"
                                                                    class="form-control"
                                                                >
                                                                @break

                                                            @case('select')
                                                                <select
                                                                    name="settings[{{ $setting->key }}]"
                                                                    id="setting_{{ $setting->key }}"
                                                                    class="form-control"
                                                                >
                                                                    @if($setting->key === 'smtp_encryption')
                                                                        <option value="tls" {{ $setting->value === 'tls' ? 'selected' : '' }}>TLS</option>
                                                                        <option value="ssl" {{ $setting->value === 'ssl' ? 'selected' : '' }}>SSL</option>
                                                                        <option value="" {{ $setting->value === '' ? 'selected' : '' }}>None</option>
                                                                    @endif
                                                                </select>
                                                                @break

                                                            @default
                                                                <input
                                                                    type="text"
                                                                    name="settings[{{ $setting->key }}]"
                                                                    id="setting_{{ $setting->key }}"
                                                                    value="{{ $setting->value }}"
                                                                    class="form-control"
                                                                >
                                                        @endswitch
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Belum ada pengaturan SMTP yang tersedia. Silakan jalankan seeder untuk menambahkan pengaturan default.
                                    <br><br>
                                    <code>php artisan db:seed --class=SettingsSeeder</code>
                                </div>
                            @endif

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Pengaturan SMTP
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Email Templates Tab -->
                    <div class="tab-pane fade" id="templates" role="tabpanel" aria-labelledby="templates-tab">
                        <form id="templatesForm" action="{{ route('admin.pengaturan.email.perbarui') }}" method="POST">
                            @csrf
                            @method('PUT')

                            @if(isset($settings['email_templates']) && $settings['email_templates']->count() > 0)
                                @foreach($settings['email_templates'] as $template)
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">
                                                <i class="fas fa-file-alt mr-2"></i>
                                                {{ $template->label }}
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            @if($template->description)
                                                <p class="text-muted">{{ $template->description }}</p>
                                            @endif

                                            <div class="form-group">
                                                <label for="setting_{{ $template->key }}">Template Content:</label>
                                                <textarea
                                                    name="settings[{{ $template->key }}]"
                                                    id="setting_{{ $template->key }}"
                                                    class="form-control ckeditor"
                                                    rows="10"
                                                >{{ $template->value }}</textarea>
                                            </div>

                                            @if($template->key === 'email_template_verification')
                                                <div class="alert alert-info">
                                                    <strong>Variabel yang tersedia:</strong> {{name}}, {{email}}, {{verification_url}}
                                                </div>
                                            @elseif($template->key === 'email_template_registration')
                                                <div class="alert alert-info">
                                                    <strong>Variabel yang tersedia:</strong> {{name}}, {{email}}, {{role}}
                                                </div>
                                            @elseif($template->key === 'email_template_account_suspended')
                                                <div class="alert alert-info">
                                                    <strong>Variabel yang tersedia:</strong> {{name}}, {{email}}, {{reason}}
                                                </div>
                                            @elseif($template->key === 'email_template_topup')
                                                <div class="alert alert-info">
                                                    <strong>Variabel yang tersedia:</strong> {{name}}, {{amount}}, {{method}}, {{status}}, {{date}}, {{status_message}}
                                                </div>
                                            @elseif($template->key === 'email_template_password_reset')
                                                <div class="alert alert-info">
                                                    <strong>Variabel yang tersedia:</strong> {{name}}, {{email}}, {{reset_url}}
                                                </div>
                                            @elseif($template->key === 'email_template_report_notification')
                                                <div class="alert alert-info">
                                                    <strong>Variabel yang tersedia:</strong> {{reporter_name}}, {{rental_name}}, {{category}}, {{date}}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Belum ada template email yang tersedia. Silakan jalankan seeder untuk menambahkan template default.
                                    <br><br>
                                    <code>php artisan db:seed --class=SettingsSeeder</code>
                                </div>
                            @endif

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Template Email
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.dasbor') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Test Email Modal -->
<div class="modal fade" id="testEmailModal" tabindex="-1" role="dialog" aria-labelledby="testEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="testEmailModalLabel">Test Email SMTP</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="testEmailForm">
                    <div class="form-group">
                        <label for="test_email">Email Tujuan:</label>
                        <input type="email" class="form-control" id="test_email" name="test_email" required>
                        <small class="form-text text-muted">Masukkan email untuk mengirim test email</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="sendTestEmail">
                    <i class="fas fa-paper-plane"></i> Kirim Test Email
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- CKEditor 5 -->
<style>
.ck-editor__editable {
    min-height: 200px;
}
</style>
@endpush

@push('scripts')
<!-- CKEditor 5 -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script>
$(document).ready(function() {
    // Initialize CKEditor for all textareas with class 'ckeditor'
    const editorInstances = {};

    document.querySelectorAll('.ckeditor').forEach(function(textarea) {
        ClassicEditor
            .create(textarea, {
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
            .then(editor => {
                editorInstances[textarea.id] = editor;
            })
            .catch(error => {
                console.error('Error initializing CKEditor:', error);
            });
    });

    // Test SMTP button
    $('#testSmtp').on('click', function() {
        $('#testEmailModal').modal('show');
    });

    // Send test email
    $('#sendTestEmail').on('click', function() {
        const btn = $(this);
        const originalText = btn.html();
        const testEmail = $('#test_email').val();

        if (!testEmail) {
            alert('Silakan masukkan email tujuan');
            return;
        }

        btn.html('<i class="fas fa-spinner fa-spin"></i> Mengirim...').prop('disabled', true);

        // Get current SMTP settings from form
        const smtpData = {
            _token: '{{ csrf_token() }}',
            test_email: testEmail,
            smtp_host: $('#setting_smtp_host').val(),
            smtp_port: $('#setting_smtp_port').val(),
            smtp_username: $('#setting_smtp_username').val(),
            smtp_password: $('#setting_smtp_password').val(),
            smtp_encryption: $('#setting_smtp_encryption').val(),
            mail_from_address: $('#setting_mail_from_address').val(),
            mail_from_name: $('#setting_mail_from_name').val()
        };

        $.post('{{ route("admin.pengaturan.email.tes") }}', smtpData)
            .done(function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#testEmailModal').modal('hide');
                } else {
                    toastr.error(response.message);
                }
            })
            .fail(function(xhr) {
                let message = 'Terjadi kesalahan saat mengirim test email';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                toastr.error(message);
            })
            .always(function() {
                btn.html(originalText).prop('disabled', false);
            });
    });

    // Form submissions with CKEditor data sync
    $('#smtpSettingsForm, #templatesForm').on('submit', function() {
        // Sync CKEditor data before submit
        Object.keys(editorInstances).forEach(function(textareaId) {
            const editor = editorInstances[textareaId];
            const textarea = document.getElementById(textareaId);
            if (editor && textarea) {
                textarea.value = editor.getData();
            }
        });
    });
});
</script>

<!-- Toastr for notifications -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

@if(session('success'))
<script>
    toastr.success('{{ session('success') }}');
</script>
@endif

@if(session('error'))
<script>
    toastr.error('{{ session('error') }}');
</script>
@endif
@endpush
