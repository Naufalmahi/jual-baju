<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SchoolWear - Seragam Kualitas, Prestasi Bangsa')</title>

    <!-- CSS Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Overlay Styles */
        .sidebar-overlay {
            display: none !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            background: rgba(0, 0, 0, 0.5) !important;
            z-index: 9998 !important; /* Di bawah sidebar */
        }

        .sidebar-overlay.active {
            display: block !important;
        }

        /* Mobile Sidebar Styles */
        @media (max-width: 991.98px) {
            aside.sidebar,
            .sidebar {
                position: fixed !important;
                top: 0 !important;
                left: -280px !important; /* Tersembunyi di kiri */
                width: 260px !important;
                height: 100vh !important;
                z-index: 9999 !important; /* WAJIB di atas overlay */
                transition: left 0.3s ease-in-out !important;
                background-color: #1e293b !important; /* Sesuaikan warna latar sidebar kamu */
                box-shadow: 4px 0 15px rgba(0,0,0,0.3) !important;
                display: flex !important;
                flex-direction: column !important;
            }

            /* Saat Aktif -> Muncul ke layar */
            aside.sidebar.active,
            .sidebar.active {
                left: 0 !important;
            }

            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="bi bi-bag-fill" style="font-size:1.3rem;color:#FFC107"></i>
            <span>SchoolWear</span>
        </div>
        <div class="sidebar-nav">
            <div class="nav-section">Menu Utama</div>
            <a href="{{ route('siswa.dashboard') }}" class="{{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                <i class="bi bi-house-fill"></i>
                <span>Beranda</span>
            </a>
            <a href="{{ route('siswa.products.index') }}" class="{{ request()->routeIs('siswa.products.*') ? 'active' : '' }}">
                <i class="bi bi-shop"></i>
                <span>Katalog Produk</span>
            </a>
            <div class="nav-section">Transaksi</div>
            <a href="{{ route('siswa.cart.index') }}" class="{{ request()->routeIs('siswa.cart.*') ? 'active' : '' }}">
                <i class="bi bi-cart-fill"></i>
                <span>Keranjang</span>
            </a>
            <a href="{{ route('siswa.orders.index') }}" class="{{ request()->routeIs('siswa.orders.index') ? 'active' : '' }}">
                <i class="bi bi-clipboard-check-fill"></i>
                <span>Pesanan Saya</span>
            </a>
            <a href="{{ route('siswa.orders.history') }}" class="{{ request()->routeIs('siswa.orders.history') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i>
                <span>Riwayat Transaksi</span>
            </a>
            <div class="nav-section">Akun</div>
            <a href="{{ route('siswa.profile.index') }}" class="{{ request()->routeIs('siswa.profile.*') ? 'active' : '' }}">
                <i class="bi bi-person-fill"></i>
                <span>Profil Saya</span>
            </a>
            <form action="{{ route('logout') }}" method="POST" class="mt-1">
                @csrf
                <button type="submit" style="margin:0;padding:10px 20px;width:calc(100% - 20px);display:flex;align-items:center;gap:12px;color:rgba(255,255,255,.7);font-size:.85rem;border:none;background:none;cursor:pointer;border-radius:8px;font-weight:500;">
                    <i class="bi bi-box-arrow-right" style="font-size:1.1rem;width:20px;text-align:center"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
        <div class="sidebar-footer">
            <div class="user-info">
                @php $photoUrl = !empty(auth()->user()->foto) ? asset('storage/'.auth()->user()->foto).'?v='.time() : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name ?? 'Siswa').'&background=0F4C81&color=fff&size=72'; @endphp
                <img src="{{ $photoUrl }}" alt="Avatar" class="user-avatar" style="width:32px;height:32px;border-radius:50%;object-fit:cover">
                <div>
                    <div class="user-name">{{ auth()->user()->name ?? 'Siswa' }}</div>
                    <div class="user-role">Siswa</div>
                </div>
            </div>
        </div>
    </aside>

    <div class="main-content">
        <nav class="top-navbar">
            <button class="sidebar-toggle" id="sidebarToggle" type="button"><i class="bi bi-list"></i></button>
            <div class="breadcrumb-custom d-none d-md-flex">
                <span class="current">@yield('page_title', 'Beranda')</span>
            </div>
            <div class="topbar-right">
                <img src="{{ $photoUrl }}" alt="Avatar" style="width:32px;height:32px;border-radius:50%;object-fit:cover">
                <span class="d-none d-sm-inline" style="font-size:.85rem;font-weight:600;color:var(--neutral-700)">Halo, {{ auth()->user()->name ?? 'Siswa' }}</span>
            </div>
        </nav>

        <div class="page-content">
            @if(session('success'))
                <div class="alert alert-dismissible fade show d-flex align-items-center gap-2" role="alert" style="border-radius:var(--radius);border:none;border-left:4px solid var(--success);background:#d1fae5;color:#065f46;padding:12px 16px;font-size:.82rem;font-weight:500">
                    <i class="bi bi-check-circle-fill" style="font-size:1.1rem;flex-shrink:0"></i>
                    <span style="flex:1">{{ session('success') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" style="font-size:.75rem"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-dismissible fade show d-flex align-items-center gap-2" role="alert" style="border-radius:var(--radius);border:none;border-left:4px solid var(--danger);background:#fee2e2;color:#991b1b;padding:12px 16px;font-size:.82rem;font-weight:500">
                    <i class="bi bi-exclamation-triangle-fill" style="font-size:1.1rem;flex-shrink:0"></i>
                    <span style="flex:1">{{ session('error') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" style="font-size:.75rem"></button>
                </div>
            @endif
            @yield('content')
        </div>
    </div>

    <!-- JS Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Script Toggle Sidebar -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (toggleBtn && sidebar && overlay) {
                toggleBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('active');
                });

                overlay.addEventListener('click', function () {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });
            }
        });
    </script>
</body>
</html>