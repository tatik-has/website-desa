<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Surat Desa - Desa Pakning Asal</title>

    {{-- PATH CSS utama --}}
    <link rel="stylesheet" href="{{ asset('presentation_tier/css/masyarakat/dashboard.css') }}">

    {{-- Library ikon dan font --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @stack('styles')

</head>
<body>

    {{-- NAVBAR UTAMA --}}
    <nav class="navbar">
        <div class="navbar-left">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Desa Pakning Asal">
        </div>
        <div class="navbar-right">
            <a href="{{ url('/dashboard') }}">Home</a>
            <a href="{{ url('/pengajuan') }}">Pengajuan</a>
            <a href="{{ route('masyarakat.riwayat') }}">Riwayat</a>
            <a href="{{ url('/faq') }}">FAQ</a>

            {{-- Ikon Notifikasi --}}
            <a href="{{ route('notifications.index') }}" class="bell-icon">
                <i class="fa fa-bell"></i>
                @auth
                    @if(Auth::user()->unreadNotifications->count() > 0)
                        <span class="notification-count">
                            {{ Auth::user()->unreadNotifications->count() }}
                        </span>
                    @endif
                @endauth
            </a>

            {{-- Link Profile --}}
            @auth
                <a href="{{ route('profile.show') }}" class="profile-link">
                    <i class="fa fa-user-circle"></i>
                </a>
            @endauth

            {{-- Tombol Logout --}}
            @auth
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-button">Logout</button>
                </form>
            @endauth
        </div>
    </nav>

    {{-- KONTEN HALAMAN --}}
    @yield('content')

    {{-- FOOTER IDENTITAS --}}
    <footer class="main-footer">
        <p>&copy; 2026 Hastita Sari. All Rights Reserved.</p>
        <small>Sistem Administrasi Surat-Menyurat Desa Pakning Asal</small>
    </footer>

    {{-- SCRIPT JAVASCRIPT --}}
    <script src="{{ asset('js/app.js') }}"></script>

    @auth
    <script>
        // Real-time listener untuk status surat
        if (window.Echo) {
            window.Echo.private('user.{{ Auth::id() }}')
                .listen('StatusDiperbarui', (e) => {
                    alert(e.message);
                    if (e.message.toLowerCase().includes('selesai')) {
                        setTimeout(() => window.location.reload(), 2000);
                    }
                });
        }
    </script>
    @endauth

    @stack('scripts')
</body>
</html>