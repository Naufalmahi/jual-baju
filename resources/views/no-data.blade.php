<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tidak Ada Data - SchoolWear</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body>
    <div class="error-page">
        <div class="error-card fade-in" style="max-width:400px">
            <div class="error-icon" style="background:var(--neutral-100);color:var(--neutral-400);width:72px;height:72px;font-size:1.5rem">
                <i class="bi bi-inbox"></i>
            </div>
            <div class="error-code" style="font-size:2.5rem;color:var(--neutral-400);margin-bottom:4px">Kosong</div>
            <h2 style="font-size:1.15rem;color:var(--neutral-600)">Tidak Ada Data</h2>
            <p style="font-size:.82rem">Belum ada data yang tersedia untuk ditampilkan saat ini.</p>
            <div class="error-actions">
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