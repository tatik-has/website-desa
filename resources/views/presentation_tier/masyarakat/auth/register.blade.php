@extends('presentation_tier.masyarakat.layout')

@section('content')
<link rel="stylesheet" href="{{ asset('presentation_tier/css/shared/style.css') }}">

<div class="register-container">
    <h2 class="register-title">Register</h2>

    <form method="POST" action="{{ url('/register') }}">
        @csrf
        {{-- Untuk error umum (spt gagal kirim email) --}}
        @if(session('error'))
            <div style="color: red; background: #ffe0e0; border: 1px solid red; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
                {{ session('error') }}
            </div>
        @endif

        {{-- Untuk error validasi (NIK/Email sudah ada, dll) --}}
        @if ($errors->any())
            <div style="color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                <strong>Oops! Terjadi kesalahan:</strong>
                <ul style="margin-top: 10px; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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
