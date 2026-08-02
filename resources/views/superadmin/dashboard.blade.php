@extends('layouts.superadmin')

@section('title', 'Dashboard - Super Admin')
@section('page_title', 'Dashboard Overview')

@section('content')
<!-- Stat Cards -->
<div class="row g-4 mb-5">
    <div class="col-sm-6 col-xl-3" data-aos="fade-up">
        <div class="stat-card">
            <div><div class="stat-label">Total Admin</div><div class="stat-value">{{ $totalAdmin }}</div></div>
            <div class="stat-icon stat-icon-info"><i class="bi bi-person-gear"></i></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="50">
        <div class="stat-card">
            <div><div class="stat-label">Total Kasir</div><div class="stat-value">{{ $totalKasir }}</div></div>
            <div class="stat-icon stat-icon-success"><i class="bi bi-laptop"></i></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-card">
            <div><div class="stat-label">Total Siswa</div><div class="stat-value">{{ $totalSiswa }}</div></div>
            <div class="stat-icon stat-icon-primary"><i class="bi bi-mortarboard-fill"></i></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="150">
        <div class="stat-card">
            <div><div class="stat-label">Total Kelas</div><div class="stat-value">{{ $totalKelas }}</div></div>
            <div class="stat-icon stat-icon-warning"><i class="bi bi-door-open"></i></div>
        </div>
    </div>
</div>

