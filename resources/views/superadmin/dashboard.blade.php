@extends('layouts.superadmin')

@section('title', 'Dashboard - Super Admin')
@section('page_title', 'Dashboard Overview')

@section('content')
<!-- NOTIFIKASI BERHASIL -->
@if(session('success'))
    <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
        {{ session('success') }}
    </div>
@endif

<!-- KARTU STATISTIK -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow border-l-4 border-blue-500 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase">Total Admin</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalAdmin }}</p>
        </div>
        <i class="fas fa-user-shield text-blue-500 text-3xl"></i>
    </div>

    <div class="bg-white p-6 rounded-xl shadow border-l-4 border-green-500 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase">Total Kasir</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalKasir }}</p>
        </div>
        <i class="fas fa-cash-register text-green-500 text-3xl"></i>
    </div>

    <div class="bg-white p-6 rounded-xl shadow border-l-4 border-purple-500 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase">Total Siswa</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalSiswa }}</p>
        </div>
        <i class="fas fa-user-graduate text-purple-500 text-3xl"></i>
    </div>

    <div class="bg-white p-6 rounded-xl shadow border-l-4 border-amber-500 flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-gray-500 uppercase">Total Kelas</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalKelas }}</p>
        </div>
        <i class="fas fa-door-open text-amber-500 text-3xl"></i>
    </div>
</div>

