@extends('layouts.siswa')

@section('title', 'Profil Saya')
@section('page_title', 'Profil Saya')

@section('content')
@php
    $photoUrl = 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=0F4C81&color=fff&size=150';
    if (!empty($user->foto)) {
        $photoUrl = asset('storage/' . $user->foto) . '?v=' . time();
    }
@endphp

<div class="mb-4" data-aos="fade-up">
    <h4 class="fw-bold" style="font-size:1.2rem">Profil Saya</h4>
    <p style="font-size:.82rem;color:var(--neutral-500)">Kelola informasi profil dan akun kamu</p>
</div>

<div class="row g-4">
    <div class="col-lg-4" data-aos="fade-up">
        <div class="card-custom text-center" style="padding:32px 24px">
            <div class="position-relative d-inline-block mb-3">
                <img src="{{ $photoUrl }}" alt="Foto Profil" style="width:110px;height:110px;border-radius:50%;object-fit:cover;border:4px solid var(--primary-lighter)">
                <button type="button" onclick="document.getElementById('photoInput').click()" style="position:absolute;bottom:2px;right:2px;width:34px;height:34px;border-radius:50%;background:var(--white);border:1px solid var(--neutral-200);display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:var(--shadow-sm);transition:var(--transition)">
                    <i class="bi bi-camera" style="font-size:.8rem;color:var(--neutral-600)"></i>
                </button>
            </div>
            <form id="photoForm" action="{{ route('siswa.profile.photo') }}" method="POST" enctype="multipart/form-data" class="d-none">@csrf<input type="file" id="photoInput" name="photo" accept="image/*" onchange="document.getElementById('photoForm').submit()"></form>
            <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
            <span class="badge-custom badge-primary mb-3">Siswa</span>
            <div class="text-start pt-3" style="border-top:1px solid var(--neutral-100);font-size:.8rem;color:var(--neutral-600)">
                <div class="mb-2"><i class="bi bi-person-badge me-2" style="color:var(--primary)"></i>NISN: {{ $user->nisn_nip ?? $user->username ?? '-' }}</div>
                <div><i class="bi bi-building me-2" style="color:var(--primary)"></i>Kelas: {{ $user->classModel->class_name ?? $user->kelas ?? '-' }}</div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="form-card mb-4" data-aos="fade-up">
            <div class="form-card-title">Informasi Pribadi</div>
            <div class="d-flex justify-content-between py-3" style="border-bottom:1px solid var(--neutral-100)">
                <span style="color:var(--neutral-500);font-size:.85rem">NIS / NISN</span>
                <span class="fw-bold" style="font-size:.9rem">{{ $user->nisn_nip ?? $user->username ?? '-' }}</span>
            </div>
            <div class="d-flex justify-content-between py-3" style="border-bottom:1px solid var(--neutral-100)">
                <span style="color:var(--neutral-500);font-size:.85rem">Nama Lengkap</span>
                <span class="fw-bold" style="font-size:.9rem">{{ $user->name }}</span>
            </div>
            <div class="d-flex justify-content-between py-3">
                <span style="color:var(--neutral-500);font-size:.85rem">Kelas</span>
                <span class="fw-bold" style="font-size:.9rem">{{ $user->classModel->class_name ?? $user->kelas ?? '-' }}</span>
            </div>
        </div>

        <div class="card-custom" data-aos="fade-up" style="border-left:4px solid var(--accent)">
            <div class="card-body-custom d-flex align-items-center gap-3">
                <div class="stat-icon stat-icon-warning flex-shrink-0"><i class="bi bi-shield-lock"></i></div>
                <div>
                    <h6 class="fw-bold mb-1" style="font-size:.88rem">Informasi Akun Dikelola Admin</h6>
                    <p style="font-size:.78rem;color:var(--neutral-600);margin:0">Data akun siswa (NISN & Nama) dibuat oleh Admin Sekolah. Hubungi Kasir atau Admin jika ada kendala.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection