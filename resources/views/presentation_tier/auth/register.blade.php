@extends('presentation_tier.layout')

@section('content')
<link rel="stylesheet" href="{{ asset('presentation_tier/css/style.css') }}">

<div class="register-container">
    <h2 class="register-title">Register</h2>

    <form method="POST" action="{{ url('/register') }}">
        @csrf

        {{-- Nama Lengkap --}}
        <div>
            <input type="text" name="name" placeholder="Nama Lengkap" required class="register-input">
        </div>

        {{-- NIK --}}
        <div>
            <input type="text" name="nik" placeholder="Nomor NIK (16 Digit)" required maxlength="16" minlength="16" class="register-input">
        </div>

        {{-- Nama Desa --}}
        <div>
            <input type="text" name="desa" placeholder="Nama Desa" required class="register-input">
        </div>

        {{-- Alamat --}}
        <div>
            <textarea name="alamat" placeholder="Alamat Lengkap" required class="register-textarea"></textarea>
        </div>

        {{-- Email --}}
        <div>
            <input type="email" name="email" placeholder="Email" required class="register-input">
        </div>

        {{-- Password --}}
        <div>
            <input type="password" name="password" placeholder="Password" required class="register-input">
        </div>

        {{-- Tombol Daftar --}}
        <div style="text-align:center;">
            <button class="register-button" type="submit">Daftar</button>
        </div>
    </form>

    <p class="register-footer">
        Sudah punya akun?
        <a href="{{ url('/login') }}">Login</a>
    </p>
</div>
@endsection
