<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SchoolWear - Seragam Kualitas, Prestasi Bangsa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-brand-100 min-h-screen text-gray-800">

    <div class="flex min-h-screen">
        <!-- SIDEBAR LEFT -->
        <aside class="w-64 bg-white border-r border-brand-200 flex flex-col justify-between p-4 sticky top-0 h-screen">
            <div>
                <!-- LOGO & BRAND -->
                <div class="flex items-center gap-3 px-3 py-2 mb-6">
                    <div class="w-10 h-10 bg-brand-800 rounded-xl flex items-center justify-center text-white">
                        <i class="fa-solid fa-shirt text-lg"></i>
                    </div>
                    <div>
                        <h1 class="font-bold text-lg text-gray-900 leading-tight">SchoolWear</h1>
                        <p class="text-xs text-gray-500">Seragam Kualitas, Prestasi Bangsa</p>
                    </div>
                </div>

                <!-- NAV MENU -->
                <nav class="space-y-1">
                    <a href="{{ route('siswa.dashboard') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition {{ request()->routeIs('siswa.dashboard') ? 'bg-brand-800 text-white shadow-sm' : 'text-gray-600 hover:bg-brand-100 hover:text-brand-800' }}">
                        <i class="fa-solid fa-house w-5"></i>
                        <span>Beranda</span>
                    </a>

                    <a href="{{ route('siswa.products.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition {{ request()->routeIs('siswa.products.*') ? 'bg-brand-800 text-white shadow-sm' : 'text-gray-600 hover:bg-brand-100 hover:text-brand-800' }}">
                        <i class="fa-solid fa-store w-5"></i>
                        <span>Katalog Produk</span>
                    </a>

                    <a href="{{ route('siswa.orders.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition {{ request()->routeIs('siswa.orders.index') ? 'bg-brand-800 text-white shadow-sm' : 'text-gray-600 hover:bg-brand-100 hover:text-brand-800' }}">
                        <i class="fa-solid fa-clipboard-list w-5"></i>
                        <span>Pesanan Saya</span>
                    </a>

                    <a href="{{ route('siswa.orders.history') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition {{ request()->routeIs('siswa.orders.history') ? 'bg-brand-800 text-white shadow-sm' : 'text-gray-600 hover:bg-brand-100 hover:text-brand-800' }}">
                        <i class="fa-solid fa-receipt w-5"></i>
                        <span>Riwayat Transaksi</span>
                    </a>

                    <a href="{{ route('siswa.cart.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition {{ request()->routeIs('siswa.cart.*') ? 'bg-brand-800 text-white shadow-sm' : 'text-gray-600 hover:bg-brand-100 hover:text-brand-800' }}">
                        <i class="fa-solid fa-cart-shopping w-5"></i>
                        <span>Keranjang</span>
                    </a>

                    <a href="{{ route('siswa.profile.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition {{ request()->routeIs('siswa.profile.*') ? 'bg-brand-800 text-white shadow-sm' : 'text-gray-600 hover:bg-brand-100 hover:text-brand-800' }}">
                        <i class="fa-solid fa-user w-5"></i>
                        <span>Profil Saya</span>
                    </a>

                    <form action="{{ route('logout') }}" method="POST" class="pt-2">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm text-gray-600 hover:bg-red-50 hover:text-red-600 transition">
                            <i class="fa-solid fa-right-from-bracket w-5"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </nav>
            </div>

            <!-- BANNER PROMO SIDEBAR BOTTOM -->
            <div class="bg-gradient-to-br from-amber-100 to-orange-100 p-4 rounded-2xl border border-amber-200/60 mt-6 relative overflow-hidden">
                <div class="flex items-center gap-3 relative z-10">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm text-brand-800">
                        <i class="fa-solid fa-user-graduate text-xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-xs text-brand-900">Tampil Rapi, Percaya Diri Setiap Hari!</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <main class="flex-1 flex flex-col min-w-0">
            <!-- HEADER BAR TOP (HANYA HALO USER & FOTO) -->
            <header class="bg-white/80 backdrop-blur border-b border-brand-200 px-8 py-4 flex items-center justify-end sticky top-0 z-20">
                <div class="flex items-center gap-3">
                    <img src="{{ !empty(auth()->user()->foto) ? asset('storage/'.auth()->user()->foto).'?v='.time() : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name ?? 'Siswa').'&background=4A2E1B&color=fff' }}" 
                         alt="Avatar" 
                         class="w-9 h-9 rounded-full object-cover border border-gray-200">
                    <span class="text-sm font-semibold text-gray-800">Halo, {{ auth()->user()->name ?? 'Siswa' }} 👋</span>
                </div>
            </header>

            <!-- PAGE CONTENT -->
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