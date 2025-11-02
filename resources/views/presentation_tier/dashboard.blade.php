{{-- File: resources/views/presentation_tier/dashboard.blade.php --}}
@extends('presentation_tier.masyarakat.layout')

@section('content')
    {{-- Perhatikan: Bagian <link> CSS dan <nav class="navbar"> sudah tidak ada di sini --}}
    {{-- Semuanya sudah diwariskan dari file layout.blade.php --}}
    
    <main class="hero-container">
        <div class="hero-content">
            <div class="hero-text">
                <p class="hero-subtitle">Administrasi Surat-Menyurat</p>
                <h1 class="hero-title">Desa Pakning Asal</h1>
            </div>
            <div class="hero-action">
                <a href="{{ url('/pengajuan') }}" class="hero-button">Pengajuan</a>
            </div>
        </div>
        <p class="hero-tagline">Mempermudah Setiap Proses, Mempercepat Setiap Langkah.</p>
    </main>
@endsection