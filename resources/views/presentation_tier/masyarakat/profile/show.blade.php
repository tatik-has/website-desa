@extends('presentation_tier.masyarakat.layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('presentation_tier/css/masyarakat/profile.css') }}">
@endpush

@section('content')
<div class="profile-container">
    <div class="profile-card">
        <h2 class="profile-title">Profil Saya</h2>

        {{-- Informasi User --}}
        <div class="profile-section">
            <h3 class="section-title">Informasi Akun</h3>
            <div class="info-group">
                <label>Nama Lengkap</label>
                <div class="info-value">{{ $user->name }}</div>
            </div>
            <div class="info-group">
                <label>NIK</label>
                <div class="info-value">{{ $user->nik }}</div>
            </div>
            <div class="info-group">
                <label>Email</label>
                <div class="info-value">{{ $user->email }}</div>
            </div>
            <div class="info-group">
                <label>Desa</label>
                <div class="info-value">{{ $user->desa }}</div>
            </div>
            <div class="info-group">
                <label>Alamat</label>
                <div class="info-value">{{ $user->alamat }}</div>
            </div>
            <div class="info-group">
                <label>Status Verifikasi</label>
                <div class="info-value">
                    @if($user->is_verified)
                        <span class="badge badge-success">
                            <i class="fa fa-check-circle"></i> Terverifikasi
                        </span>
                    @else
                        <span class="badge badge-warning">
                            <i class="fa fa-clock"></i> Belum Terverifikasi
                        </span>
                    @endif
                </div>
            </div>
            <div class="info-group">
                <label>Terdaftar Sejak</label>
                <div class="info-value">
                    {{ $user->created_at ? $user->created_at->format('d F Y') : '-' }}
                </div>
            </div>
        </div>

        {{-- Informasi Keamanan --}}
        <div class="profile-section">
            <h3 class="section-title">Keamanan</h3>
            <div class="info-notice">
                <i class="fa fa-info-circle"></i>
                <p>Untuk mengubah password atau informasi akun, silakan hubungi admin desa.</p>
            </div>
            <div class="info-group">
                <label>Password</label>
                <div class="info-value">••••••••</div>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="form-actions">
            <a href="{{ route('dashboard') }}" class="btn-cancel">
                <i class="fa fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection