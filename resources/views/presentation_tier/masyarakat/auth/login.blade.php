@extends('presentation_tier.layout')

@section('content')
<link rel="stylesheet" href="{{ asset('presentation_tier/css/shared/style.css') }}">

<div class="login-container">
    {{-- Logo Desa --}}
    <div class="login-logo">
        {{-- Ganti 'logo-desa.png' dengan path logo desa Anda --}}
        <img src="{{ asset('images/logo.png') }}" alt="Logo Desa">
    </div>

    {{-- Teks Sambutan --}}
    <div class="login-welcome">
        <h1>Selamat Datang</h1>
        <p>Sistem Informasi Desa<br>Silakan login untuk melanjutkan</p>
    </div>

    {{-- Pesan error / sukses --}}
    @if(session('error'))
        <div class="login-message-error">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="login-message-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ url('/login') }}">
        @csrf

        {{-- Email --}}
        <div>
            <input type="email" name="email" placeholder="Email" required class="login-input">
        </div>

        {{-- Password --}}
        <div>
            <input type="password" name="password" placeholder="Password" required class="login-input">
        </div>

        {{-- Tombol Login --}}
        <div>
            <button class="login-button" type="submit">Masuk</button>
        </div>
    </form>

    {{-- Link daftar --}}
    <p class="login-footer">
        Belum punya akun?
        <a href="{{ url('/register') }}">Daftar Sekarang</a>
    </p>
</div>
@endsection