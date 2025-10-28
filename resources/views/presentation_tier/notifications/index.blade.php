@extends('presentation_tier.auth.layout')

@section('content')
<link rel="stylesheet" href="{{ asset('presentation_tier/css/notifications.css') }}">
<main class="notif-container">
    <div class="notif-header">
        <h1>Notifikasi Anda</h1>
        @if($notifications->count() > 0)
            <form action="{{ route('notifications.deleteAll') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus semua notifikasi?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete-all">
                    <i class="fas fa-trash-alt"></i> Hapus Semua
                </button>
            </form>
        @endif
    </div>

    <div class="notif-list">
        @forelse ($notifications as $notification)
            <div class="notif-item">
                <div class="notif-icon">
                    <i class="fas fa-file-pdf"></i>
                </div>

                <div class="notif-content">
                    <p class="message">{{ $notification->data['pesan'] }}</p>
                    <span class="time">{{ $notification->created_at->diffForHumans() }}</span>
                </div>

                <div class="notif-action">
                    @if (!empty($notification->data['file_path']))
                        <a href="{{ Storage::url($notification->data['file_path']) }}" class="btn-download" target="_blank">
                            <i class="fas fa-download"></i> Unduh
                        </a>
                    @endif
                    
                    <form action="{{ route('notifications.delete', $notification->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete" onclick="return confirm('Yakin ingin menghapus notifikasi ini?')">
                            <i class="fas fa-trash-alt"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="no-notif">
                <i class="fas fa-bell-slash"></i>
                <p>Tidak ada notifikasi saat ini.</p>
            </div>
        @endforelse
    </div>
</main>
@endsection