@php
    $rentalRegistration = Auth::user()->rentalRegistration;
@endphp

<div class="row g-4">
    <!-- Informasi Perusahaan Rental (Read Only) -->
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary bg-opacity-10 border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-building text-primary me-2"></i>
                    Informasi Perusahaan Rental
                    <span class="badge bg-secondary ms-2">Read Only</span>
                </h5>
            </div>
            <div class="card-body">
                @if($rentalRegistration)
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Rental</label>
                            <div class="form-control-plaintext border rounded p-2 bg-light">
                                {{ $rentalRegistration->nama_rental }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Jenis Rental</label>
                            <div class="form-control-plaintext border rounded p-2 bg-light">
                                @if(is_array($rentalRegistration->jenis_rental))
                                    @foreach($rentalRegistration->jenis_rental as $jenis)
                                        <span class="badge bg-primary me-1">{{ $jenis }}</span>
                                    @endforeach
                                @else
                                    {{ $rentalRegistration->jenis_rental }}
                                @endif
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Alamat Usaha</label>
                            <div class="form-control-plaintext border rounded p-2 bg-light">
                                {{ $rentalRegistration->alamat }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Kota</label>
                            <div class="form-control-plaintext border rounded p-2 bg-light">
                                {{ $rentalRegistration->kota }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Provinsi</label>
                            <div class="form-control-plaintext border rounded p-2 bg-light">
                                {{ $rentalRegistration->provinsi }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">No. HP Usaha</label>
                            <div class="form-control-plaintext border rounded p-2 bg-light">
                                {{ $rentalRegistration->no_hp }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email Usaha</label>
                            <div class="form-control-plaintext border rounded p-2 bg-light">
                                {{ $rentalRegistration->email }}
                            </div>
                        </div>
                        @if($rentalRegistration->website)
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Website</label>
                            <div class="form-control-plaintext border rounded p-2 bg-light">
                                <a href="{{ $rentalRegistration->website }}" target="_blank" class="text-decoration-none">
                                    {{ $rentalRegistration->website }}
                                    <i class="fas fa-external-link-alt ms-1"></i>
                                </a>
                            </div>
                        </div>
                        @endif
                        @if($rentalRegistration->deskripsi)
                        <div class="col-12">
                            <label class="form-label fw-bold">Deskripsi</label>
                            <div class="form-control-plaintext border rounded p-2 bg-light">
                                {{ $rentalRegistration->deskripsi }}
                            </div>
                        </div>
                        @endif
                        
                        <!-- Sosial Media -->
                        @if($rentalRegistration->sosial_media)
                        <div class="col-12">
                            <label class="form-label fw-bold">Sosial Media</label>
                            <div class="d-flex gap-3">
                                @if(isset($rentalRegistration->sosial_media['facebook']) && $rentalRegistration->sosial_media['facebook'])
                                    <a href="{{ $rentalRegistration->sosial_media['facebook'] }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                        <i class="fab fa-facebook me-1"></i>Facebook
                                    </a>
                                @endif
                                @if(isset($rentalRegistration->sosial_media['instagram']) && $rentalRegistration->sosial_media['instagram'])
                                    <a href="{{ $rentalRegistration->sosial_media['instagram'] }}" target="_blank" class="btn btn-outline-danger btn-sm">
                                        <i class="fab fa-instagram me-1"></i>Instagram
                                    </a>
                                @endif
                                @if(isset($rentalRegistration->sosial_media['whatsapp']) && $rentalRegistration->sosial_media['whatsapp'])
                                    <a href="https://wa.me/{{ $rentalRegistration->sosial_media['whatsapp'] }}" target="_blank" class="btn btn-outline-success btn-sm">
                                        <i class="fab fa-whatsapp me-1"></i>WhatsApp
                                    </a>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Dokumen Legalitas -->
                        @if($rentalRegistration->dokumen_legalitas && count($rentalRegistration->dokumen_legalitas) > 0)
                        <div class="col-12">
                            <label class="form-label fw-bold">Dokumen Legalitas</label>
                            <div class="mt-2">
                                <button type="button" class="btn btn-outline-warning" onclick="showPasswordModal()">
                                    <i class="fas fa-eye me-2"></i>Lihat Legalitas
                                    <i class="fas fa-lock ms-1"></i>
                                </button>
                                <small class="text-muted d-block mt-1">
                                    Masukkan password untuk melihat dokumen legalitas
                                </small>
                            </div>
                        </div>
                        @endif
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Data registrasi rental tidak ditemukan. Silakan hubungi administrator.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Informasi Penanggung Jawab (Editable) -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-success bg-opacity-10 border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-edit text-success me-2"></i>
                    Informasi Penanggung Jawab
                    <span class="badge bg-success ms-2">Editable</span>
                </h5>
            </div>
            <div class="card-body">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>
    </div>

    <!-- Update Password -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-warning bg-opacity-10 border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-lock text-warning me-2"></i>
                    Ubah Password
                </h5>
            </div>
            <div class="card-body">
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </div>
</div>

<!-- Password Modal for Legalitas -->
<div class="modal fade" id="passwordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-lock me-2"></i>Verifikasi Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">Masukkan password Anda untuk melihat dokumen legalitas:</p>
                <form id="passwordForm">
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" required>
                        <div class="invalid-feedback" id="passwordError"></div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-unlock me-2"></i>Verifikasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Legalitas Modal -->
<div class="modal fade" id="legalitasModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-file-alt me-2"></i>Dokumen Legalitas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="legalitasContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showPasswordModal() {
    $('#passwordModal').modal('show');
}

$('#passwordForm').on('submit', function(e) {
    e.preventDefault();
    
    const password = $('#password').val();
    const submitBtn = $(this).find('button[type="submit"]');
    const originalText = submitBtn.html();
    
    // Reset error state
    $('#password').removeClass('is-invalid');
    $('#passwordError').text('');
    
    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Memverifikasi...');
    
    $.ajax({
        url: '{{ route("profil.verify-password") }}',
        method: 'POST',
        data: {
            password: password,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                $('#passwordModal').modal('hide');
                showLegalitasModal();
                $('#password').val(''); // Clear password
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                $('#password').addClass('is-invalid');
                $('#passwordError').text('Password salah');
            } else {
                alert('Terjadi kesalahan saat memverifikasi password');
            }
        },
        complete: function() {
            submitBtn.prop('disabled', false).html(originalText);
        }
    });
});

function showLegalitasModal() {
    const legalitasFiles = @json($rentalRegistration ? $rentalRegistration->dokumen_legalitas : []);
    
    let content = '<div class="row g-3">';
    
    if (legalitasFiles && legalitasFiles.length > 0) {
        legalitasFiles.forEach(function(file, index) {
            const fileUrl = `/storage/${file}`;
            const fileName = file.split('/').pop();
            const isImage = /\.(jpg|jpeg|png|gif)$/i.test(fileName);
            
            content += `
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            ${isImage ? 
                                `<img src="${fileUrl}" class="img-fluid rounded mb-2" style="max-height: 200px;">` :
                                `<i class="fas fa-file-pdf fa-4x text-danger mb-2"></i>`
                            }
                            <h6 class="card-title">${fileName}</h6>
                            <a href="${fileUrl}" target="_blank" class="btn btn-primary btn-sm">
                                <i class="fas fa-download me-1"></i>Download
                            </a>
                        </div>
                    </div>
                </div>
            `;
        });
    } else {
        content += '<div class="col-12"><p class="text-muted text-center">Tidak ada dokumen legalitas</p></div>';
    }
    
    content += '</div>';
    
    $('#legalitasContent').html(content);
    $('#legalitasModal').modal('show');
}
</script>
@endpush
