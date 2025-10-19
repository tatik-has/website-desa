{{-- File: resources/views/presentation_tier/layout.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Surat Desa</title>

    {{-- MEMUAT SEMUA CSS YANG DIPERLUKAN DI SINI --}}
    <link rel="stylesheet" href="{{ asset('presentation_tier/css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- Ini untuk memuat CSS tambahan dari halaman lain jika perlu --}}
    @stack('styles') 
</head>
<body>

    {{-- =============================================== --}}
    {{-- == NAVBAR INI AKAN MUNCUL DI SEMUA HALAMAN   == --}}
    {{-- =============================================== --}}
    <nav class="navbar">
        <div class="navbar-left">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Desa Pakning Asal">
        </div>
        <div class="navbar-right">
            <a href="{{ url('/dashboard') }}">Home</a>
            <a href="{{ url('/pengajuan') }}">Pengajuan</a>
            <a href="{{ url('/faq') }}">FAQ</a>
            <a href="{{ route('notifications.index') }}" class="bell-icon">
                <i class="fa fa-bell"></i>
                @auth
                    @if(Auth::user() && Auth::user()->unreadNotifications->count() > 0)
                        <span class="notification-count">{{ Auth::user()->unreadNotifications->count() }}</span>
                    @endif
                @endauth
            </a>
            {{-- Tambahkan link logout jika user sudah login --}}
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-button">Logout</button>
                </form>
            @endauth
        </div>
    </nav>

    {{-- @yield('content') adalah tempat konten unik dari setiap halaman --}}
    {{-- (seperti hero section dari dashboard.blade.php) akan ditampilkan --}}
    @yield('content')

    {{-- Ini untuk memuat script tambahan dari halaman lain jika perlu --}}
    @stack('scripts')
</body>
</html>