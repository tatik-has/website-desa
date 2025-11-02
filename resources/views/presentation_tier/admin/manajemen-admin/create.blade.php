@extends('presentation_tier.admin.partials.layout')

@section('content')
<div class="content-header">
    <h2><i class="fa-solid fa-user-plus"></i> Tambah Admin Baru</h2>
</div>

<div class="form-container">
    <form action="{{ route('admin.manajemen-admin.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="nama">
                Nama Lengkap <span class="required">*</span>
            </label>
            <input type="text" 
                   id="nama" 
                   name="nama" 
                   class="form-control @error('nama') error @enderror" 
                   value="{{ old('nama') }}"
                   placeholder="Masukkan nama lengkap admin"
                   required>
            @error('nama')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">
                Email <span class="required">*</span>
            </label>
            <input type="email" 
                   id="email" 
                   name="email" 
                   class="form-control @error('email') error @enderror" 
                   value="{{ old('email') }}"
                   placeholder="contoh@email.com"
                   required>
            @error('email')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">
                Password <span class="required">*</span>
            </label>
            <input type="password" 
                   id="password" 
                   name="password" 
                   class="form-control @error('password') error @enderror"
                   placeholder="Minimal 8 karakter"
                   required>
            @error('password')
                <span class="error-message">{{ $message }}</span>
            @enderror
            <span class="form-help">Password minimal 8 karakter</span>
        </div>

        <div class="form-group">
            <label for="password_confirmation">
                Konfirmasi Password <span class="required">*</span>
            </label>
            <input type="password" 
                   id="password_confirmation" 
                   name="password_confirmation" 
                   class="form-control"
                   placeholder="Ulangi password"
                   required>
        </div>

        <div class="form-group">
            <label for="role">
                Role <span class="required">*</span>
            </label>
            <select id="role" 
                    name="role" 
                    class="form-control @error('role') error @enderror"
                    required>
                <option value="">-- Pilih Role --</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="superadmin" {{ old('role') === 'superadmin' ? 'selected' : '' }}>Superadmin</option>
            </select>
            @error('role')
                <span class="error-message">{{ $message }}</span>
            @enderror
            <span class="form-help">
                <strong>Admin:</strong> Hanya dapat mengelola permohonan surat<br>
                <strong>Superadmin:</strong> Dapat mengelola permohonan surat dan admin lainnya
            </span>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.manajemen-admin.index') }}" class="btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-save"></i> Simpan Admin
            </button>
        </div>
    </form>
</div>
@endsection