<!-- Maintenance Panel -->
<div class="card-custom mb-5" data-aos="fade-up">
    <div class="card-body-custom">
        <h5 class="fw-bold mb-1">Pengaturan Mode Maintenance System</h5>
        <p style="font-size:.8rem;color:var(--neutral-500);margin-bottom:20px">Kelola mode maintenance berdasarkan role dan modul spesifik aplikasi.</p>

        <div class="row g-4">
            <!-- Admin Toko -->
            <div class="col-lg-4">
                <div class="d-flex align-items-center gap-2 pb-2 mb-3" style="border-bottom:1px solid var(--neutral-200)">
                    <i class="bi bi-person-gear" style="color:var(--info)"></i>
                    <h6 class="fw-bold mb-0" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.05em">Akses Admin Toko</h6>
                </div>
                <div class="p-3 mb-2" style="border:1px solid var(--neutral-200);border-radius:var(--radius-sm);background:var(--primary-lighter)">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><span class="fw-bold" style="font-size:.8rem">Keseluruhan Admin</span><br><span style="font-size:.7rem">Status: @if(($mAdmin ?? '0') == '1')<span style="color:var(--danger);font-weight:700">Maintenance (503)</span>@else<span style="color:var(--success);font-weight:700">Normal</span>@endif</span></div>
                        <form action="{{ route('superadmin.toggle-maintenance') }}" method="POST">@csrf<input type="hidden" name="target" value="admin"><input type="hidden" name="status" value="{{ ($mAdmin ?? '0') == '1' ? '0' : '1' }}"><button type="submit" class="{{ ($mAdmin ?? '0') == '1' ? 'btn-success-custom' : 'btn-danger-custom' }} btn-sm-custom">{{ ($mAdmin ?? '0') == '1' ? 'Matikan' : 'Aktifkan' }}</button></form>
                    </div>
                </div>
                <p class="fw-bold mb-2" style="font-size:.65rem;text-transform:uppercase;color:var(--neutral-400)">Modul Spesifik Admin</p>
                @foreach(['categories' => 'Kategori Produk', 'products' => 'Produk / Barang', 'classes' => 'Kelas & Jurusan'] as $key => $label)
                    @php $val = ${'m' . ucfirst($key)} ?? '0'; @endphp
                    <div class="p-2 mb-2" style="border:1px solid var(--neutral-200);border-radius:var(--radius-sm)">
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="font-size:.75rem">{{ $label }}: @if($val == '1')<span style="color:var(--danger);font-weight:700">Maintenance</span>@else<span style="color:var(--success);font-weight:700">Normal</span>@endif</span>
                            <form action="{{ route('superadmin.toggle-maintenance') }}" method="POST" class="d-inline">@csrf<input type="hidden" name="target" value="{{ $key }}"><input type="hidden" name="status" value="{{ $val == '1' ? '0' : '1' }}"><button type="submit" class="{{ $val == '1' ? 'btn-success-custom' : 'btn-danger-custom' }} btn-sm-custom" style="padding:3px 10px;font-size:.65rem">{{ $val == '1' ? 'Matikan' : 'Aktifkan' }}</button></form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Kasir -->
            <div class="col-lg-4">
                <div class="d-flex align-items-center gap-2 pb-2 mb-3" style="border-bottom:1px solid var(--neutral-200)">
                    <i class="bi bi-laptop" style="color:var(--success)"></i>
                    <h6 class="fw-bold mb-0" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.05em">Akses Halaman Kasir</h6>
                </div>
                <div class="p-3 mb-2" style="border:1px solid var(--neutral-200);border-radius:var(--radius-sm);background:#d1fae5">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><span class="fw-bold" style="font-size:.8rem">Keseluruhan Kasir</span><br><span style="font-size:.7rem">Status: @if(($mKasir ?? '0') == '1')<span style="color:var(--danger);font-weight:700">Maintenance (503)</span>@else<span style="color:var(--success);font-weight:700">Normal</span>@endif</span></div>
                        <form action="{{ route('superadmin.toggle-maintenance') }}" method="POST">@csrf<input type="hidden" name="target" value="kasir"><input type="hidden" name="status" value="{{ ($mKasir ?? '0') == '1' ? '0' : '1' }}"><button type="submit" class="{{ ($mKasir ?? '0') == '1' ? 'btn-success-custom' : 'btn-danger-custom' }} btn-sm-custom">{{ ($mKasir ?? '0') == '1' ? 'Matikan' : 'Aktifkan' }}</button></form>
                    </div>
                </div>
            </div>

            <!-- Siswa -->
            <div class="col-lg-4">
                <div class="d-flex align-items-center gap-2 pb-2 mb-3" style="border-bottom:1px solid var(--neutral-200)">
                    <i class="bi bi-mortarboard-fill" style="color:var(--primary)"></i>
                    <h6 class="fw-bold mb-0" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.05em">Akses Halaman Siswa</h6>
                </div>
                <div class="p-3 mb-2" style="border:1px solid var(--neutral-200);border-radius:var(--radius-sm);background:var(--primary-lighter)">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><span class="fw-bold" style="font-size:.8rem">Keseluruhan Siswa</span><br><span style="font-size:.7rem">Status: @if(($mSiswa ?? '0') == '1')<span style="color:var(--danger);font-weight:700">Maintenance (503)</span>@else<span style="color:var(--success);font-weight:700">Normal</span>@endif</span></div>
                        <form action="{{ route('superadmin.toggle-maintenance') }}" method="POST">@csrf<input type="hidden" name="target" value="siswa"><input type="hidden" name="status" value="{{ ($mSiswa ?? '0') == '1' ? '0' : '1' }}"><button type="submit" class="{{ ($mSiswa ?? '0') == '1' ? 'btn-success-custom' : 'btn-danger-custom' }} btn-sm-custom">{{ ($mSiswa ?? '0') == '1' ? 'Matikan' : 'Aktifkan' }}</button></form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Welcome -->
<div class="card-custom" data-aos="fade-up">
    <div class="card-body-custom">
        <h5 class="fw-bold mb-2">Selamat Datang di Panel Super Admin!</h5>
        <p style="font-size:.85rem;color:var(--neutral-600)">Gunakan menu di sebelah kiri untuk mengelola akun petugas, meng-import data siswa, atau mengatur master kelas dan jurusan sekolah.</p>
    </div>
</div>
@endsection
