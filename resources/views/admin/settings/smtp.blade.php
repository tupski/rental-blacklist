@extends('layouts.admin')

@section('title', 'Pengaturan SMTP')
@section('page-title', 'Pengaturan SMTP')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item">Pengaturan</li>
    <li class="breadcrumb-item active">SMTP</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-envelope mr-2"></i>
                    Pengaturan SMTP
                </h3>
                <div class="card-tools">
                    <button type="button" id="testSmtp" class="btn btn-info btn-sm">
                        <i class="fas fa-paper-plane"></i> Test Connection
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form id="smtpSettingsForm" action="{{ route('admin.settings.smtp.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    @if(isset($settings) && $settings->count() > 0)
                        @foreach($settings as $group => $groupSettings)
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-server mr-2"></i>
                                        Konfigurasi Email Server
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($groupSettings as $setting)
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
                                                            <div class="input-group">
                                                                <input
                                                                    type="password"
                                                                    name="settings[{{ $setting->key }}]"
                                                                    id="setting_{{ $setting->key }}"
                                                                    value="{{ $setting->value }}"
                                                                    class="form-control"
                                                                    placeholder="••••••••"
                                                                >
                                                                <div class="input-group-append">
                                                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('setting_{{ $setting->key }}')">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
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

                                                        @case('number')
                                                            <input
                                                                type="number"
                                                                name="settings[{{ $setting->key }}]"
                                                                id="setting_{{ $setting->key }}"
                                                                value="{{ $setting->value }}"
                                                                class="form-control"
                                                                @if($setting->key === 'smtp_port')
                                                                    min="1"
                                                                    max="65535"
                                                                @endif
                                                            >
                                                            @break

                                                        @case('email')
                                                            <input
                                                                type="email"
                                                                name="settings[{{ $setting->key }}]"
                                                                id="setting_{{ $setting->key }}"
                                                                value="{{ $setting->value }}"
                                                                class="form-control"
                                                                placeholder="email@example.com"
                                                            >
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
                        @endforeach
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            Belum ada pengaturan SMTP yang tersedia. Silakan jalankan seeder untuk menambahkan pengaturan default.
                            <br><br>
                            <code>php artisan db:seed --class=SettingsSeeder</code>
                        </div>
                    @endif
                </form>
            </div>
            <div class="card-footer">
                <button type="submit" form="smtpSettingsForm" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Pengaturan
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<!-- SMTP Configuration Guide -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-question-circle mr-2"></i>
                    Panduan Konfigurasi SMTP
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <h6><strong>Gmail</strong></h6>
                        <ul class="list-unstyled">
                            <li><strong>Host:</strong> smtp.gmail.com</li>
                            <li><strong>Port:</strong> 587</li>
                            <li><strong>Encryption:</strong> TLS</li>
                            <li><strong>Username:</strong> your-email@gmail.com</li>
                            <li><strong>Password:</strong> App Password</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h6><strong>Outlook/Hotmail</strong></h6>
                        <ul class="list-unstyled">
                            <li><strong>Host:</strong> smtp-mail.outlook.com</li>
                            <li><strong>Port:</strong> 587</li>
                            <li><strong>Encryption:</strong> TLS</li>
                            <li><strong>Username:</strong> your-email@outlook.com</li>
                            <li><strong>Password:</strong> Your Password</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h6><strong>Yahoo Mail</strong></h6>
                        <ul class="list-unstyled">
                            <li><strong>Host:</strong> smtp.mail.yahoo.com</li>
                            <li><strong>Port:</strong> 587</li>
                            <li><strong>Encryption:</strong> TLS</li>
                            <li><strong>Username:</strong> your-email@yahoo.com</li>
                            <li><strong>Password:</strong> App Password</li>
                        </ul>
                    </div>
                </div>
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Catatan:</strong> Untuk Gmail dan Yahoo, Anda perlu menggunakan App Password, bukan password akun biasa. 
                    Pastikan juga 2-Factor Authentication sudah diaktifkan.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Test SMTP connection
    $('#testSmtp').on('click', function() {
        const btn = $(this);
        const originalText = btn.html();

        btn.html('<i class="fas fa-spinner fa-spin"></i> Testing...').prop('disabled', true);

        $.post('{{ route("admin.settings.smtp.test") }}', {
            _token: '{{ csrf_token() }}',
            smtp_host: $('#setting_smtp_host').val(),
            smtp_port: $('#setting_smtp_port').val(),
            smtp_username: $('#setting_smtp_username').val(),
            smtp_password: $('#setting_smtp_password').val(),
            smtp_encryption: $('#setting_smtp_encryption').val(),
            mail_from_address: $('#setting_mail_from_address').val(),
            mail_from_name: $('#setting_mail_from_name').val()
        })
        .done(function(response) {
            if (response.success) {
                toastr.success('SMTP connection successful!');
            } else {
                toastr.error('SMTP connection failed: ' + response.message);
            }
        })
        .fail(function() {
            toastr.error('Failed to test SMTP connection');
        })
        .always(function() {
            btn.html(originalText).prop('disabled', false);
        });
    });
    
    // Auto-fill port based on encryption
    $('#setting_smtp_encryption').on('change', function() {
        const encryption = $(this).val();
        const portField = $('#setting_smtp_port');
        
        if (encryption === 'ssl') {
            portField.val('465');
        } else if (encryption === 'tls') {
            portField.val('587');
        }
    });
});

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = field.nextElementSibling.querySelector('i');

    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>

<!-- Toastr for notifications -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@endpush
