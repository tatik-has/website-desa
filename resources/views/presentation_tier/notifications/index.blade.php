@extends('presentation_tier.auth.layout')

@section('content')
<link rel="stylesheet" href="{{ asset('presentation_tier/css/notifications.css') }}">
<main class="notif-container">
    <div class="notif-header">
        <h1>Notifikasi Anda</h1>
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
                </div>
            </div>
        @empty
            <div class="no-notif">
                <p>Tidak ada notifikasi saat ini.</p>
            </div>
        @endforelse
    </div>
</main>
@endsection