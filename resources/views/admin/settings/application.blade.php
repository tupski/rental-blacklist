@extends('layouts.admin')

@section('title', 'Pengaturan Aplikasi')
@section('page-title', 'Pengaturan Aplikasi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
    <li class="breadcrumb-item">Pengaturan</li>
    <li class="breadcrumb-item active">Aplikasi</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-globe mr-2"></i>
                    Pengaturan Aplikasi
                </h3>
                <div class="card-tools">
                    <small class="text-muted">SEO, Media Sosial, Footer, Kontak, Captcha, dan Pengaturan Umum</small>
                </div>
            </div>
            <div class="card-body">
                <form id="applicationSettingsForm" action="{{ route('admin.pengaturan.aplikasi.perbarui') }}" method="POST">
                    @csrf
                    @method('PUT')

                    @if(isset($settings) && $settings->count() > 0)
                        @foreach($settings as $group => $groupSettings)
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        @switch($group)
                                            @case('general')
                                                <i class="fas fa-cog mr-2"></i>
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
                                            @case('footer')
                                                <i class="fas fa-window-minimize mr-2"></i>
                                                Pengaturan Footer
                                                @break
                                            @case('contact')
                                                <i class="fas fa-address-book mr-2"></i>
                                                Informasi Kontak
                                                @break
                                            @case('captcha')
                                                <i class="fas fa-shield-alt mr-2"></i>
                                                Pengaturan Captcha
                                                @break
                                            @default
                                                {{ ucfirst($group) }}
                                        @endswitch
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($groupSettings as $setting)
                                            <div class="col-md-6">
                                                <div class="form-group
                                                    @if($group === 'captcha' && $setting->key !== 'captcha_enabled' && $setting->key !== 'captcha_type') captcha-field @endif
                                                    @if(str_contains($setting->key, '_site_key') || str_contains($setting->key, '_secret_key')) captcha-key-field @endif
                                                ">
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

                                                        @case('select')
                                                            <select
                                                                name="settings[{{ $setting->key }}]"
                                                                id="setting_{{ $setting->key }}"
                                                                class="form-control"
                                                                @if($setting->key === 'captcha_type') onchange="toggleCaptchaFields(this.value)" @endif
                                                            >
                                                                @if($setting->key === 'captcha_type')
                                                                    <option value="recaptcha_v2" {{ $setting->value === 'recaptcha_v2' ? 'selected' : '' }}>Google reCAPTCHA v2</option>
                                                                    <option value="recaptcha_v3" {{ $setting->value === 'recaptcha_v3' ? 'selected' : '' }}>Google reCAPTCHA v3</option>
                                                                    <option value="hcaptcha" {{ $setting->value === 'hcaptcha' ? 'selected' : '' }}>hCaptcha</option>
                                                                    <option value="turnstile" {{ $setting->value === 'turnstile' ? 'selected' : '' }}>Cloudflare Turnstile</option>
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
                        @endforeach
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            Belum ada pengaturan aplikasi yang tersedia. Silakan jalankan seeder untuk menambahkan pengaturan default.
                            <br><br>
                            <code>php artisan db:seed --class=SettingsSeeder</code>
                        </div>
                    @endif
                </form>
            </div>
            <div class="card-footer">
                <button type="submit" form="applicationSettingsForm" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Pengaturan
                </button>
                <a href="{{ route('admin.dasbor') }}" class="btn btn-secondary">
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
    // Form validation
    $('#applicationSettingsForm').on('submit', function(e) {
        let isValid = true;

        // Validate required fields
        $(this).find('input[required], textarea[required]').each(function() {
            if (!$(this).val().trim()) {
                isValid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            toastr.error('Mohon lengkapi semua field yang wajib diisi');
        }
    });

    // URL validation
    $('input[type="url"]').on('blur', function() {
        const url = $(this).val();
        if (url && !isValidUrl(url)) {
            $(this).addClass('is-invalid');
            toastr.warning('Format URL tidak valid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    // Email validation
    $('input[type="email"]').on('blur', function() {
        const email = $(this).val();
        if (email && !isValidEmail(email)) {
            $(this).addClass('is-invalid');
            toastr.warning('Format email tidak valid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
});

function isValidUrl(string) {
    try {
        new URL(string);
        return true;
    } catch (_) {
        return false;
    }
}

function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Captcha settings management
function toggleCaptchaSettings() {
    const captchaEnabled = $('#setting_captcha_enabled').is(':checked');
    const captchaGroup = $('#setting_captcha_enabled').closest('.card').find('.form-group').not($('#setting_captcha_enabled').closest('.form-group'));

    if (captchaEnabled) {
        captchaGroup.show();
        toggleCaptchaFields($('#setting_captcha_type').val());
    } else {
        captchaGroup.hide();
    }
}

function toggleCaptchaFields(selectedType) {
    // Hide all captcha key fields first
    $('[id*="_site_key"], [id*="_secret_key"]').closest('.form-group').hide();

    // Show fields based on selected type
    if (selectedType) {
        $(`[id*="${selectedType}_site_key"], [id*="${selectedType}_secret_key"]`).closest('.form-group').show();
    }
}

$(document).ready(function() {
    // Initialize captcha settings visibility
    toggleCaptchaSettings();

    // Handle captcha enabled checkbox change
    $('#setting_captcha_enabled').on('change', function() {
        toggleCaptchaSettings();
    });

    // Handle captcha type change
    $('#setting_captcha_type').on('change', function() {
        toggleCaptchaFields($(this).val());
    });
});
</script>

<!-- Toastr for notifications -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@endpush
