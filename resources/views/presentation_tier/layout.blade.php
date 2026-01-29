<!DOCTYPE html>
<html>
<head>
  <title>Surat Desa</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="{{ asset('presentation_tier/css/app.css') }}">
  {{-- Tambahkan link Font Awesome untuk ikon notifikasi --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  
  <style>
    body { font-family: Arial, sans-serif; background: linear-gradient(to right,#0B1A2C,#046C89,#00C4D8); color:#fff; margin:0; padding:0; }
    .container { max-width:900px; margin:40px auto; padding:20px; }
    .card { background:rgba(255,255,255,0.06); padding:18px; border-radius:10px; margin:10px; display:inline-block; width:280px; text-align:center; }
    .card a { color:#fff; text-decoration:none; font-weight:600; }
    .btn { display:inline-block; padding:8px 14px; border-radius:6px; background:#17a2b8; color:#fff; text-decoration:none; }

    /* Styling Tambahan untuk Notifikasi Floating agar lebih terlihat */
    .alert-wrapper {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
    }
    .custom-alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        backdrop-filter: blur(10px);
        animation: slideIn 0.5s ease-out forwards;
    }
    .alert-success { background: rgba(40, 167, 69, 0.9); border-left: 5px solid #1e7e34; }
    .alert-error { background: rgba(220, 53, 69, 0.9); border-left: 5px solid #bd2130; }

    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
  </style>
</head>
<body>

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
        <span>Terjadi kesalahan pada data Anda.</span>
      </div>
    @endif
  </div>

  <div class="container">
    {{-- Notifikasi inline (Cadangan) --}}
    @if(session('success'))
      <div style="background:rgba(0,255,0,0.2);padding:10px;border-radius:6px;margin-bottom:10px;border:1px solid rgba(0,255,0,0.5);">
        <i class="fas fa-check"></i> {{ session('success') }}
      </div>
    @endif

    @yield('content')
  </div>

  <script>
    // Menghilangkan notifikasi otomatis dalam 5 detik
    setTimeout(function() {
      const alerts = document.querySelectorAll('.custom-alert');
      alerts.forEach(alert => {
        alert.style.transition = "opacity 0.6s";
        alert.style.opacity = "0";
        setTimeout(() => alert.remove(), 600);
      });
    }, 5000);
  </script>
</body>
</html>