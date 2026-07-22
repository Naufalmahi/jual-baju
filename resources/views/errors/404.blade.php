<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen p-4">
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8 text-center">
        <h1 class="text-7xl font-extrabold text-indigo-600 mb-2">404</h1>
        <h2 class="text-2xl font-bold text-slate-800 mb-4">Halaman Tidak Ditemukan</h2>
        <p class="text-slate-600 mb-6">Maaf, halaman yang kamu cari tidak ada atau telah dipindahkan.</p>
        <a href="{{ url('/') }}" class="inline-block px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg shadow-md hover:bg-indigo-700 transition duration-200">
            Kembali ke Beranda
        </a>
    </div>
</body>
</html>