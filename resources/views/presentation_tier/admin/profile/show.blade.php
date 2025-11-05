@extends('presentation_tier.admin.partials.layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('presentation_tier/css/admin/admin-profile.css') }}">
@endpush

@section('content')
    <div class="profile-container">
        <div class="profile-header">
            <div class="header-content">
                <div class="profile-avatar">
                    <div class="avatar-circle">
                        <i class="fa-solid fa-user"></i>
                    </div>
                </div>
                <div class="header-text">
                    <h2>Profil Saya</h2>
                    <p class="subtitle">Kelola informasi akun dan keamanan Anda</p>
                </div>
            </div>
        </div>

        {{-- Pesan Sukses --}}
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Menampilkan Error Validasi --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div>
                    <strong>Terdapat beberapa kesalahan:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.profile.update') }}" method="POST" class="profile-form">
            @csrf

            <div class="form-card">
                <div class="card-header">
                    <i class="fa-solid fa-user-circle"></i>
                    <h3>Informasi Dasar</h3>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">
                                <i class="fa-solid fa-user"></i>
                                Nama Lengkap
                            </label>
                            <input type="text" id="name" name="name" 
                                   value="{{ old('name', $admin->name) }}" 
                                   placeholder="Masukkan nama lengkap"
                                   required>
                        </div>
                        <div class="form-group">
                            <label for="email">
                                <i class="fa-solid fa-envelope"></i>
                                Email
                            </label>
                            <input type="email" id="email" name="email" 
                                   value="{{ old('email', $admin->email) }}" 
                                   placeholder="email@example.com"
                                   required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="role">
                            <i class="fa-solid fa-shield-halved"></i>
                            Role
                        </label>
                        <input type="text" id="role" 
                               value="{{ ucfirst($admin->role) }}" 
                               class="form-readonly" 
                               readonly>
                        <small class="form-hint">Role tidak dapat diubah</small>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <div class="card-header">
                    <i class="fa-solid fa-lock"></i>
                    <h3>Keamanan & Password</h3>
                </div>
                <div class="card-body">
                    <div class="info-box">
                        <i class="fa-solid fa-info-circle"></i>
                        <p>Kosongkan kolom password jika Anda tidak ingin mengubahnya. Password minimal 8 karakter.</p>
                    </div>

                    <div class="form-group">
                        <label for="current_password">
                            <i class="fa-solid fa-key"></i>
                            Password Lama
                        </label>
                        <input type="password" id="current_password" 
                               name="current_password" 
                               placeholder="Masukkan password lama"
                               autocomplete="off">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="new_password">
                                <i class="fa-solid fa-lock"></i>
                                Password Baru
                            </label>
                            <input type="password" id="new_password" 
                                   name="new_password" 
                                   placeholder="Minimal 8 karakter"
                                   autocomplete="new-password">
                        </div>
                        <div class="form-group">
                            <label for="new_password_confirmation">
                                <i class="fa-solid fa-lock-open"></i>
                                Konfirmasi Password Baru
                            </label>
                            <input type="password" id="new_password_confirmation" 
                                   name="new_password_confirmation"
                                   placeholder="Ulangi password baru"
                                   autocomplete="new-password">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection