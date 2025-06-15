@auth
    @if(Auth::user()->isPending())
        @php
            $wa1 = App\Models\Setting::get('admin_contact_wa1', '0819-1191-9993');
            $wa2 = App\Models\Setting::get('admin_contact_wa2', '0822-1121-9993');
            
            // Create WhatsApp message
            $message = "Halo Admin CekPenyewa.com,\n\n";
            $message .= "Saya ingin menanyakan status aktivasi akun saya:\n\n";
            $message .= "Nama: " . Auth::user()->name . "\n";
            $message .= "Email: " . Auth::user()->email . "\n";
            $message .= "Role: " . (Auth::user()->role === 'pengusaha_rental' ? 'Pemilik Rental' : 'User Umum') . "\n";
            $message .= "Tanggal Daftar: " . Auth::user()->created_at->format('d/m/Y H:i') . "\n\n";
            $message .= "Mohon informasi kapan akun saya akan diaktifkan.\n\n";
            $message .= "Terima kasih.";
            
            $encodedMessage = urlencode($message);
            $wa1Link = "https://wa.me/{$wa1}?text={$encodedMessage}";
            $wa2Link = "https://wa.me/{$wa2}?text={$encodedMessage}";
        @endphp
        
        <div class="alert alert-warning alert-dismissible sticky-top shadow-sm border-0" 
             style="position: sticky; top: 0; z-index: 1050; margin-bottom: 0; border-radius: 0;">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h6 class="alert-heading mb-1">
                            <i class="fas fa-clock text-warning me-2"></i>
                            <strong>Akun Menunggu Persetujuan</strong>
                        </h6>
                        <p class="mb-0">
                            Akun Anda sedang ditinjau oleh admin dan akan diaktifkan dalam <strong>1x24 jam</strong>. 
                            Saat ini Anda tidak dapat mengakses fitur pencarian dan data akan ditampilkan dalam bentuk sensor.
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end mt-2 mt-md-0">
                        <small class="d-block text-muted mb-2">Ada pertanyaan? Hubungi admin:</small>
                        <div class="btn-group" role="group">
                            <a href="{{ $wa1Link }}" target="_blank" class="btn btn-success btn-sm">
                                <i class="fab fa-whatsapp me-1"></i>
                                {{ $wa1 }}
                            </a>
                            <a href="{{ $wa2Link }}" target="_blank" class="btn btn-success btn-sm">
                                <i class="fab fa-whatsapp me-1"></i>
                                {{ $wa2 }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@endauth
