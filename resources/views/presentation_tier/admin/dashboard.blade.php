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
            {{-- Data dinamis untuk permohonan berstatus 'Diproses' --}}
            <p>{{ $totalDiproses }}</p>
        </div>
        <div class="widget-card">
            <h4>Surat Disetujui</h4>
            {{-- Data dinamis untuk permohonan berstatus 'Selesai' --}}
            <p>{{ $totalSelesai }}</p>
        </div>
        <div class="widget-card">
            <h4>Total Pengajuan Surat</h4>
            {{-- Data dinamis untuk permohonan berstatus 'Ditolak' --}}
            <p>{{ $totalDitolak }}</p>
        </div>
    </div>
</div>
@endsection