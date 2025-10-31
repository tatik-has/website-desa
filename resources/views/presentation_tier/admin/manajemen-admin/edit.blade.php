{{-- File: resources/views/presentation_tier/admin/manajemen-admin/edit.blade.php --}}
@extends('presentation_tier.admin.layout')

@section('content')
<div class="content-header">
    <h2><i class="fa-solid fa-user-pen"></i> Edit Admin: {{ $admin->nama }}</h2>
</div>

<div class="form-container">
    <form action="{{ route('admin.manajemen-admin.update', $admin->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nama">
                Nama Lengkap <span class="required">*</span>
            </label>
            <input type="text" 
                   id="nama" 
                   name="nama" 
                   class="form-control @error('nama') error @enderror" 
                   value="{{ old('nama', $admin->nama) }}"
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
                   value="{{ old('email', $admin->email) }}"
                   placeholder="contoh@email.com"
                   required>
            @error('email')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">
                Password Baru
            </label>
            <input type="password" 
                   id="password" 
                   name="password" 
                   class="form-control @error('password') error @enderror"
                   placeholder="Kosongkan jika tidak ingin mengubah password">
            @error('password')
                <span class="error-message">{{ $message }}</span>
            @enderror
            <span class="form-help">
                Kosongkan jika tidak ingin mengubah password. Minimal 8 karakter jika diisi.
            </span>
        </div>

        <div class="form-group">
            <label for="password_confirmation">
                Konfirmasi Password Baru
            </label>
            <input type="password" 
                   id="password_confirmation" 
                   name="password_confirmation" 
                   class="form-control"
                   placeholder="Ulangi password baru">
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
                <option value="admin" {{ old('role', $admin->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="superadmin" {{ old('role', $admin->role) === 'superadmin' ? 'selected' : '' }}>Superadmin</option>
            </select>
            @error('role')
                <span class="error-message">{{ $message }}</span>
            @enderror
            <span class="form-help">
                <strong>Admin:</strong> Hanya dapat mengelola permohonan surat<br>
                <strong>Superadmin:</strong> Dapat mengelola permohonan surat dan admin lainnya
            </span>
        </div>

        @if($admin->id === Auth::guard('admin')->id())
            <div class="alert alert-error">
                <i class="fa-solid fa-exclamation-triangle"></i>
                <strong>Perhatian:</strong> Anda sedang mengedit akun Anda sendiri. Pastikan data yang dimasukkan sudah benar.
            </div>
        @endif

        <div class="form-actions">
            <a href="{{ route('admin.manajemen-admin.index') }}" class="btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-save"></i> Update Admin
            </button>
        </div>
    </form>
</div>
@endsection