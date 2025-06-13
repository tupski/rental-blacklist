@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Sistem')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Pengaturan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pengaturan Sistem</h3>
            </div>
            <div class="card-body">
                    <form id="settingsForm" action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        @if(isset($settings) && $settings->count() > 0)
                            @foreach($settings as $group => $groupSettings)
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            @switch($group)
                                                @case('general')
                                                    <i class="fas fa-globe mr-2"></i>
                                                    Pengaturan Umum
                                                    @break
                                                @case('seo')
                                                    <i class="fas fa-search mr-2"></i>
                                                    SEO & Meta Tags
                                                    @break
                                                @case('social')
                                                    <i class="fas fa-share-alt mr-2"></i>
                                                    Media Sosial
                                                    @break
                                                @case('smtp')
                                                    <i class="fas fa-envelope mr-2"></i>
                                                    Pengaturan SMTP
                                                    @break
                                                @case('payment')
                                                    <i class="fas fa-credit-card mr-2"></i>
                                                    Payment Gateway
                                                    @break
                                                @case('pricing')
                                                    <i class="fas fa-money-bill mr-2"></i>
                                                    Pengaturan Harga
                                                    @break
                                                @default
                                                    {{ ucfirst($group) }}
                                            @endswitch
                                        </h5>
                                        @if($group === 'smtp')
                                            <div class="card-tools">
                                                <button type="button" id="testSmtp" class="btn btn-sm btn-info">
                                                    <i class="fas fa-paper-plane"></i> Test Connection
                                                </button>
                                            </div>
                                        @endif
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
                                                            @case('textarea')
                                                                <textarea
                                                                    name="settings[{{ $setting->key }}]"
                                                                    id="setting_{{ $setting->key }}"
                                                                    rows="3"
                                                                    class="form-control"
                                                                >{{ $setting->value }}</textarea>
                                                                @break

                                                            @case('boolean')
                                                                <div class="form-check">
                                                                    <input
                                                                        type="checkbox"
                                                                        name="settings[{{ $setting->key }}]"
                                                                        id="setting_{{ $setting->key }}"
                                                                        value="1"
                                                                        {{ $setting->value ? 'checked' : '' }}
                                                                        class="form-check-input"
                                                                    >
                                                                    <label for="setting_{{ $setting->key }}" class="form-check-label">
                                                                        Aktifkan
                                                                    </label>
                                                                </div>
                                                                @break

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
                                                                    @if(str_contains($setting->key, 'price'))
                                                                        min="0"
                                                                        step="100"
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

                                                            @case('url')
                                                                <input
                                                                    type="url"
                                                                    name="settings[{{ $setting->key }}]"
                                                                    id="setting_{{ $setting->key }}"
                                                                    value="{{ $setting->value }}"
                                                                    placeholder="https://..."
                                                                    class="form-control"
                                                                >
                                                                @break

                                                            @case('json')
                                                                <textarea
                                                                    name="settings[{{ $setting->key }}]"
                                                                    id="setting_{{ $setting->key }}"
                                                                    rows="3"
                                                                    class="form-control"
                                                                    placeholder='["option1", "option2"]'
                                                                >{{ $setting->value }}</textarea>
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
                                Belum ada pengaturan yang tersedia. Silakan jalankan seeder untuk menambahkan pengaturan default.
                                <br><br>
                                <code>php artisan db:seed --class=SettingsSeeder</code>
                            </div>
                        @endif
                    </form>
            </div>
            <div class="card-footer">
                <button type="submit" form="settingsForm" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Pengaturan
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Form ID already set in HTML

    // Test SMTP connection
    $('#testSmtp').on('click', function() {
        const btn = $(this);
        const originalText = btn.html();

        btn.html('<i class="fas fa-spinner fa-spin"></i> Testing...').prop('disabled', true);

        $.post('{{ route("admin.settings.test-smtp") }}', {
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
