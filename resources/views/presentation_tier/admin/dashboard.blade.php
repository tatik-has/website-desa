{{-- File: resources/views/presentation_tier/admin/dashboard.blade.php --}}
@extends('presentation_tier.admin.layout')

@section('content')
<div class="dashboard-content">
    <div class="dashboard-header">
        <h2>Selamat Datang, Admin!</h2>
        <p>Berikut adalah ringkasan data di sistem Anda.</p>
    </div>

    <div class="widget-container">
        <div class="widget-card">
            <h4>Permohonan Masuk</h4>
            {{-- Nanti Anda bisa isi dengan data dinamis, contoh: <p>{{ $jumlahPermohonanMasuk }}</p> --}}
            <p>12</p>
        </div>
        <div class="widget-card">
            <h4>Surat Disetujui</h4>
            <p>8</p>
        </div>
        <div class="widget-card">
            <h4>Total Laporan</h4>
            <p>4</p>
        </div>
    </div>
</div>
@endsection