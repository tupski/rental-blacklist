@extends('layouts.admin')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Notifikasi</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Notifikasi</h3>
                <div class="card-tools">
                    <form action="{{ route('admin.notifications.mark-read') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-secondary btn-sm">
                            <i class="fas fa-check-double"></i> Tandai Semua Dibaca
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                @forelse($notifications as $notification)
                <div class="alert {{ $notification->read_at ? 'alert-light' : 'alert-info' }} alert-dismissible">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="alert-heading">
                                <i class="{{ getNotificationIcon($notification->type) }} mr-2"></i>
                                {{ getNotificationTitle($notification->type) }}
                                @if(!$notification->read_at)
                                    <span class="badge badge-primary ml-2">Baru</span>
                                @endif
                            </h6>
                            <p class="mb-1">{{ $notification->data['message'] ?? 'Notifikasi baru' }}</p>
                            <small class="text-muted">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $notification->created_at->diffForHumans() }}
                            </small>
                        </div>
                        <div class="ml-3">
                            @if(!$notification->read_at)
                            <form action="{{ route('admin.notifications.mark-read') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                <button type="submit" class="btn btn-sm btn-outline-primary" title="Tandai Dibaca">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    
                    @if(isset($notification->data['details']))
                    <hr>
                    <div class="notification-details">
                        @foreach($notification->data['details'] as $key => $value)
                        <small><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</small><br>
                        @endforeach
                    </div>
                    @endif
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada notifikasi</h5>
                    <p class="text-muted">Semua notifikasi akan muncul di sini</p>
                </div>
                @endforelse
            </div>
            
            @if($notifications->hasPages())
            <div class="card-footer">
                {{ $notifications->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Statistics -->
<div class="row">
    <div class="col-lg-6 col-12">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ auth()->user()->unreadNotifications()->count() }}</h3>
                <p>Notifikasi Belum Dibaca</p>
            </div>
            <div class="icon">
                <i class="fas fa-bell"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-12">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ auth()->user()->readNotifications()->count() }}</h3>
                <p>Notifikasi Sudah Dibaca</p>
            </div>
            <div class="icon">
                <i class="fas fa-check"></i>
            </div>
        </div>
    </div>
</div>
@endsection

@php
function getNotificationIcon($type) {
    switch($type) {
        case 'App\\Notifications\\UserRegisteredNotification':
            return 'fas fa-user-plus text-success';
        case 'App\\Notifications\\TopupRequestNotification':
            return 'fas fa-credit-card text-info';
        case 'App\\Notifications\\BlacklistReportNotification':
            return 'fas fa-ban text-danger';
        default:
            return 'fas fa-bell text-primary';
    }
}

function getNotificationTitle($type) {
    switch($type) {
        case 'App\\Notifications\\UserRegisteredNotification':
            return 'User Baru Terdaftar';
        case 'App\\Notifications\\TopupRequestNotification':
            return 'Permintaan Topup';
        case 'App\\Notifications\\BlacklistReportNotification':
            return 'Laporan Blacklist Baru';
        default:
            return 'Notifikasi';
    }
}
@endphp
