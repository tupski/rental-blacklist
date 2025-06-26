@extends('layouts.main')

@section('title', 'Syarat dan Ketentuan')

@section('meta_description', 'Syarat dan ketentuan penggunaan layanan CekPenyewa.com - Sistem Blacklist Rental Indonesia')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-primary mb-3">
                    <i class="fas fa-file-contract me-3"></i>
                    Syarat dan Ketentuan
                </h1>
                <p class="lead text-muted">
                    Syarat dan ketentuan penggunaan layanan {{ config('app.name', 'CekPenyewa.com') }}
                </p>
                <hr class="w-25 mx-auto">
            </div>

            <!-- Content -->
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    <div class="content-area">
                        {!! $content !!}
                    </div>
                </div>
                <div class="card-footer bg-light text-center">
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i>
                        Terakhir diperbarui: {{ now()->format('d F Y') }}
                    </small>
                </div>
            </div>

            <!-- Navigation -->
            <div class="text-center mt-4">
                <a href="{{ route('beranda') }}" class="btn btn-outline-primary me-3">
                    <i class="fas fa-home me-2"></i>
                    Kembali ke Beranda
                </a>
                <a href="{{ route('kebijakan-privasi') }}" class="btn btn-outline-success">
                    <i class="fas fa-shield-alt me-2"></i>
                    Kebijakan Privasi
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.content-area {
    line-height: 1.8;
    font-size: 1.1rem;
}

.content-area h1,
.content-area h2,
.content-area h3,
.content-area h4,
.content-area h5,
.content-area h6 {
    color: #2c3e50;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.content-area h1 {
    border-bottom: 3px solid #3498db;
    padding-bottom: 0.5rem;
}

.content-area h2 {
    border-bottom: 2px solid #e74c3c;
    padding-bottom: 0.3rem;
}

.content-area p {
    margin-bottom: 1.2rem;
    text-align: justify;
}

.content-area ul,
.content-area ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.content-area li {
    margin-bottom: 0.5rem;
}

.content-area blockquote {
    border-left: 4px solid #3498db;
    background-color: #f8f9fa;
    padding: 1rem 1.5rem;
    margin: 1.5rem 0;
    font-style: italic;
}

.content-area table {
    width: 100%;
    margin: 1.5rem 0;
    border-collapse: collapse;
}

.content-area table th,
.content-area table td {
    border: 1px solid #dee2e6;
    padding: 0.75rem;
    text-align: left;
}

.content-area table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.content-area strong {
    color: #2c3e50;
}

.content-area a {
    color: #3498db;
    text-decoration: none;
}

.content-area a:hover {
    color: #2980b9;
    text-decoration: underline;
}
</style>
@endsection
