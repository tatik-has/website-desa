<!DOCTYPE html>
<html>
<head>
  <title>Surat Desa</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="{{ asset('presentation_tier/css/app.css') }}">
  <style>
    body { font-family: Arial, sans-serif; background: linear-gradient(to right,#0B1A2C,#046C89,#00C4D8); color:#fff; margin:0; padding:0; }
    .container { max-width:900px; margin:40px auto; padding:20px; }
    .card { background:rgba(255,255,255,0.06); padding:18px; border-radius:10px; margin:10px; display:inline-block; width:280px; text-align:center; }
    .card a { color:#fff; text-decoration:none; font-weight:600; }
    .btn { display:inline-block; padding:8px 14px; border-radius:6px; background:#17a2b8; color:#fff; text-decoration:none; }
  </style>
</head>
<body>
  <div class="container">
    @if(session('success'))
      <div style="background:rgba(0,255,0,0.12);padding:10px;border-radius:6px;margin-bottom:10px;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div style="background:rgba(255,0,0,0.12);padding:10px;border-radius:6px;margin-bottom:10px;">{{ session('error') }}</div>
    @endif

    @yield('content')
  </div>
</body>
</html>
