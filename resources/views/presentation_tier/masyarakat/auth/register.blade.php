@extends('presentation_tier.masyarakat.layout')

@section('content')
<link rel="stylesheet" href="{{ asset('presentation_tier/css/shared/style.css') }}">

{{-- Pembungkus ini penting agar form bisa melebar dan ke tengah --}}
<div style="display: flex; justify-content: center; align-items: flex-start; min-height: 80vh; width: 100%; padding: 20px 0;">
    
    <div class="register-container">
        <h2 class="register-title">Daftar Akun Baru</h2>

        <form method="POST" action="{{ url('/register') }}">
            @csrf
            
            @if(session('error'))
                <div style="color: red; background: #ffe0e0; border: 1px solid red; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div style="color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    <strong>Terjadi Kesalahan:</strong>
                    <ul style="margin-top: 10px; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Input Nama --}}
            <input type="text" name="name" placeholder="Nama Lengkap" required class="register-input">

            {{-- NIK --}}
            <input type="text" name="nik" placeholder="Nomor NIK (16 Digit)" required maxlength="16" minlength="16" class="register-input">

            {{-- Nama Desa --}}
            <input type="text" name="desa" placeholder="Nama Desa" required class="register-input">

            {{-- Alamat --}}
            <textarea name="alamat" placeholder="Alamat Lengkap" required class="register-textarea"></textarea>

            {{-- Email --}}
            <input type="email" name="email" placeholder="Email" required class="register-input">

            {{-- Password --}}
            <input type="password" name="password" placeholder="Password" required class="register-input">

            {{-- Tombol --}}
            <div style="text-align:center;">
                <button class="register-button" type="submit">Daftar Sekarang</button>
            </div>
        </form>

        <p class="register-footer" style="text-align: center; margin-top: 20px;">
            Sudah punya akun?
            <a href="{{ url('/login') }}" style="color: #007BFF; font-weight: bold; text-decoration: none;">Login</a>
        </p>
    </div>

</div>
@endsection