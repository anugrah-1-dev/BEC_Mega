<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Portal BEC</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #0f172a;
            overflow: hidden;
        }

        /* Animated background */
        .bg-gfx {
            position: fixed; inset: 0; z-index: 0;
            background: linear-gradient(135deg, #1e1b4b 0%, #0f172a 50%, #1e1b4b 100%);
        }
        .blob {
            position: absolute; border-radius: 50%;
            filter: blur(80px); opacity: 0.35;
            animation: drift 8s ease-in-out infinite alternate;
        }
        .blob-1 { width:500px; height:500px; background:#4f46e5; top:-150px; left:-100px; animation-delay: 0s; }
        .blob-2 { width:400px; height:400px; background:#ec4899; bottom:-100px; right:-80px; animation-delay: 3s; }
        .blob-3 { width:300px; height:300px; background:#06b6d4; top:30%; right:20%; animation-delay: 1.5s; }
        @keyframes drift {
            from { transform: translate(0,0) scale(1); }
            to   { transform: translate(30px, -30px) scale(1.1); }
        }

        .split-layout {
            display: flex; width: 100%; min-height: 100vh;
            position: relative; z-index: 1;
        }

        /* Left branding panel */
        .brand-panel {
            flex: 1; display: flex; flex-direction: column;
            justify-content: center; align-items: flex-start;
            padding: 60px 80px;
        }
        .brand-panel img { height: 70px; width: auto; margin-bottom: 40px; filter: brightness(10); }
        .brand-tagline {
            font-size: 3rem; font-weight: 900; color: white;
            line-height: 1.15; max-width: 420px;
        }
        .brand-tagline span {
            background: linear-gradient(90deg, #818cf8, #ec4899);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .brand-desc {
            margin-top: 20px; font-size: 1rem; color: rgba(255,255,255,0.55);
            max-width: 360px; line-height: 1.7;
        }

        /* Floating stats */
        .stat-chips {
            display: flex; gap: 16px; margin-top: 48px; flex-wrap: wrap;
        }
        .stat-chip {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
            backdrop-filter: blur(10px);
            border-radius: 14px; padding: 14px 20px;
            color: white;
        }
        .stat-chip .val { font-size: 1.6rem; font-weight: 900; }
        .stat-chip .lbl { font-size: 0.72rem; color: rgba(255,255,255,0.5); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }

        /* Right form panel */
        .form-panel {
            width: 480px; min-height: 100vh;
            background: white;
            display: flex; flex-direction: column;
            justify-content: center; padding: 60px 48px;
        }

        .form-brand-mobile { display: none; }

        .form-header { margin-bottom: 36px; }
        .form-header h1 { font-size: 1.75rem; font-weight: 900; color: #0f172a; }
        .form-header p  { font-size: 0.9rem; color: #64748b; margin-top: 8px; line-height: 1.6; }

        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block; font-size: 0.78rem; font-weight: 800;
            color: #475569; text-transform: uppercase; letter-spacing: 0.07em; margin-bottom: 8px;
        }
        .form-control {
            width: 100%; padding: 14px 18px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px; font-family: 'Inter',sans-serif;
            font-size: 0.95rem; color: #0f172a;
            background: #f8fafc; transition: all 0.2s;
        }
        .form-control:focus {
            outline: none; border-color: #4f46e5;
            background: white; box-shadow: 0 0 0 4px rgba(79,70,229,0.12);
        }
        .form-error { font-size: 0.78rem; color: #ef4444; margin-top: 6px; }

        .btn-submit {
            width: 100%; padding: 15px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white; border: none; border-radius: 10px;
            font-family: 'Inter',sans-serif; font-size: 1rem; font-weight: 800;
            cursor: pointer; transition: all 0.3s;
            box-shadow: 0 8px 20px rgba(79,70,229,0.35);
            margin-top: 8px;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(79,70,229,0.45); }
        
        .btn-outline {
            width: 100%; padding: 14px;
            background: transparent;
            color: #64748b; border: 1.5px solid #e2e8f0; border-radius: 10px;
            font-family: 'Inter',sans-serif; font-size: 0.9rem; font-weight: 700;
            cursor: pointer; transition: all 0.2s;
            text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px;
            margin-top: 12px;
        }
        .btn-outline:hover { background: #f8fafc; border-color: #cbd5e1; color: #475569; }

        .form-footer {
            text-align: center; margin-top: 28px;
            font-size: 0.875rem; color: #64748b;
        }
        .form-footer a { color: #4f46e5; font-weight: 700; text-decoration: none; }
        .form-footer a:hover { text-decoration: underline; }

        .divider {
            height: 1px; background: #f1f5f9;
            margin: 28px 0;
        }

        @media (max-width: 900px) {
            .brand-panel { display: none; }
            .form-panel { width: 100%; padding: 40px 28px; }
        }
    </style>
</head>
<body>
<div class="bg-gfx">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
</div>

<div class="split-layout">
    <!-- Branding Side -->
    <div class="brand-panel">
        <img src="{{ asset('assets/logo_BEC.png') }}" alt="BEC Logo">
        <h1 class="brand-tagline">
            Mulai Perjalanan<br><span>Bahasa Inggris</span><br>Anda di BEC
        </h1>
        <p class="brand-desc">
            Bergabunglah dengan ribuan siswa Brilliant English Course — kursus bahasa Inggris terpercaya.
        </p>
        <div class="stat-chips">
            <div class="stat-chip">
                <div class="val">1000+</div>
                <div class="lbl">Alumni</div>
            </div>
            <div class="stat-chip">
                <div class="val">20+</div>
                <div class="lbl">Program</div>
            </div>
            <div class="stat-chip">
                <div class="val">5★</div>
                <div class="lbl">Rating</div>
            </div>
        </div>
    </div>

    <!-- Form Side -->
    <div class="form-panel">
        <div class="form-header">
            <h1>Selamat Datang Kembali 👋</h1>
            <p>Masuk ke portal untuk melanjutkan pendaftaran atau memeriksa status Anda.</p>
        </div>

        @if ($errors->any())
            <div style="background:#fee2e2; color:#991b1b; padding:14px 18px; border-radius:10px; margin-bottom:20px; font-size:0.875rem; border:1px solid #fca5a5; display:flex; align-items:center; gap:10px;">
                <svg style="width:18px;height:18px;flex-shrink:0" viewBox="0 0 24 24"><path fill="currentColor" d="M13,13H11V7H13M13,17H11V15H13M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"/></svg>
                Email atau password salah. Silakan coba lagi.
            </div>
        @endif

        <form action="{{ route('login.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="nama@email.com" autocomplete="email">
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••" autocomplete="current-password">
            </div>
            <button type="submit" class="btn-submit">MASUK KE PORTAL →</button>
        </form>

        <div class="divider"></div>
        <div class="form-footer">
            Belum punya akun?
            <a href="{{ route('register') }}" class="btn-outline" style="background:#4f46e5; color:white; border:none; margin-top:8px;">
                <svg style="width:18px;height:18px" viewBox="0 0 24 24"><path fill="currentColor" d="M15,14C12.33,14 7,15.33 7,18V20H23V18C23,15.33 17.67,14 15,14M6,10V7H4V10H1V12H4V15H6V12H9V10H6M15,12A4,4 0 0,0 19,8A4,4 0 0,0 15,4A4,4 0 0,0 11,8A4,4 0 0,0 15,12Z"/></svg>
                DAFTAR SEKARANG
            </a>
        </div>
        <div class="form-footer" style="margin-top:16px;">
            <a href="{{ route('home') }}" class="btn-outline">
                <svg style="width:18px;height:18px" viewBox="0 0 24 24"><path fill="currentColor" d="M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z"/></svg>
                Kembali ke Virtual Tour
            </a>
        </div>
    </div>
</div>
</body>
</html>
