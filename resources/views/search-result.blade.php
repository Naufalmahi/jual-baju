<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian - SchoolWear</title>
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
            <div class="error-icon" style="background:var(--primary-lighter);color:var(--primary);width:72px;height:72px;font-size:1.5rem">
                <i class="bi bi-search"></i>
            </div>
            <div class="error-code" style="font-size:2.5rem;color:var(--primary);margin-bottom:4px">0</div>
            <h2 style="font-size:1.15rem">Hasil Pencarian Tidak Ditemukan</h2>
            <p style="font-size:.82rem">Maaf, tidak ada produk yang cocok dengan kata kunci "<strong style="color:var(--neutral-700)">{{ $keyword ?? '' }}</strong>". Coba gunakan kata kunci lain.</p>
            <div class="error-actions">
                <a href="{{ route('siswa.products.index') }}" class="btn-primary-custom">
                    <i class="bi bi-bag"></i> Lihat Semua Produk
                </a>
                <a href="javascript:history.back()" class="btn-outline-custom">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        <footer class="error-footer">
            <p>&copy; {{ date('Y') }} <a href="{{ url('/') }}">Koperasi SMKN 17 Jakarta</a>. Semua hak dilindungi.</p>
        </footer>
    </div>
</body>
</html>