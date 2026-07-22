<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kasir - Toko Sekolah')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">

    <div class="flex h-screen overflow-hidden">
        <!-- SIDEBAR KASIR -->
        <aside class="w-64 bg-blue-900 text-white flex flex-col justify-between">
            <div>
                <div class="p-5 text-center font-bold text-lg border-b border-blue-800 tracking-wide">
                    PORTAL KASIR
                </div>
                <nav class="mt-5 px-4 space-y-2">
                    <a href="#" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-800 transition">
                        <i class="fas fa-cash-register w-6"></i> <span>Transaksi Baru</span>
                    </a>
                    <a href="#" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-800 transition">
                        <i class="fas fa-history w-6"></i> <span>Riwayat Transaksi</span>
                    </a>
                </nav>
            </div>
            
            <div class="p-4 border-t border-blue-800">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg text-sm font-semibold transition">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col overflow-y-auto">
            <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-800">@yield('page_title', 'Kasir')</h1>
                <div class="flex items-center space-x-3">
                    <span class="text-sm font-medium text-gray-600">{{ Auth::user()->name ?? 'Kasir' }}</span>
                    <span class="px-2.5 py-1 text-xs bg-blue-100 text-blue-800 font-bold rounded-full">Kasir</span>
                </div>
            </header>

            <main class="p-6">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>