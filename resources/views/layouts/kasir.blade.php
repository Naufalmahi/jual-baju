<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koperasi Sekolah - Panel Kasir</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        darkside: '#2C1A0E',
                        brand: {
                            50: '#FDFBF7',
                            100: '#F9F6F0',
                            200: '#EAE5DD',
                            800: '#4A2E1B',
                            900: '#3D2617',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-brand-100 min-h-screen text-gray-800">

    <div class="flex min-h-screen">
        <!-- SIDEBAR DARK -->
        <aside class="w-64 bg-darkside text-white flex flex-col justify-between p-4 sticky top-0 h-screen">
            <div>
                <!-- LOGO & BRAND -->
                <div class="flex items-center gap-3 px-3 py-4 mb-6 border-b border-white/10">
                    <div class="w-10 h-10 bg-white text-darkside rounded-full flex items-center justify-center font-bold">
                        <i class="fa-solid fa-shirt text-lg"></i>
                    </div>
                    <div>
                        <h1 class="font-bold text-base uppercase tracking-wider leading-tight">Koperasi Sekolah</h1>
                        <p class="text-[10px] text-gray-400">Seragam Berkualitas</p>
                    </div>
                </div>

                <!-- NAV MENU KASIR -->
                <nav class="space-y-2">
                    <a href="{{ route('kasir.dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition {{ request()->routeIs('kasir.dashboard') ? 'bg-brand-100 text-darkside font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                        <i class="fa-solid fa-house w-5"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('kasir.orders.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition {{ request()->routeIs('kasir.orders.*') ? 'bg-brand-100 text-darkside font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                        <i class="fa-solid fa-store w-5"></i>
                        <span>Kelola Pesanan</span>
                    </a>

                    <a href="{{ route('kasir.history.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition {{ request()->routeIs('kasir.history.*') ? 'bg-brand-100 text-darkside font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                        <i class="fa-solid fa-receipt w-5"></i>
                        <span>Riwayat Transaksi</span>
                    </a>

                    <a href="{{ route('kasir.reports.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition {{ request()->routeIs('kasir.reports.*') ? 'bg-brand-100 text-darkside font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                        <i class="fa-solid fa-chart-column w-5"></i>
                        <span>Laporan</span>
                    </a>
                </nav>
            </div>

            <!-- LOGOUT -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm text-gray-400 hover:bg-red-500/20 hover:text-red-400 transition">
                    <i class="fa-solid fa-right-from-bracket w-5"></i>
                    <span>Logout</span>
                </button>
            </form>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <main class="flex-1 flex flex-col min-w-0">
            <!-- HEADER TOPBAR -->
            <header class="bg-white border-b border-brand-200 px-8 py-4 flex items-center justify-between sticky top-0 z-20">
                <i class="fa-solid fa-bars text-gray-500 text-lg cursor-pointer"></i>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-brand-200 text-brand-800 rounded-full flex items-center justify-center font-bold text-sm">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <span class="text-sm font-semibold text-gray-800">{{ auth()->user()->name ?? 'Kasir 1' }}</span>
                </div>
            </header>

            <!-- CONTENT -->
            <div class="p-8">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center gap-3">
                        <i class="fa-solid fa-circle-check text-green-600"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>

</body>
</html>