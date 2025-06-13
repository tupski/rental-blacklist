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
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        @if(isset($settings))
                            @foreach($settings as $group => $groupSettings)
                                <div class="mb-4">
                                    <h4 class="text-primary mb-3">
                                        @switch($group)
                                            @case('general')
                                                <i class="fas fa-globe mr-2"></i>
                                                Pengaturan Umum
                                                @break
                                            @case('seo')
                                                <i class="fas fa-search mr-2"></i>
                                                SEO & Meta Tags
                                                @break
                                            @case('appearance')
                                                <i class="fas fa-palette mr-2"></i>
                                                Tampilan
                                                @break
                                            @case('social')
                                                <i class="fas fa-share-alt mr-2"></i>
                                                Media Sosial
                                                @break
                                            @default
                                                {{ ucfirst($group) }}
                                        @endswitch
                                    </h4>

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

                                                        @case('number')
                                                            <input
                                                                type="number"
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
                            @endforeach
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                Belum ada pengaturan yang tersedia. Silakan hubungi administrator untuk menambahkan pengaturan sistem.
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
    // Add form ID for footer button
    $('form').attr('id', 'settingsForm');
});
</script>
@endpush
