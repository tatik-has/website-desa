{{-- File: resources/views/presentation_tier/dashboard.blade.php --}}
@extends('presentation_tier.masyarakat.layout')

@section('content')
    <main class="hero-container">
        <div class="hero-content">
            <div class="hero-text">
                <p class="hero-subtitle">Administrasi Surat-Menyurat</p>
                <h1 class="hero-title">Desa Pakning Asal</h1>
            </div>
            <div class="hero-action">
                <a href="{{ url('/pengajuan') }}" class="hero-button">Pengajuan</a>
            </div>
        </div>
        <p class="hero-tagline">Mempermudah Setiap Proses, Mempercepat Setiap Langkah.</p>
    </main>
@endsection

@push('scripts')
    {{-- Memuat SweetAlert2 dari CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Cek jika ada flash session 'success'
        @if(session('success'))
            Swal.fire({
                title: 'Terima Kasih!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'Siap!',
                confirmButtonColor: '#2c3e50', // Sesuaikan dengan warna tema desamu
                timer: 5000, // Pop-up akan hilang sendiri dalam 5 detik
                timerProgressBar: true
            });
        @endif

        // Cek jika ada flash session 'error'
        @if(session('error'))
            Swal.fire({
                title: 'Oops!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#e74c3c'
            });
        @endif
    </script>
@endpush