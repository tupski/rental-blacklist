@extends('layouts.admin')

@section('title', 'Pengaturan Pembayaran')
@section('page-title', 'Pengaturan Pembayaran')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item">Pengaturan</li>
    <li class="breadcrumb-item active">Pembayaran</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-credit-card mr-2"></i>
                    Pengaturan Pembayaran
                </h3>
                <div class="card-tools">
                    <small class="text-muted">Metode Pembayaran, Harga, dan Payment Gateway</small>
                </div>
            </div>
            <div class="card-body">
                <form id="paymentSettingsForm" action="{{ route('admin.settings.payment.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    @if(isset($settings) && $settings->count() > 0)
                        @foreach($settings as $group => $groupSettings)
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        @switch($group)
                                            @case('payment')
                                                <i class="fas fa-money-bill-wave mr-2"></i>
                                                Metode Pembayaran & Gateway
                                                @break
                                            @case('pricing')
                                                <i class="fas fa-tags mr-2"></i>
                                                Pengaturan Harga
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
                                                                rows="4"
                                                                class="form-control"
                                                                placeholder="Masukkan instruksi pembayaran..."
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

                                                        @case('number')
                                                            <div class="input-group">
                                                                @if(str_contains($setting->key, 'price'))
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">Rp</span>
                                                                    </div>
                                                                @endif
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
                                                            </div>
                                                            @break

                                                        @case('json')
                                                            <textarea
                                                                name="settings[{{ $setting->key }}]"
                                                                id="setting_{{ $setting->key }}"
                                                                rows="3"
                                                                class="form-control"
                                                                placeholder='["bank_transfer", "e_wallet", "credit_card"]'
                                                            >{{ $setting->value }}</textarea>
                                                            @break

                                                        @default
                                                            <input
                                                                type="text"
                                                                name="settings[{{ $setting->key }}]"
                                                                id="setting_{{ $setting->key }}"
                                                                value="{{ $setting->value }}"
                                                                class="form-control"
                                                                @if(str_contains($setting->key, 'number'))
                                                                    placeholder="08xx-xxxx-xxxx"
                                                                @endif
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
                            Belum ada pengaturan pembayaran yang tersedia. Silakan jalankan seeder untuk menambahkan pengaturan default.
                            <br><br>
                            <code>php artisan db:seed --class=SettingsSeeder</code>
                        </div>
                    @endif
                </form>
            </div>
            <div class="card-footer">
                <button type="submit" form="paymentSettingsForm" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Pengaturan
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Payment Methods Preview -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-university mr-2"></i>
                    Transfer Bank
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" alt="BCA" class="img-fluid" style="max-height: 30px;">
                    </div>
                    <div class="col-8">
                        <strong id="preview-bca-number">6050381330</strong><br>
                        <small id="preview-bca-name">ANGGA DWY SAPUTRA</small>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-4">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/2/2e/BRI_2020.svg" alt="BRI" class="img-fluid" style="max-height: 30px;">
                    </div>
                    <div class="col-8">
                        <strong id="preview-bri-number">208319382834</strong><br>
                        <small id="preview-bri-name">ANGGA DWY SAPUTRA</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-mobile-alt mr-2"></i>
                    E-Wallet
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <span class="badge badge-success">GoPay</span>
                    </div>
                    <div class="col-8">
                        <strong id="preview-gopay-number">0819-1191-9993</strong>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-4">
                        <span class="badge badge-primary">DANA</span>
                    </div>
                    <div class="col-8">
                        <strong id="preview-dana-number">0819-1191-9993</strong>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-4">
                        <span class="badge badge-info">OVO</span>
                    </div>
                    <div class="col-8">
                        <strong id="preview-ovo-number">0822-1121-9993</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Update preview when bank details change
    $('#setting_bank_bca_number').on('input', function() {
        $('#preview-bca-number').text($(this).val());
    });
    
    $('#setting_bank_bca_name').on('input', function() {
        $('#preview-bca-name').text($(this).val());
    });
    
    $('#setting_bank_bri_number').on('input', function() {
        $('#preview-bri-number').text($(this).val());
    });
    
    $('#setting_bank_bri_name').on('input', function() {
        $('#preview-bri-name').text($(this).val());
    });
    
    $('#setting_gopay_number').on('input', function() {
        $('#preview-gopay-number').text($(this).val());
    });
    
    $('#setting_dana_number').on('input', function() {
        $('#preview-dana-number').text($(this).val());
    });
    
    $('#setting_ovo_number').on('input', function() {
        $('#preview-ovo-number').text($(this).val());
    });
    
    // Format phone numbers
    $('input[id*="number"]:not([id*="bank"])').on('input', function() {
        let value = $(this).val().replace(/[^0-9]/g, '');
        if (value.startsWith('62')) {
            value = '0' + value.substring(2);
        }
        $(this).val(value);
    });
    
    // Validate pricing
    $('input[id*="price"]').on('input', function() {
        const value = parseInt($(this).val());
        if (value < 0) {
            $(this).val(0);
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
