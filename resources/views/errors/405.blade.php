<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>405 - Metode Tidak Diizinkan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body>
    <div class="error-page">
        <div class="error-card fade-in">
            <div class="error-brand">
                <i class="bi bi-bag-fill"></i>
                <span>SchoolWear</span>
            </div>
            <div class="error-icon" style="background:#fce4ec;color:var(--secondary)">
                <i class="bi bi-ban"></i>
            </div>
            <div class="error-code" style="color:var(--secondary)">405</div>
            <h2>Metode Tidak Diizinkan</h2>
            <p>Metode permintaan ini tidak didukung oleh server. Silakan coba dengan cara yang berbeda.</p>
            <div class="error-actions">
                <a href="{{ url('/') }}" class="btn-primary-custom">
                    <i class="bi bi-house"></i> Kembali ke Beranda
                </a>
                <a href="javascript:history.back()" class="btn-outline-custom">
                    <i class="bi bi-arrow-left"></i> Kembali ke Halaman Sebelumnya
                </a>
            </div>
        </div>
        <footer class="error-footer">
            <p>&copy; {{ date('Y') }} <a href="{{ url('/') }}">Koperasi SMKN 17 Jakarta</a>. Semua hak dilindungi.</p>
        </footer>
    </div>
</body>
</html>