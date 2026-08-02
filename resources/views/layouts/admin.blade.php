<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Koperasi - Sekolah')</title>
    
    <!-- CSS Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Fallback Toggle Style */
        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: -260px;
                width: 260px;
                height: 100vh;
                z-index: 1050;
                transition: left 0.3s ease;
            }
            .sidebar.active {
                left: 0;
            }
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: rgba(0,0,0,0.5);
                z-index: 1040;
            }
            .sidebar-overlay.active {
                display: block;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="bi bi-shop" style="font-size:1.3rem;color:#FFC107"></i>
            <span>ADMIN KOPERASI</span>
        </div>
        <div class="sidebar-nav">
            <div class="nav-section">Menu Utama</div>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>
            <div class="nav-section">Manajemen Data</div>
            <a href="{{ route('admin.classes.index') }}" class="{{ request()->routeIs('admin.classes.*') ? 'active' : '' }}">
                <i class="bi bi-building"></i>
                <span>Data Kelas & Jurusan</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-tags-fill"></i>
                <span>Kategori Produk</span>
            </a>
            <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam-fill"></i>
                <span>Data Barang / Stok</span>
            </a>
            <div class="nav-section">Pengguna</div>
            <a href="{{ route('admin.kasir.index') }}" class="{{ request()->routeIs('admin.kasir.*') ? 'active' : '' }}">
                <i class="bi bi-laptop"></i>
                <span>Kelola Kasir</span>
            </a>
            <a href="{{ route('admin.siswa.index') }}" class="{{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
                <i class="bi bi-mortarboard-fill"></i>
                <span>Data Siswa & Impor</span>
            </a>
        </div>
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar bg-success d-flex align-items-center justify-content-center text-white fw-bold" style="font-size:.75rem">AD</div>
                <div>
                    <div class="user-name">{{ Auth::user()->name ?? 'Admin' }}</div>
                    <div class="user-role">Admin Toko</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="mt-2">
                @csrf
                <button type="submit" class="sidebar-nav" style="margin:0;padding:8px 12px;width:100%;display:flex;align-items:center;gap:8px;color:rgba(255,255,255,.6);font-size:.8rem;border:none;background:none;cursor:pointer;border-radius:8px;">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="main-content">
        <nav class="top-navbar">
            <button class="sidebar-toggle" id="sidebarToggle" type="button"><i class="bi bi-list"></i></button>
            <div class="breadcrumb-custom d-none d-md-flex">
                <span class="current">@yield('page_title', 'Dashboard')</span>
            </div>
            <div class="topbar-right">
                <span class="badge badge-success">Admin Toko</span>
                <span class="d-none d-sm-inline" style="font-size:.85rem;font-weight:600;color:var(--neutral-700)">{{ Auth::user()->name ?? 'Admin' }}</span>
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