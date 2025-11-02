{{-- File: resources/views/presentation_tier/auth/layout.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Surat Desa</title>

    {{-- PATH CSS utama --}}
    <link rel="stylesheet" href="{{ asset('presentation_tier/css/masyarakat/dashboard.css') }}">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @stack('styles') 
</head>
<body>

    {{-- ================================================= --}}
    {{-- NAVBAR UTAMA UNTUK PENGGUNA LOGIN                --}}
    {{-- ================================================= --}}
    <nav class="navbar">
        <div class="navbar-left">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Desa Pakning Asal">
        </div>
        <div class="navbar-right">
            <a href="{{ url('/dashboard') }}">Home</a>
            <a href="{{ url('/pengajuan') }}">Pengajuan</a>
            <a href="{{ url('/faq') }}">FAQ</a>

            {{-- Ikon Notifikasi --}}
            <a href="{{ route('notifications.index') }}" class="bell-icon">
                <i class="fa fa-bell"></i>
                @auth
                    @if(Auth::user() && Auth::user()->unreadNotifications->count() > 0)
                        <span class="notification-count">
                            {{ Auth::user()->unreadNotifications->count() }}
                        </span>
                    @endif
                @endauth
            </a>

            {{-- Tombol Logout --}}
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-button">Logout</button>
                </form>
            @endauth
        </div>
    </nav>

    {{-- ================================================= --}}
    {{-- BAGIAN UTAMA UNTUK KONTEN HALAMAN                --}}
    {{-- ================================================= --}}
    @yield('content')

    {{-- ================================================= --}}
    {{-- SCRIPT JAVASCRIPT DAN REAL-TIME LISTENER         --}}
    {{-- ================================================= --}}
    <script src="{{ asset('js/app.js') }}"></script> {{-- Pastikan ini di-load --}}

    @auth
    <script>
        // Mendengarkan channel privat user, misalnya 'user.1', 'user.2', dst.
        window.Echo.private('user.{{ Auth::id() }}')
            .listen('StatusDiperbarui', (e) => {
                // Tampilkan pesan dari event
                alert(e.message);

                // Jika status berisi kata "SELESAI", reload halaman agar tombol download muncul
                if (e.message.includes('SELESAI')) {
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                }
            });
    </script>
    @endauth

    {{-- Tempat untuk script tambahan --}}
    @stack('scripts')
</body>
</html>
