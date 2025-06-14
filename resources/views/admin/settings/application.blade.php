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
                    <small class="text-muted">SEO, Media Sosial, dan Pengaturan Umum</small>
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
                                            @default
                                                {{ ucfirst($group) }}
                                        @endswitch
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
</script>

<!-- Toastr for notifications -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@endpush