<!-- PANEL KONTROL MAINTENANCE SYSTEM (KIRI: ADMIN | TENGAH: KASIR | KANAN: SISWA) -->
<div class="bg-white p-6 rounded-xl shadow mb-8">
    <h2 class="text-lg font-bold text-gray-800 mb-1">Pengaturan Mode Maintenance System</h2>
    <p class="text-gray-500 text-sm mb-6">Kelola mode maintenance berdasarkan role dan modul spesifik aplikasi.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- ================= KANAN 1: MODUL & ROLE ADMIN TOKO (BAGIAN KIRI) ================= -->
        <div class="flex flex-col gap-4 border-r pr-0 md:pr-6 border-gray-200">
            <div class="flex items-center gap-2 pb-2 border-b border-gray-200">
                <i class="fas fa-user-shield text-blue-500"></i>
                <h3 class="font-bold text-gray-800 uppercase text-xs tracking-wider">Akses Admin Toko</h3>
            </div>

            <!-- Role Akses Utama Admin -->
            <div class="p-4 border rounded-lg bg-blue-50/50 border-blue-100 flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-gray-800 text-sm">Keseluruhan Admin</h4>
                    <p class="text-xs text-gray-500 mt-1">
                        Status: 
                        @if(($mAdmin ?? '0') == '1')
                            <span class="text-red-600 font-bold">Maintenance (503)</span>
                        @else
                            <span class="text-green-600 font-bold">Normal</span>
                        @endif
                    </p>
                </div>
                <form action="{{ route('superadmin.toggle-maintenance') }}" method="POST">
                    @csrf
                    <input type="hidden" name="target" value="admin">
                    <input type="hidden" name="status" value="{{ ($mAdmin ?? '0') == '1' ? '0' : '1' }}">
                    <button type="submit" class="px-3 py-1.5 text-xs font-bold text-white rounded-lg transition {{ ($mAdmin ?? '0') == '1' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }}">
                        {{ ($mAdmin ?? '0') == '1' ? 'Matikan' : 'Aktifkan' }}
                    </button>
                </form>
            </div>

            <p class="text-[11px] font-bold text-gray-400 uppercase mt-2">Modul Spesifik Admin:</p>

            <!-- Modul Categories -->
            <div class="p-3 border rounded-lg bg-gray-50 flex items-center justify-between">
                <div>
                    <h5 class="font-medium text-gray-700 text-xs">Modul Kategori Produk</h5>
                    <p class="text-[11px] text-gray-500">
                        @if(($mCategories ?? '0') == '1')
                            <span class="text-red-600 font-bold">Maintenance</span>
                        @else
                            <span class="text-green-600 font-bold">Normal</span>
                        @endif
                    </p>
                </div>
                <form action="{{ route('superadmin.toggle-maintenance') }}" method="POST">
                    @csrf
                    <input type="hidden" name="target" value="categories">
                    <input type="hidden" name="status" value="{{ ($mCategories ?? '0') == '1' ? '0' : '1' }}">
                    <button type="submit" class="px-2.5 py-1 text-xs font-semibold text-white rounded transition {{ ($mCategories ?? '0') == '1' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }}">
                        {{ ($mCategories ?? '0') == '1' ? 'Matikan' : 'Aktifkan' }}
                    </button>
                </form>
            </div>

            <!-- Modul Products -->
            <div class="p-3 border rounded-lg bg-gray-50 flex items-center justify-between">
                <div>
                    <h5 class="font-medium text-gray-700 text-xs">Modul Produk / Barang</h5>
                    <p class="text-[11px] text-gray-500">
                        @if(($mProducts ?? '0') == '1')
                            <span class="text-red-600 font-bold">Maintenance</span>
                        @else
                            <span class="text-green-600 font-bold">Normal</span>
                        @endif
                    </p>
                </div>
                <form action="{{ route('superadmin.toggle-maintenance') }}" method="POST">
                    @csrf
                    <input type="hidden" name="target" value="products">
                    <input type="hidden" name="status" value="{{ ($mProducts ?? '0') == '1' ? '0' : '1' }}">
                    <button type="submit" class="px-2.5 py-1 text-xs font-semibold text-white rounded transition {{ ($mProducts ?? '0') == '1' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }}">
                        {{ ($mProducts ?? '0') == '1' ? 'Matikan' : 'Aktifkan' }}
                    </button>
                </form>
            </div>

            <!-- Modul Classes -->
            <div class="p-3 border rounded-lg bg-gray-50 flex items-center justify-between">
                <div>
                    <h5 class="font-medium text-gray-700 text-xs">Modul Kelas & Jurusan</h5>
                    <p class="text-[11px] text-gray-500">
                        @if(($mClasses ?? '0') == '1')
                            <span class="text-red-600 font-bold">Maintenance</span>
                        @else
                            <span class="text-green-600 font-bold">Normal</span>
                        @endif
                    </p>
                </div>
                <form action="{{ route('superadmin.toggle-maintenance') }}" method="POST">
                    @csrf
                    <input type="hidden" name="target" value="classes">
                    <input type="hidden" name="status" value="{{ ($mClasses ?? '0') == '1' ? '0' : '1' }}">
                    <button type="submit" class="px-2.5 py-1 text-xs font-semibold text-white rounded transition {{ ($mClasses ?? '0') == '1' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }}">
                        {{ ($mClasses ?? '0') == '1' ? 'Matikan' : 'Aktifkan' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- ================= KOLOM 2: MODUL & ROLE KASIR (BAGIAN TENGAH) ================= -->
        <div class="flex flex-col gap-4 border-r pr-0 md:pr-6 border-gray-200">
            <div class="flex items-center gap-2 pb-2 border-b border-gray-200">
                <i class="fas fa-cash-register text-green-500"></i>
                <h3 class="font-bold text-gray-800 uppercase text-xs tracking-wider">Akses Halaman Kasir</h3>
            </div>

            <!-- Role Akses Utama Kasir -->
            <div class="p-4 border rounded-lg bg-green-50/50 border-green-100 flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-gray-800 text-sm">Keseluruhan Kasir</h4>
                    <p class="text-xs text-gray-500 mt-1">
                        Status: 
                        @if(($mKasir ?? '0') == '1')
                            <span class="text-red-600 font-bold">Maintenance (503)</span>
                        @else
                            <span class="text-green-600 font-bold">Normal</span>
                        @endif
                    </p>
                </div>
                <form action="{{ route('superadmin.toggle-maintenance') }}" method="POST">
                    @csrf
                    <input type="hidden" name="target" value="kasir">
                    <input type="hidden" name="status" value="{{ ($mKasir ?? '0') == '1' ? '0' : '1' }}">
                    <button type="submit" class="px-3 py-1.5 text-xs font-bold text-white rounded-lg transition {{ ($mKasir ?? '0') == '1' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }}">
                        {{ ($mKasir ?? '0') == '1' ? 'Matikan' : 'Aktifkan' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- ================= KOLOM 3: MODUL & ROLE SISWA (BAGIAN KANAN) ================= -->
        <div class="flex flex-col gap-4">
            <div class="flex items-center gap-2 pb-2 border-b border-gray-200">
                <i class="fas fa-user-graduate text-purple-500"></i>
                <h3 class="font-bold text-gray-800 uppercase text-xs tracking-wider">Akses Halaman Siswa</h3>
            </div>

            <!-- Role Akses Utama Siswa -->
            <div class="p-4 border rounded-lg bg-purple-50/50 border-purple-100 flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-gray-800 text-sm">Keseluruhan Siswa</h4>
                    <p class="text-xs text-gray-500 mt-1">
                        Status: 
                        @if(($mSiswa ?? '0') == '1')
                            <span class="text-red-600 font-bold">Maintenance (503)</span>
                        @else
                            <span class="text-green-600 font-bold">Normal</span>
                        @endif
                    </p>
                </div>
                <form action="{{ route('superadmin.toggle-maintenance') }}" method="POST">
                    @csrf
                    <input type="hidden" name="target" value="siswa">
                    <input type="hidden" name="status" value="{{ ($mSiswa ?? '0') == '1' ? '0' : '1' }}">
                    <button type="submit" class="px-3 py-1.5 text-xs font-bold text-white rounded-lg transition {{ ($mSiswa ?? '0') == '1' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }}">
                        {{ ($mSiswa ?? '0') == '1' ? 'Matikan' : 'Aktifkan' }}
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<div class="bg-white p-6 rounded-xl shadow">
    <h2 class="text-lg font-bold text-gray-800 mb-2">Selamat Datang di Panel Super Admin!</h2>
    <p class="text-gray-600 text-sm">Gunakan menu di sebelah kiri untuk mengelola akun petugas, meng-import data siswa, atau mengatur master kelas dan jurusan sekolah.</p>
</div>
@endsection