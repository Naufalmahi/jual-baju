<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Super Admin - Sistem Sekolah')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">

    <div class="flex h-screen overflow-hidden">
        <!-- SIDEBAR SUPER ADMIN -->
        <aside class="w-64 bg-slate-900 text-white flex flex-col justify-between">
            <div>
                <div class="p-5 text-center font-bold text-lg border-b border-slate-800 tracking-wide">
                    SUPER ADMIN
                </div>
                <nav class="mt-5 px-4 space-y-2">
                    <a href="{{ route('superadmin.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-800 transition">
                        <i class="fas fa-home w-6"></i> <span>Dashboard</span>
                    </a>
                    <a href="{{ route('superadmin.users.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-800 transition">
                        <i class="fas fa-user-shield w-6"></i> <span>Kelola Admin</span>
                    </a>
                    <a href="{{ route('superadmin.classes.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-800 transition">
                        <i class="fas fa-school w-6"></i> <span>Master Kelas</span>
                    </a>
                    <a href="{{ route('superadmin.settings.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-slate-800 transition">
                        <i class="fas fa-cog w-6"></i> <span>Pengaturan</span>
                    </a>
                </nav>
            </div>
            
            <div class="p-4 border-t border-slate-800">
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
                <h1 class="text-xl font-bold text-gray-800">@yield('page_title', 'Dashboard Super Admin')</h1>
                <div class="flex items-center space-x-3">
                    <span class="text-sm font-medium text-gray-600">{{ Auth::user()->name ?? 'Super Admin' }}</span>
                    <span class="px-2.5 py-1 text-xs bg-slate-200 text-slate-800 font-bold rounded-full">Super Admin</span>
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