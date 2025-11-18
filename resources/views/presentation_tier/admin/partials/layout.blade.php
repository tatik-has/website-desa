{{-- File: resources/views/presentation_tier/admin/layout.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Desa</title>

    {{-- CSS dan Font yang sama untuk semua halaman admin --}}
    <link rel="stylesheet" href="{{ asset('presentation_tier/css/admin/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Slot untuk CSS tambahan per halaman --}}
    @stack('styles')
</head>

<body>
    <div class="admin-container">
        {{-- =============================================== --}}
        {{-- == BAGIAN SIDEBAR YANG AKAN SELALU SAMA == --}}
        {{-- =============================================== --}}
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
                <h3>Admin Desa</h3>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ url('/admin/dashboard') }}" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie"></i> <span>Dashboard</span>
                </a>
                <a href="{{ url('/admin/surat') }}" class="{{ request()->is('admin/surat*') ? 'active' : '' }}">
                    <i class="fa-solid fa-envelope"></i>
                    <span>Permohonan Surat</span>
                </a>
                <a href="{{ url('/admin/laporan') }}" class="{{ request()->is('admin/laporan') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-alt"></i>
                    <span>Laporan</span>
                </a>

                {{-- ✅ MENU ARSIP BARU --}}
                <a href="{{ route('admin.arsip') }}" class="{{ request()->is('admin/arsip*') ? 'active' : '' }}">
                    <i class="fa-solid fa-archive"></i>
                    <span>Arsip</span>
                </a>

                {{-- Menu Manajemen Admin hanya untuk superadmin --}}
                @if(Auth::guard('admin')->user()->role == 'superadmin')
                    <a href="{{ url('/admin/manajemen-admin') }}"
                        class="{{ request()->is('admin/manajemen-admin*') ? 'active' : '' }}">
                        <i class="fa-solid fa-users-cog"></i>
                        <span>Manajemen Admin</span>
                    </a>
                @endif

                {{-- Menu Profile untuk semua admin --}}
                <a href="{{ route('admin.profile.show') }}" 
                    class="{{ request()->is('admin/profile*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-circle"></i>
                    <span>Profile</span>
                </a>
            </nav>
        </aside>

        {{-- =============================================== --}}
        {{-- == BAGIAN KONTEN UTAMA == --}}
        {{-- =============================================== --}}
        <main class="main-content">

            {{-- Bagian Top Bar --}}
            <div class="top-bar">
                {{-- Wrapper Kiri (Kosong) --}}
                <div class="top-bar-left">
                </div>

                {{-- Wrapper Kanan (Notifikasi & Logout) --}}
                <div class="top-bar-right">
                    {{-- Notifikasi --}}
                    <div class="notification-wrapper">
                        <i class="fa-solid fa-bell icon-btn" id="notification-bell">
                            <span class="notification-count" id="notification-count">0</span>
                        </i>
                        <div class="notification-dropdown" id="notification-dropdown">
                            <div class="notification-header">
                                <span>Notifikasi</span>
                            </div>
                            <div class="notification-list" id="notification-list">
                                <p style="text-align: center; padding: 20px; color: #888;">
                                    Tidak ada notifikasi baru.
                                </p>
                            </div>
                        </div>
                    </div>

                    <span class="divider">|</span> {{-- Pemisah visual --}}

                    {{-- Logout --}}
                    <form action="{{ route('admin.logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>


            {{-- Tempat konten unik tiap halaman --}}
            @yield('content')
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    @auth('admin')
    <script>
        $(document).ready(function () {
            const bell = $('#notification-bell');
            const countBadge = $('#notification-count');
            const dropdown = $('#notification-dropdown');
            const list = $('#notification-list');

            function fetchNotifications() {
                $.ajax({
                    url: '{{ route("admin.notifications.unread") }}',
                    method: 'GET',
                    success: function (response) {
                        // Update badge count
                        if (response.count > 0) {
                            countBadge.text(response.count).show();
                        } else {
                            countBadge.hide();
                        }

                        // Update dropdown list
                        list.empty();
                        if (response.notifications.length > 0) {
                            response.notifications.forEach(function (notif) {
                                // Ambil kunci rute dari data notifikasi
                                let typeKey = notif.data.jenis_surat_key; // 'domisili', 'ktm', atau 'sku'
                                let permohonanId = notif.data.permohonan_id;

                                // Buat URL sesuai route yang ada di web.php
                                let url = '';
                                if (typeKey === 'domisili') {
                                    url = '{{ route("admin.domisili.show", ":id") }}'.replace(':id', permohonanId);
                                } else if (typeKey === 'ktm') {
                                    url = '{{ route("admin.ktm.show", ":id") }}'.replace(':id', permohonanId);
                                } else if (typeKey === 'sku') {
                                    url = '{{ route("admin.sku.show", ":id") }}'.replace(':id', permohonanId);
                                } else {
                                    url = '#'; // fallback jika tidak dikenali
                                }

                                let item = `
                                    <a href="${url}" class="notification-item">
                                        <div class="message">${notif.data.pesan}</div>
                                        <div class="timestamp">${new Date(notif.created_at).toLocaleString('id-ID')}</div>
                                    </a>
                                `;
                                list.append(item);
                            });
                        } else {
                            list.html('<p style="text-align: center; padding: 20px; color: #888;">Tidak ada notifikasi baru.</p>');
                        }
                    }
                });
            }

            // Klik lonceng untuk tampil/sembunyi dropdown
            bell.on('click', function (e) {
                e.stopPropagation();
                dropdown.toggle();

                // Tandai notifikasi sudah dibaca
                if (dropdown.is(':visible') && parseInt(countBadge.text()) > 0) {
                    $.ajax({
                        url: '{{ route("admin.notifications.markAsRead") }}',
                        method: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function () {
                            countBadge.text('0').hide();
                        }
                    });
                }
            });

            // Sembunyikan dropdown saat klik di luar area
            $(document).on('click', function (e) {
                if (!$(e.target).closest('.notification-wrapper').length) {
                    dropdown.hide();
                }
            });

            // Ambil notifikasi saat halaman pertama dimuat
            fetchNotifications();

            // Laravel Echo real-time listener
            if (typeof window.Echo !== 'undefined') {
                window.Echo.private('admin-channel')
                    .listen('SuratDiajukan', (e) => {
                        console.log('Event diterima:', e);
                        fetchNotifications();

                        // Tambah notifikasi popup kecil (opsional)
                        const toast = $('<div class="toast-notification"></div>')
                            .text(e.message)
                            .appendTo('body')
                            .css('display', 'block');
                        setTimeout(() => toast.fadeOut(500, () => toast.remove()), 4000);
                    });
            }
        });
    </script>
@endauth

    {{-- Slot untuk script tambahan dari halaman lain --}}
    @stack('scripts')
</body>

</html>