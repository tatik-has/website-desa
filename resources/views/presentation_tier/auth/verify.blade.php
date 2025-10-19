@extends('presentation_tier.layout')

@section('content')
<link rel="stylesheet" href="{{ asset('presentation_tier/css/style.css') }}">

<div class="verify-container">
    <h2 class="verify-title">Verifikasi Akun</h2>

    {{-- Pesan sukses / error --}}
    @if(session('error'))
        <div class="verify-message-error">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="verify-message-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('verify.code') }}">
        @csrf

        {{-- Kode Verifikasi --}}
        <div>
            <input type="text" name="code" placeholder="Kode Verifikasi" required class="verify-input">
        </div>

        {{-- Tombol Verifikasi --}}
        <div style="text-align:center;">
            <button class="verify-button" type="submit">Verifikasi</button>
        </div>
    </form>

    <p class="verify-footer">
        Belum menerima kode? 
        <a href="{{ url('/resend-code') }}">Kirim Ulang</a>
    </p>
</div>
@endsection
