@extends('presentation_tier.masyarakat.layout')

@section('content')
    <link rel="stylesheet" href="{{ asset('presentation_tier/css/shared/profile.css') }}">
<div class="profile-container">
    <div class="profile-card">
        <h2 class="profile-title">Profile Saya</h2>

        {{-- Notifikasi --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

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
        </div>

        {{-- Form Ubah Password --}}
        <div class="profile-section">
            <h3 class="section-title">Ubah Password</h3>
            <form method="POST" action="{{ route('profile.updatePassword') }}">
                @csrf
                <div class="form-group">
                    <label for="current_password">Password Lama <span class="required">*</span></label>
                    <input type="password" id="current_password" name="current_password"
                        class="form-input @error('current_password') error @enderror" required>
                    @error('current_password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password">Password Baru <span class="required">*</span></label>
                    <input type="password" id="new_password" name="new_password"
                        class="form-input @error('new_password') error @enderror" required>
                    <small class="form-help">Minimal 8 karakter</small>
                    @error('new_password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation">Konfirmasi Password Baru <span class="required">*</span></label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                        class="form-input" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fa fa-save"></i> Ubah Password
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn-cancel">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('presentation_tier/css/masyarakat/profile.css') }}">
@endpush
@endsection
