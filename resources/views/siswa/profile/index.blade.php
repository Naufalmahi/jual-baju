@extends('layouts.siswa')

@section('content')
@php
    $photoUrl = 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=EAE5DD&color=4A2E1B&size=150';
    if (!empty($user->foto)) {
        $photoUrl = asset('storage/' . $user->foto) . '?v=' . time();
    }
@endphp

<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Profil Saya</h2>
        <p class="text-gray-500 text-sm">Kelola informasi profil dan akun kamu</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- CARD KIRI: AVATAR -->
        <div class="bg-white p-6 rounded-2xl border border-brand-200 flex flex-col items-center text-center shadow-sm">
            <div class="relative mb-4">
                <img src="{{ $photoUrl }}" 
                     alt="Foto Profil" 
                     class="w-36 h-36 rounded-full object-cover border-4 border-brand-100 shadow-sm">
                
                <button type="button" onclick="document.getElementById('photoInput').click()" 
                        class="absolute bottom-1 right-1 bg-white border border-gray-200 text-gray-700 w-10 h-10 rounded-full flex items-center justify-center shadow hover:bg-brand-100 transition"
                        title="Ubah Foto">
                    <i class="fa-solid fa-camera"></i>
                </button>
            </div>

            <!-- Form Upload Foto -->
            <form id="photoForm" action="{{ route('siswa.profile.photo') }}" method="POST" enctype="multipart/form-data" class="hidden">
                @csrf
                <input type="file" id="photoInput" name="photo" accept="image/*" onchange="document.getElementById('photoForm').submit()">
            </form>

            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $user->name }}</h3>
            <span class="px-3 py-1 bg-brand-100 text-brand-800 text-xs font-semibold rounded-full mb-4">Siswa</span>

            <div class="w-full space-y-2 text-left text-xs text-gray-600 border-t border-gray-100 pt-4 mb-6">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-id-card text-brand-800 w-4"></i>
                    <span>NISN: {{ $user->nisn_nip ?? $user->username ?? $user->nis }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-graduation-cap text-brand-800 w-4"></i>
                    <span>Kelas: {{ $user->kelas->name ?? $user->classModel->name ?? $user->kelas ?? 'Terdaftar' }}</span>
                </div>
            </div>

            <button type="button" onclick="document.getElementById('photoInput').click()" 
                    class="w-full py-2.5 px-4 bg-white border border-gray-300 text-gray-700 font-semibold rounded-xl text-sm hover:bg-brand-100 transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-camera"></i>
                <span>Ubah Foto</span>
            </button>
        </div>

        <!-- CARD KANAN: INFORMASI PRIBADI SINKRON -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-brand-200 shadow-sm">
                <div class="mb-6">
                    <h3 class="font-bold text-lg text-gray-900">Informasi Pribadi</h3>
                </div>

                <div class="divide-y divide-gray-100 text-sm">
                    <div class="py-4 flex justify-between items-center">
                        <span class="text-gray-500 font-medium">NIS / NISN</span>
                        <!-- Menggunakan nisn_nip / username sesuai DB Admin -->
                        <span class="text-gray-900 font-bold text-base">{{ $user->nisn_nip ?? $user->username ?? $user->nis }}</span>
                    </div>
                    <div class="py-4 flex justify-between items-center">
                        <span class="text-gray-500 font-medium">Nama Lengkap</span>
                        <span class="text-gray-900 font-bold text-base">{{ $user->name }}</span>
                    </div>
                    <div class="py-4 flex justify-between items-center">
                        <span class="text-gray-500 font-medium">Kelas</span>
                        <span class="text-gray-900 font-bold text-base">{{ $user->kelas->name ?? $user->classModel->name ?? $user->kelas ?? 'XI RPL 2' }}</span>
                    </div>
                </div>
            </div>

            <!-- CATATAN INFORMASI AKUN -->
            <div class="bg-amber-50 p-6 rounded-2xl border border-amber-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-100 text-amber-800 rounded-xl flex items-center justify-center text-lg flex-shrink-0">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <div>
                    <h4 class="font-bold text-amber-900 text-base">Informasi Akun Dikelola Admin</h4>
                    <p class="text-xs text-amber-800">Data akun siswa (NISN & Nama) dibuat oleh Admin Sekolah. Jika terjadi kendala data, hubungi Kasir atau Admin Koperasi.</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection