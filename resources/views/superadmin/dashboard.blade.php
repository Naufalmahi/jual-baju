@extends('layouts.superadmin')

@section('title', 'Dashboard - Super Admin')
@section('page_title', 'Dashboard Overview')

@section('content')
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

<div class="bg-white p-6 rounded-xl shadow">
    <h2 class="text-lg font-bold text-gray-800 mb-2">Selamat Datang di Panel Super Admin!</h2>
    <p class="text-gray-600 text-sm">Gunakan menu di sebelah kiri untuk mengelola akun petugas, meng-import data siswa, atau mengatur master kelas dan jurusan sekolah.</p>
</div>
@endsection