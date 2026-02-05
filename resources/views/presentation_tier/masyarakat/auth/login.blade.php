@extends('presentation_tier.layout')

@section('content')
<link rel="stylesheet" href="{{ asset('presentation_tier/css/shared/style.css') }}">

<div class="main-wrapper">
    {{-- Sisi Kiri: Informasi Sistem --}}
    <div class="info-section">
        <div class="info-content">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Desa" class="info-logo">
            <h2>Sistem Informasi Surat Menyurat</h2>
            <p>Memudahkan administrasi persuratan desa secara digital, cepat, dan transparan.</p>
            <ul class="feature-list">
                <li>Pengajuan Surat Online</li>
                <li>Tracking Status Surat</li>
                <li>Arsip Digital Aman</li>
            </ul>
        </div>
    </div>

    {{-- Sisi Kanan: Form Login --}}
    <div class="login-section">
        <div class="login-box">
            <div class="login-welcome">
                <h1>Selamat Datang</h1>
                <p>Silakan login untuk melanjutkan</p>
            </div>

            @if(session('error'))
                <div class="login-message-error">{{ session('error') }}</div>
            @endif
            
            <form method="POST" action="{{ url('/login') }}">
                @csrf
                <input type="email" name="email" placeholder="Email" required class="login-input">
                <input type="password" name="password" placeholder="Password" required class="login-input">
                <button class="login-button" type="submit">Masuk</button>
            </form>

            <p class="login-footer">
                Belum punya akun? <a href="{{ url('/register') }}">Daftar Sekarang</a>
            </p>
        </div>
    </div>
</div>
@endsection