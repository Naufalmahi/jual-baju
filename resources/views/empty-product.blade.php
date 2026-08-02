<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk Tidak Ditemukan - SchoolWear</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body>
    <div class="error-page">
        <div class="error-card fade-in" style="max-width:400px">
            <div class="error-icon" style="background:#fef3c7;color:var(--warning);width:72px;height:72px;font-size:1.5rem">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="error-code" style="font-size:2.5rem;color:var(--warning);margin-bottom:4px">Kosong</div>
            <h2 style="font-size:1.15rem">Belum Ada Produk</h2>
            <p style="font-size:.82rem">Belum ada produk seragam yang tersedia di kategori ini. Silakan cek kategori lainnya.</p>
            <div class="error-actions">
                <a href="{{ route('siswa.products.index') }}" class="btn-primary-custom">
                    <i class="bi bi-bag"></i> Lihat Semua Produk
                </a>
                <a href="{{ url('/') }}" class="btn-outline-custom">
                    <i class="bi bi-house"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
        <footer class="error-footer">
            <p>&copy; {{ date('Y') }} <a href="{{ url('/') }}">Koperasi SMKN 17 Jakarta</a>. Semua hak dilindungi.</p>
        </footer>
    </div>
</body>
</html>