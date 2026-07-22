<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Siswa - Koperasi Sekolah')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 font-sans min-h-screen flex flex-col">

    <!-- NAVBAR TOP (Siswa tidak pakai sidebar) -->
    <header class="bg-indigo-700 text-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <i class="fas fa-shopping-bag text-2xl"></i>
                <span class="font-bold text-lg tracking-wide">KOPERASI SISWA</span>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="text-right hidden sm:block">
                    <p class="text-xs text-indigo-200">Siswa</p>
                    <p class="text-sm font-semibold">{{ Auth::user()->name ?? 'Nama Siswa' }}</p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 bg-red-500 hover:bg-red-600 rounded-lg text-xs font-semibold transition">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- CONTENT SISWA -->
    <main class="max-w-7xl mx-auto w-full p-4 sm:p-6 flex-1">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-white border-t py-4 text-center text-xs text-gray-500">
        &copy; {{ date('Y') }} Koperasi Sekolah - Sistem Informasi Penjualan
    </footer>

</body>
</html>