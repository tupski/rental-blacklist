@extends('admin.layouts.app')

@section('title', 'Tambah Footer Widget')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus me-2"></i>
            Tambah Footer Widget
        </h1>
        <a href="{{ route('admin.footer-widgets.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-edit me-2"></i>
                        Form Tambah Widget
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.footer-widgets.store') }}" method="POST" id="widgetForm">
                        @csrf

                        <!-- Basic Info -->
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Judul Widget <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="order" class="form-label">Urutan <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror"
                                           id="order" name="order" value="{{ old('order', 0) }}" min="0" required>
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Tipe Widget <span class="text-danger">*</span></label>
                                    <select class="form-select @error('type') is-invalid @enderror"
                                            id="type" name="type" required>
                                        <option value="">Pilih Tipe Widget</option>
                                        @foreach($types as $key => $label)
                                            <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="css_class" class="form-label">CSS Class (Opsional)</label>
                                    <input type="text" class="form-control @error('css_class') is-invalid @enderror"
                                           id="css_class" name="css_class" value="{{ old('css_class') }}"
                                           placeholder="custom-widget-class">
                                    @error('css_class')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                       value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Widget Aktif
                                </label>
                            </div>
                        </div>

                        <!-- Dynamic Content Based on Type -->
                        <div id="content-sections">
                            <!-- Text Content -->
                            <div id="text-content" class="content-section" style="display: none;">
                                <div class="mb-3">
                                    <label for="content" class="form-label">Konten</label>
                                    <textarea class="form-control @error('content') is-invalid @enderror"
                                              id="content" name="content" rows="5">{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Links Content -->
                            <div id="links-content" class="content-section" style="display: none;">
                                <label class="form-label">Daftar Link</label>
                                <div id="links-container">
                                    @if(old('links'))
                                        @foreach(old('links') as $index => $link)
                                            <div class="link-item mb-2">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control"
                                                               name="links[{{ $index }}][text]"
                                                               placeholder="Teks Link"
                                                               value="{{ $link['text'] ?? '' }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="url" class="form-control"
                                                               name="links[{{ $index }}][url]"
                                                               placeholder="https://example.com"
                                                               value="{{ $link['url'] ?? '' }}">
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button type="button" class="btn btn-danger btn-sm remove-link">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="link-item mb-2">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control"
                                                           name="links[0][text]" placeholder="Teks Link">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="url" class="form-control"
                                                           name="links[0][url]" placeholder="https://example.com">
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button" class="btn btn-danger btn-sm remove-link">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="add-link">
                                    <i class="fas fa-plus me-1"></i> Tambah Link
                                </button>
                            </div>

                            <!-- Contact Content -->
                            <div id="contact-content" class="content-section" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="contact_address" class="form-label">Alamat</label>
                                            <textarea class="form-control" id="contact_address"
                                                      name="contact_address" rows="3">{{ old('contact_address') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="contact_phone" class="form-label">Telepon</label>
                                            <input type="text" class="form-control" id="contact_phone"
                                                   name="contact_phone" value="{{ old('contact_phone') }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="contact_email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="contact_email"
                                                   name="contact_email" value="{{ old('contact_email') }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="contact_whatsapp" class="form-label">WhatsApp</label>
                                            <input type="text" class="form-control" id="contact_whatsapp"
                                                   name="contact_whatsapp" value="{{ old('contact_whatsapp') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Social Content -->
                            <div id="social-content" class="content-section" style="display: none;">
                                <label class="form-label">Media Sosial</label>
                                <div id="social-container">
                                    @if(old('social'))
                                        @foreach(old('social') as $index => $social)
                                            <div class="social-item mb-2">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <select class="form-select" name="social[{{ $index }}][platform]">
                                                            <option value="">Pilih Platform</option>
                                                            <option value="facebook" {{ ($social['platform'] ?? '') == 'facebook' ? 'selected' : '' }}>Facebook</option>
                                                            <option value="twitter" {{ ($social['platform'] ?? '') == 'twitter' ? 'selected' : '' }}>Twitter</option>
                                                            <option value="instagram" {{ ($social['platform'] ?? '') == 'instagram' ? 'selected' : '' }}>Instagram</option>
                                                            <option value="linkedin" {{ ($social['platform'] ?? '') == 'linkedin' ? 'selected' : '' }}>LinkedIn</option>
                                                            <option value="youtube" {{ ($social['platform'] ?? '') == 'youtube' ? 'selected' : '' }}>YouTube</option>
                                                            <option value="whatsapp" {{ ($social['platform'] ?? '') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                                                            <option value="telegram" {{ ($social['platform'] ?? '') == 'telegram' ? 'selected' : '' }}>Telegram</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-7">
                                                        <input type="url" class="form-control"
                                                               name="social[{{ $index }}][url]"
                                                               placeholder="https://facebook.com/username"
                                                               value="{{ $social['url'] ?? '' }}">
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button type="button" class="btn btn-danger btn-sm remove-social">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="social-item mb-2">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <select class="form-select" name="social[0][platform]">
                                                        <option value="">Pilih Platform</option>
                                                        <option value="facebook">Facebook</option>
                                                        <option value="twitter">Twitter</option>
                                                        <option value="instagram">Instagram</option>
                                                        <option value="linkedin">LinkedIn</option>
                                                        <option value="youtube">YouTube</option>
                                                        <option value="whatsapp">WhatsApp</option>
                                                        <option value="telegram">Telegram</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="url" class="form-control"
                                                           name="social[0][url]"
                                                           placeholder="https://facebook.com/username">
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button" class="btn btn-danger btn-sm remove-social">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="add-social">
                                    <i class="fas fa-plus me-1"></i> Tambah Media Sosial
                                </button>
                            </div>

                            <!-- Custom HTML Content -->
                            <div id="custom-content" class="content-section" style="display: none;">
                                <div class="mb-3">
                                    <label for="custom_content" class="form-label">HTML Kustom</label>
                                    <textarea class="form-control @error('content') is-invalid @enderror"
                                              id="custom_content" name="content" rows="8">{{ old('content') }}</textarea>
                                    <div class="form-text">Anda dapat menggunakan HTML dan CSS kustom di sini.</div>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.footer-widgets.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Widget
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>
                        Panduan
                    </h6>
                </div>
                <div class="card-body">
                    <div id="type-help">
                        <p class="text-muted">Pilih tipe widget untuk melihat panduan penggunaan.</p>
                    </div>

                    <div id="text-help" class="type-help" style="display: none;">
                        <h6>Teks Biasa</h6>
                        <p class="small">Widget untuk menampilkan teks biasa. Mendukung line break.</p>
                    </div>

                    <div id="links-help" class="type-help" style="display: none;">
                        <h6>Daftar Link</h6>
                        <p class="small">Widget untuk menampilkan daftar link dalam bentuk list. Tambahkan teks dan URL untuk setiap link.</p>
                    </div>

                    <div id="contact-help" class="type-help" style="display: none;">
                        <h6>Informasi Kontak</h6>
                        <p class="small">Widget untuk menampilkan informasi kontak dengan ikon. Isi field yang diperlukan saja.</p>
                    </div>

                    <div id="social-help" class="type-help" style="display: none;">
                        <h6>Media Sosial</h6>
                        <p class="small">Widget untuk menampilkan ikon media sosial. Pilih platform dan masukkan URL profil.</p>
                    </div>

                    <div id="custom-help" class="type-help" style="display: none;">
                        <h6>HTML Kustom</h6>
                        <p class="small">Widget untuk HTML kustom. Anda dapat menggunakan HTML dan CSS sesuai kebutuhan.</p>
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
    let linkIndex = {{ old('links') ? count(old('links')) : 1 }};
    let socialIndex = {{ old('social') ? count(old('social')) : 1 }};

    // Show/hide content sections based on type
    $('#type').change(function() {
        const selectedType = $(this).val();

        // Hide all content sections
        $('.content-section').hide();
        $('.type-help').hide();

        // Show relevant section
        if (selectedType) {
            $(`#${selectedType}-content`).show();
            $(`#${selectedType}-help`).show();
        } else {
            $('#type-help').show();
        }
    });

    // Trigger change on page load
    $('#type').trigger('change');

    // Add new link
    $('#add-link').click(function() {
        const linkHtml = `
            <div class="link-item mb-2">
                <div class="row">
                    <div class="col-md-5">
                        <input type="text" class="form-control"
                               name="links[${linkIndex}][text]" placeholder="Teks Link">
                    </div>
                    <div class="col-md-6">
                        <input type="url" class="form-control"
                               name="links[${linkIndex}][url]" placeholder="https://example.com">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm remove-link">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#links-container').append(linkHtml);
        linkIndex++;
    });

    // Remove link
    $(document).on('click', '.remove-link', function() {
        if ($('.link-item').length > 1) {
            $(this).closest('.link-item').remove();
        } else {
            alert('Minimal harus ada satu link');
        }
    });

    // Add new social media
    $('#add-social').click(function() {
        const socialHtml = `
            <div class="social-item mb-2">
                <div class="row">
                    <div class="col-md-4">
                        <select class="form-select" name="social[${socialIndex}][platform]">
                            <option value="">Pilih Platform</option>
                            <option value="facebook">Facebook</option>
                            <option value="twitter">Twitter</option>
                            <option value="instagram">Instagram</option>
                            <option value="linkedin">LinkedIn</option>
                            <option value="youtube">YouTube</option>
                            <option value="whatsapp">WhatsApp</option>
                            <option value="telegram">Telegram</option>
                        </select>
                    </div>
                    <div class="col-md-7">
                        <input type="url" class="form-control"
                               name="social[${socialIndex}][url]"
                               placeholder="https://facebook.com/username">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm remove-social">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#social-container').append(socialHtml);
        socialIndex++;
    });

    // Remove social media
    $(document).on('click', '.remove-social', function() {
        if ($('.social-item').length > 1) {
            $(this).closest('.social-item').remove();
        } else {
            alert('Minimal harus ada satu media sosial');
        }
    });

    // Form validation
    $('#widgetForm').submit(function(e) {
        const type = $('#type').val();

        if (type === 'links') {
            let hasValidLink = false;
            $('.link-item').each(function() {
                const text = $(this).find('input[name*="[text]"]').val();
                const url = $(this).find('input[name*="[url]"]').val();
                if (text && url) {
                    hasValidLink = true;
                    return false;
                }
            });

            if (!hasValidLink) {
                e.preventDefault();
                alert('Minimal harus ada satu link yang valid (teks dan URL terisi)');
                return false;
            }
        }

        if (type === 'social') {
            let hasValidSocial = false;
            $('.social-item').each(function() {
                const platform = $(this).find('select[name*="[platform]"]').val();
                const url = $(this).find('input[name*="[url]"]').val();
                if (platform && url) {
                    hasValidSocial = true;
                    return false;
                }
            });

            if (!hasValidSocial) {
                e.preventDefault();
                alert('Minimal harus ada satu media sosial yang valid (platform dan URL terisi)');
                return false;
            }
        }
    });
});
</script>
@endpush
