@extends('layouts.main')

@section('title', 'Profile')

@section('content')
<div class="bg-light min-vh-100">
    <div class="container py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <h1 class="display-6 fw-bold text-dark mb-2">
                                    <i class="fas fa-user-edit text-primary me-3"></i>
                                    Profile
                                </h1>
                                <p class="text-muted mb-1">
                                    Kelola informasi profil dan keamanan akun Anda
                                </p>
                                <p class="text-muted small">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ \App\Helpers\DateHelper::formatIndonesian(now(), 'l, d F Y') }}
                                </p>
                            </div>
                            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                                <a href="{{ route('dasbor') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Kembali ke Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Alerts -->
        @if(!$canEdit)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="alert alert-warning border-0 shadow-sm">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle text-warning me-3 fs-4"></i>
                            <div>
                                <h6 class="alert-heading mb-1">Profil Tidak Dapat Diedit</h6>
                                <p class="mb-0">
                                    @if(!Auth::user()->isActive())
                                        Akun Anda belum aktif. Menunggu persetujuan admin untuk dapat mengedit profil.
                                    @elseif($requiresEmailVerification)
                                        Email belum diverifikasi. Silakan verifikasi email terlebih dahulu untuk dapat mengedit profil.
                                        <a href="{{ route('verifikasi.pemberitahuan') }}" class="alert-link">Verifikasi sekarang</a>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Profile Forms -->
        @if(Auth::user()->role === 'pengusaha_rental')
            @include('profile.partials.rental-owner-profile')
        @else
            <div class="row g-4">
                <!-- Update Profile Information -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light border-0">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-user text-primary me-2"></i>
                                Informasi Profile
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
                        <div class="card-header bg-light border-0">
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

                <!-- Delete Account -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm border-danger">
                        <div class="card-header bg-danger bg-opacity-10 border-0">
                            <h5 class="card-title mb-0 text-danger">
                                <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                                Zona Berbahaya
                            </h5>
                        </div>
                        <div class="card-body">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
