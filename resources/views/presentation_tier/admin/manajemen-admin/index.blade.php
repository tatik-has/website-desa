{{-- File: resources/views/presentation_tier/admin/manajemen-admin/index.blade.php --}}
@extends('presentation_tier.admin.layout')

@section('content')
<div class="content-header">
    <h2><i class="fa-solid fa-users-cog"></i> Manajemen Admin</h2>
    <a href="{{ route('admin.manajemen-admin.create') }}" class="btn-primary">
        <i class="fa-solid fa-plus"></i> Tambah Admin Baru
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        <i class="fa-solid fa-circle-check"></i>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-error">
        <i class="fa-solid fa-circle-exclamation"></i>
        {{ session('error') }}
    </div>
@endif

@if($admins->count() > 0)
    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Tanggal Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admins as $index => $admin)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $admin->nama }}</strong>
                        @if($admin->id === Auth::guard('admin')->id())
                            <span style="color: #059669; font-size: 12px;">(Anda)</span>
                        @endif
                    </td>
                    <td>{{ $admin->email }}</td>
                    <td>
                        @if($admin->role === 'superadmin')
                            <span class="badge badge-superadmin">Superadmin</span>
                        @else
                            <span class="badge badge-admin">Admin</span>
                        @endif
                    </td>
                    <td>{{ $admin->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.manajemen-admin.edit', $admin->id) }}" class="btn-edit">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>
                            
                            @if($admin->id !== Auth::guard('admin')->id())
                                <form action="{{ route('admin.manajemen-admin.destroy', $admin->id) }}" 
                                      method="POST" 
                                      style="display: inline;"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus admin {{ $admin->nama }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">
                                        <i class="fa-solid fa-trash"></i> Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="empty-state">
        <i class="fa-solid fa-users-slash"></i>
        <h3>Belum Ada Admin</h3>
        <p>Belum ada data admin yang terdaftar dalam sistem.</p>
        <a href="{{ route('admin.manajemen-admin.create') }}" class="btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Admin Pertama
        </a>
    </div>
@endif
@endsection