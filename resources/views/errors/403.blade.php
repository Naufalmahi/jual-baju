<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen p-4">
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8 text-center">
        <h1 class="text-7xl font-extrabold text-red-500 mb-2">403</h1>
        <h2 class="text-2xl font-bold text-slate-800 mb-4">Akses Ditolak</h2>
        <p class="text-slate-600 mb-6">Kamu tidak memiliki izin untuk mengakses halaman ini.</p>
        <a href="{{ url('/') }}" class="inline-block px-6 py-3 bg-slate-800 text-white font-medium rounded-lg shadow-md hover:bg-slate-900 transition duration-200">
            Kembali ke Beranda
        </a>
    </div>
</body>
</html>