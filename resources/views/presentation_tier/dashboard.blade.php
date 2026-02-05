{{-- File: resources/views/presentation_tier/dashboard.blade.php --}}
@extends('presentation_tier.masyarakat.layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('presentation_tier/css/dashboard.css') }}">
@endpush

@section('content')
    <main class="hero-container">
        <div class="hero-content">
            <div class="hero-text">
                <p class="hero-subtitle">Administrasi Surat-Menyurat</p>
                <h1 class="hero-title">Desa Pakning Asal</h1>
            </div>
            <div class="hero-action">
                <a href="{{ url('/pengajuan') }}" class="hero-button">
                    <i class="fas fa-file-alt"></i>
                    <span>Pengajuan Surat</span>
                </a>
            </div>
        </div>
        <p class="hero-tagline">Mempermudah Setiap Proses, Mempercepat Setiap Langkah.</p>
    </main>
@endsection

@push('scripts')
    {{-- Memuat SweetAlert2 dari CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Konfigurasi SweetAlert2 yang responsif
        const swalConfig = {
            confirmButtonColor: '#2c3e50',
            timer: 5000,
            timerProgressBar: true,
            showClass: {
                popup: 'animate__animated animate__fadeInDown animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp animate__faster'
            },
            // Responsif untuk mobile
            width: window.innerWidth < 576 ? '90%' : '32em',
            padding: window.innerWidth < 576 ? '1.5em' : '3em'
        };

        // Cek jika ada flash session 'success'
        @if(session('success'))
            Swal.fire({
                ...swalConfig,
                title: 'Terima Kasih!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'Siap!'
            });
        @endif

        // Cek jika ada flash session 'error'
        @if(session('error'))
            Swal.fire({
                ...swalConfig,
                title: 'Oops!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#e74c3c',
                timer: null // Tidak auto-close untuk error
            });
        @endif

        // Adjust SweetAlert size on window resize
        window.addEventListener('resize', function() {
            const root = document.documentElement;
            if (window.innerWidth < 576) {
                root.style.setProperty('--swal-width', '90%');
            } else {
                root.style.setProperty('--swal-width', '32em');
            }
        });
    </script>
@endpush