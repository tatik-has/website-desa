@extends('presentation_tier.layout')

@section('content')
<link rel="stylesheet" href="{{ asset('presentation_tier/css/shared/style.css') }}">

<div class="login-container">
    <h2 class="login-title">Login</h2>

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
        <div style="text-align:center;">
            <button class="login-button" type="submit">Login</button>
        </div>
    </form>

    {{-- Link daftar di kanan bawah --}}
    <p class="login-footer">
        Belum punya akun?
        <a href="{{ url('/register') }}">Daftar</a>
    </p>
</div>
@endsection