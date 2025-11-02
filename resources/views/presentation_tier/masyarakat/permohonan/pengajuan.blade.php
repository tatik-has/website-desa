@extends('presentation_tier.masyarakat.layout')

@push('styles')
    {{-- Memuat CSS khusus untuk halaman ini saja --}}
    <link rel="stylesheet" href="{{ asset('presentation_tier/css/masyarakat/pengajuan.css') }}">
@endpush

@section('content')
    {{-- Navbar dan link CSS umum sudah tidak ada di sini, karena diwarisi dari layout --}}
    <main class="pengajuan-container">
        <h1 class="pengajuan-title">Surat Apa Yang Ingin Anda Ajukan?</h1>

        <div class="card-container">

            <a href="{{ route('sktm.create', ['jenis' => 'usaha']) }}" class="surat-card">
                <img src="{{ asset('images/icon.png') }}" alt="Ikon Surat">
                <span>Surat Keterangan<br>Tidak Mampu</span>
            </a>

            <a href="{{ route('pengajuan.domisili.form') }}" class="surat-card">
                <img src="{{ asset('images/icon.png') }}" alt="Ikon Surat">
                <span>Surat Keterangan<br>Domisili</span>
            </a>

            <a href="{{ route('sku.create', ['jenis' => 'usaha']) }}" class="surat-card">
                <img src="{{ asset('images/icon.png') }}" alt="Ikon Surat">
                <span>Surat Keterangan<br>Usaha</span>
            </a>
        </div>
    </main>
@endsection