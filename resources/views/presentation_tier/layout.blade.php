<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Surat Desa</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  {{-- CSS Files --}}
  <link rel="stylesheet" href="{{ asset('presentation_tier/css/masyarakat/app.css') }}">
  <link rel="stylesheet" href="{{ asset('presentation_tier/css/layout.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  
  @stack('styles')
</head>
<body>

  {{-- Floating Alert Notifications --}}
  <div class="alert-wrapper">
    @if(session()->has('success'))
      <div class="custom-alert alert-success">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
      </div>
    @endif

    @if($errors->any())
      <div class="custom-alert alert-error">
        <i class="fas fa-exclamation-circle"></i>
        <span>
          @if($errors->count() == 1)
            {{ $errors->first() }}
          @else
            Terjadi kesalahan pada data Anda.
          @endif
        </span>
      </div>
    @endif
  </div>

  {{-- Main Container --}}
  <div class="container">
    {{-- Inline Alert (Backup untuk koneksi lambat) --}}
    @if(session('success'))
      <div class="inline-alert">
        <i class="fas fa-check"></i>
        <span>{{ session('success') }}</span>
      </div>
    @endif

    {{-- Content Section --}}
    @yield('content')
  </div>

  {{-- JavaScript --}}
  <script>
    // Menghilangkan notifikasi otomatis dalam 5 detik
    setTimeout(function() {
      const alerts = document.querySelectorAll('.custom-alert');
      alerts.forEach(alert => {
        alert.style.transition = "opacity 0.6s ease, transform 0.6s ease";
        alert.style.opacity = "0";
        alert.style.transform = "translateX(120%)";
        setTimeout(() => alert.remove(), 600);
      });
    }, 5000);

    // Close alert on click
    document.querySelectorAll('.custom-alert').forEach(alert => {
      alert.style.cursor = 'pointer';
      alert.addEventListener('click', function() {
        this.style.transition = "opacity 0.3s ease, transform 0.3s ease";
        this.style.opacity = "0";
        this.style.transform = "translateX(120%)";
        setTimeout(() => this.remove(), 300);
      });
    });
  </script>

  @stack('scripts')
</body>
</html>