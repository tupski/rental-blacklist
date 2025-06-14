@extends('layouts.admin')

@section('title', 'Profil Admin')
@section('page-title', 'Profil Admin')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Profil</li>
@endsection

@section('content')
<div class="row">
    <!-- Profile Information -->
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Informasi Profil</h3>
            </div>
            <div class="card-body">
                @include('admin.profile.partials.update-profile-information-form')
            </div>
        </div>
    </div>

    <!-- Change Password -->
    <div class="col-md-6">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Ubah Password</h3>
            </div>
            <div class="card-body">
                @include('admin.profile.partials.update-password-form')
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Admin Information -->
    <div class="col-md-6">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Informasi Admin</h3>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Role:</strong></td>
                        <td><span class="badge badge-success">{{ ucfirst($user->role) }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Email Verified:</strong></td>
                        <td>
                            @if($user->email_verified_at)
                                <span class="badge badge-success">
                                    <i class="fas fa-check"></i> Verified
                                </span>
                                <br><small class="text-muted">{{ $user->email_verified_at->format('d/m/Y H:i') }}</small>
                            @else
                                <span class="badge badge-warning">
                                    <i class="fas fa-clock"></i> Pending
                                </span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Last Login:</strong></td>
                        <td>{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Never' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Account Created:</strong></td>
                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Profile Updated:</strong></td>
                        <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- System Statistics -->
    <div class="col-md-6">
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Statistik Sistem</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-ban"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Blacklist</span>
                                <span class="info-box-number">{{ \App\Models\RentalBlacklist::count() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Users</span>
                                <span class="info-box-number">{{ \App\Models\User::where('role', '!=', 'admin')->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class="fas fa-flag"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Pending Reports</span>
                                <span class="info-box-number">{{ \App\Models\GuestReport::where('status', 'pending')->count() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-box bg-danger">
                            <span class="info-box-icon"><i class="fas fa-credit-card"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Pending Topups</span>
                                <span class="info-box-number">{{ \App\Models\TopupRequest::where('status', 'pending')->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Danger Zone -->
<div class="row">
    <div class="col-12">
        <div class="card card-danger">
            <div class="card-header">
                <h3 class="card-title">Zona Berbahaya</h3>
            </div>
            <div class="card-body">
                @include('admin.profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-hide success messages
    setTimeout(function() {
        $('.alert-success').fadeOut();
    }, 5000);
});
</script>
@endpush
