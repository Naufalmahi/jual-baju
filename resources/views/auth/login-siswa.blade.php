<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Siswa - SchoolWear</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        .auth-badge{display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:var(--radius-full);background:rgba(255,255,255,.12);backdrop-filter:blur(4px);font-size:.7rem;font-weight:600;border:1px solid rgba(255,255,255,.15);margin-bottom:16px;color:rgba(255,255,255,.85)}
    </style>
</head>
<body>
    <div class="auth-page-siswa">
        <div class="auth-card-custom fade-in">
            <div class="auth-brand">
                <i class="bi bi-bag-fill"></i>
                <span>SchoolWear</span>
            </div>
            <div class="auth-title">Masuk Siswa</div>
            <div class="auth-subtitle">Masuk menggunakan NISN dan password</div>

            @if($errors->any())
                <div class="auth-error">
                    <i class="bi bi-exclamation-circle"></i>
                    {{ $errors->first() }}
                </div>
            @endif
            @if(session('success'))
                <div class="auth-success">
                    <i class="bi bi-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.siswa.process') }}">
                @csrf
                <div class="auth-input-group">
                    <label class="auth-label">NISN</label>
                    <input type="text" name="nisn" class="auth-input" placeholder="Masukkan NISN" value="{{ old('nisn') }}" required autofocus>
                </div>
                <div class="auth-input-group">
                    <label class="auth-label">Password</label>
                    <input type="password" name="password" class="auth-input" placeholder="Masukkan password" required>
                </div>
                <button type="submit" class="btn-primary-custom auth-btn">
                    <i class="bi bi-box-arrow-in-right"></i> Masuk
                </button>
            </form>

            <div class="auth-footer-text">
                Petugas? <a href="{{ route('login.petugas') }}">Masuk di sini</a>
            </div>
        </div>
    </div>
</body>
</html>