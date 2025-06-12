@extends('layouts.main')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-1">
                        <i class="fas fa-cog me-2"></i>
                        Pengaturan Situs
                    </h1>
                    <p class="mb-0 opacity-75">Kelola pengaturan tampilan, SEO, dan konfigurasi situs</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card-body">
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        @foreach($settings as $group => $groupSettings)
                            <div class="mb-5">
                                <h4 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                                    @switch($group)
                                        @case('general')
                                            <i class="fas fa-globe me-2 text-primary"></i>
                                            Pengaturan Umum
                                            @break
                                        @case('seo')
                                            <i class="fas fa-search me-2 text-success"></i>
                                            SEO & Meta Tags
                                            @break
                                        @case('appearance')
                                            <i class="fas fa-palette me-2 text-info"></i>
                                            Tampilan
                                            @break
                                        @case('social')
                                            <i class="fas fa-share-alt me-2 text-warning"></i>
                                            Media Sosial
                                            @break
                                        @default
                                            {{ ucfirst($group) }}
                                    @endswitch
                                </h4>

                                <div class="row g-3">
                                    @foreach($groupSettings as $setting)
                                        <div class="col-md-6">
                                            <label for="setting_{{ $setting->key }}" class="form-label fw-medium">
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

                                                @case('color')
                                                    <div class="input-group">
                                                        <input
                                                            type="color"
                                                            name="settings[{{ $setting->key }}]"
                                                            id="setting_{{ $setting->key }}"
                                                            value="{{ $setting->value }}"
                                                            class="form-control form-control-color"
                                                        >
                                                        <input
                                                            type="text"
                                                            value="{{ $setting->value }}"
                                                            class="form-control"
                                                            readonly
                                                        >
                                                    </div>
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
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <div class="d-flex justify-content-between pt-4 border-top">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Update color input text when color picker changes
document.querySelectorAll('input[type="color"]').forEach(function(colorInput) {
    const textInput = colorInput.nextElementSibling;
    colorInput.addEventListener('change', function() {
        textInput.value = this.value;
    });
});
</script>
@endpush
@endsection
