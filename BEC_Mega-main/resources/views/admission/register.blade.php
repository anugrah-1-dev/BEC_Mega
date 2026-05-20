<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun — Portal BEC</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #0f172a;
        }
        .bg-gfx { position:fixed; inset:0; z-index:0; background: linear-gradient(135deg, #1e1b4b 0%, #0f172a 50%, #0c1a35 100%); }
        .blob { position:absolute; border-radius:50%; filter:blur(80px); opacity:0.3; animation: drift 8s ease-in-out infinite alternate; }
        .blob-1 { width:400px; height:400px; background:#4f46e5; top:-100px; right:200px; }
        .blob-2 { width:350px; height:350px; background:#10b981; bottom:-80px; left:100px; animation-delay:2s; }
        @keyframes drift { from{ transform:translate(0,0); } to{ transform:translate(20px,-20px); } }

        .center-wrap {
            position: relative; z-index:1;
            display: flex; align-items: center; justify-content: center;
            min-height: 100vh; width: 100%; padding: 40px 20px;
        }

        .register-card {
            background: white; border-radius: 24px;
            width: 100%; max-width: 560px;
            padding: 48px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.5);
        }

        .card-logo {
            display: flex; align-items: center; gap: 14px; margin-bottom: 32px;
        }
        .card-logo img { height: 48px; width: auto; }
        .card-logo .bec-name { font-size: 0.7rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; color: #64748b; }

        .card-header { margin-bottom: 32px; }
        .card-header h1 { font-size: 1.6rem; font-weight: 900; color: #0f172a; }
        .card-header p  { font-size: 0.9rem; color: #64748b; margin-top: 8px; line-height: 1.6; }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0 20px; }

        .form-group { margin-bottom: 20px; }
        .form-group.full { grid-column: span 2; }

        .form-label { display:block; font-size:0.78rem; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.07em; margin-bottom:8px; }
        .form-control {
            width:100%; padding:13px 16px;
            border:1.5px solid #e2e8f0;
            border-radius:10px; font-family:'Inter',sans-serif;
            font-size:0.9rem; color:#0f172a;
            background:#f8fafc; transition:all 0.2s;
        }
        .form-control:focus { outline:none; border-color:#4f46e5; background:white; box-shadow:0 0 0 4px rgba(79,70,229,0.12); }
        .form-error { font-size:0.78rem; color:#ef4444; margin-top:5px; }

        .btn-submit {
            width:100%; padding:15px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color:white; border:none; border-radius:10px;
            font-family:'Inter',sans-serif; font-size:1rem; font-weight:800;
            cursor:pointer; transition:all 0.3s;
            box-shadow: 0 8px 20px rgba(79,70,229,0.35);
        }
        .btn-submit:hover { transform:translateY(-2px); }

        .form-footer { text-align:center; margin-top:24px; font-size:0.875rem; color:#64748b; }
        .form-footer a { color:#4f46e5; font-weight:700; text-decoration:none; }

        .step-pills {
            display: flex; gap: 8px; margin-bottom: 32px; flex-wrap: wrap;
        }
        .pill {
            padding: 6px 14px; border-radius: 99px;
            font-size: 0.72rem; font-weight: 800; text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .pill-1 { background: rgba(79,70,229,0.1); color: #4f46e5; }
        .pill-2 { background: #f1f5f9; color: #94a3b8; }
        .pill-3 { background: #f1f5f9; color: #94a3b8; }
    </style>
</head>
<body>
<div class="bg-gfx">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
</div>
<div class="center-wrap">
    <div class="register-card">
        <div class="card-logo">
            <img src="{{ asset('assets/logo_BEC.png') }}" alt="BEC">
            <div>
                <div style="font-size:1rem; font-weight:900; color:#0f172a;">Brilliant English Course</div>
            </div>
        </div>

        <div class="step-pills">
            <span class="pill pill-1">① Buat Akun</span>
            <span class="pill pill-2">② Isi Data</span>
            <span class="pill pill-2">③ Pilih Kursus</span>
        </div>

        <div class="card-header">
            <h1>Daftar Akun Pendaftar</h1>
            <p>Langkah pertama pendaftaran BEC. Setelah membuat akun, lengkapi data diri dan pilih program kursus Anda.</p>
        </div>

        @if ($errors->any())
            <div style="background:#fee2e2;color:#991b1b;padding:14px 18px;border-radius:10px;margin-bottom:20px;font-size:0.875rem;border:1px solid #fca5a5;">
                @foreach ($errors->all() as $error)
                    <div>• {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('register.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group full">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                           required placeholder="Sesuai kartu identitas">
                    @error('name') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group full">
                    <label class="form-label">Alamat Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                           required placeholder="aktif@email.com">
                    @error('email') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control"
                           required placeholder="Min. 8 karakter">
                    @error('password') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control"
                           required placeholder="Ulangi password">
                </div>
            </div>

            <button type="submit" class="btn-submit">BUAT AKUN &amp; LANJUTKAN →</button>
        </form>

        <div class="form-footer" style="margin-top:20px;">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
            &nbsp;·&nbsp; <a href="{{ route('home') }}" style="color:#64748b;">← Virtual Tour</a>
        </div>
    </div>
</div>
</body>
</html>
