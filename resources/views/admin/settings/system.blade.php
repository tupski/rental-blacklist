@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Sistem')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
    <li class="breadcrumb-item">Pengaturan</li>
    <li class="breadcrumb-item active">Sistem</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-server mr-2"></i>
                    Pengaturan Sistem
                </h3>
                <div class="card-tools">
                    <small class="text-muted">Zona Waktu, Format Tanggal, dan Pengaturan Sistem</small>
                </div>
            </div>
            <div class="card-body">
                <form id="systemSettingsForm" action="{{ route('admin.settings.system.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    @if(isset($settings) && $settings->count() > 0)
                        @foreach($settings as $group => $groupSettings)
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-cogs mr-2"></i>
                                        Konfigurasi Sistem
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

                                                        @case('select')
                                                            <select
                                                                name="settings[{{ $setting->key }}]"
                                                                id="setting_{{ $setting->key }}"
                                                                class="form-control"
                                                            >
                                                                @if($setting->key === 'timezone')
                                                                    <option value="Asia/Jakarta" {{ $setting->value === 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (WIB)</option>
                                                                    <option value="Asia/Makassar" {{ $setting->value === 'Asia/Makassar' ? 'selected' : '' }}>Asia/Makassar (WITA)</option>
                                                                    <option value="Asia/Jayapura" {{ $setting->value === 'Asia/Jayapura' ? 'selected' : '' }}>Asia/Jayapura (WIT)</option>
                                                                @elseif($setting->key === 'date_format')
                                                                    <option value="d/m/Y" {{ $setting->value === 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                                                    <option value="Y-m-d" {{ $setting->value === 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                                                                    <option value="m/d/Y" {{ $setting->value === 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                                                                    <option value="d-m-Y" {{ $setting->value === 'd-m-Y' ? 'selected' : '' }}>DD-MM-YYYY</option>
                                                                @elseif($setting->key === 'time_format')
                                                                    <option value="H:i" {{ $setting->value === 'H:i' ? 'selected' : '' }}>24 Jam (HH:MM)</option>
                                                                    <option value="h:i A" {{ $setting->value === 'h:i A' ? 'selected' : '' }}>12 Jam (HH:MM AM/PM)</option>
                                                                @elseif($setting->key === 'currency_position')
                                                                    <option value="before" {{ $setting->value === 'before' ? 'selected' : '' }}>Sebelum Angka (Rp 1.000)</option>
                                                                    <option value="after" {{ $setting->value === 'after' ? 'selected' : '' }}>Setelah Angka (1.000 Rp)</option>
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
                            Belum ada pengaturan sistem yang tersedia. Silakan jalankan seeder untuk menambahkan pengaturan default.
                            <br><br>
                            <code>php artisan db:seed --class=SettingsSeeder</code>
                        </div>
                    @endif
                </form>
            </div>
            <div class="card-footer">
                <button type="submit" form="systemSettingsForm" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Pengaturan
                </button>
                <a href="{{ route('admin.dasbor') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <div class="float-right">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        Perubahan zona waktu akan mempengaruhi tampilan waktu di seluruh sistem
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Card -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-eye mr-2"></i>
                    Preview Format
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Tanggal Saat Ini:</strong></td>
                        <td id="preview-date">{{ now()->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Waktu Saat Ini:</strong></td>
                        <td id="preview-time">{{ now()->format('H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Format Mata Uang:</strong></td>
                        <td id="preview-currency">Rp 1.000</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-clock mr-2"></i>
                    Informasi Zona Waktu
                </h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Zona Waktu Server:</strong></td>
                        <td>{{ config('app.timezone') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Waktu Server:</strong></td>
                        <td>{{ now()->format('Y-m-d H:i:s T') }}</td>
                    </tr>
                    <tr>
                        <td><strong>UTC Offset:</strong></td>
                        <td>{{ now()->format('P') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Update preview when format changes
    $('#setting_date_format').on('change', function() {
        updateDatePreview();
    });
    
    $('#setting_time_format').on('change', function() {
        updateTimePreview();
    });
    
    $('#setting_currency_symbol, #setting_currency_position').on('change', function() {
        updateCurrencyPreview();
    });
    
    function updateDatePreview() {
        const format = $('#setting_date_format').val();
        const now = new Date();
        let preview = '';
        
        switch(format) {
            case 'd/m/Y':
                preview = now.getDate().toString().padStart(2, '0') + '/' + 
                         (now.getMonth() + 1).toString().padStart(2, '0') + '/' + 
                         now.getFullYear();
                break;
            case 'Y-m-d':
                preview = now.getFullYear() + '-' + 
                         (now.getMonth() + 1).toString().padStart(2, '0') + '-' + 
                         now.getDate().toString().padStart(2, '0');
                break;
            case 'm/d/Y':
                preview = (now.getMonth() + 1).toString().padStart(2, '0') + '/' + 
                         now.getDate().toString().padStart(2, '0') + '/' + 
                         now.getFullYear();
                break;
            case 'd-m-Y':
                preview = now.getDate().toString().padStart(2, '0') + '-' + 
                         (now.getMonth() + 1).toString().padStart(2, '0') + '-' + 
                         now.getFullYear();
                break;
        }
        
        $('#preview-date').text(preview);
    }
    
    function updateTimePreview() {
        const format = $('#setting_time_format').val();
        const now = new Date();
        let preview = '';
        
        if (format === 'H:i') {
            preview = now.getHours().toString().padStart(2, '0') + ':' + 
                     now.getMinutes().toString().padStart(2, '0');
        } else {
            let hours = now.getHours();
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;
            preview = hours.toString().padStart(2, '0') + ':' + minutes + ' ' + ampm;
        }
        
        $('#preview-time').text(preview);
    }
    
    function updateCurrencyPreview() {
        const symbol = $('#setting_currency_symbol').val() || 'Rp';
        const position = $('#setting_currency_position').val() || 'before';
        
        let preview = '';
        if (position === 'before') {
            preview = symbol + ' 1.000';
        } else {
            preview = '1.000 ' + symbol;
        }
        
        $('#preview-currency').text(preview);
    }
});
</script>

<!-- Toastr for notifications -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@endpush
