@extends('layouts.superadmin')

@section('title', 'Kelola Admin Toko')
@section('page_title', 'Kelola Akun Admin Toko / Koperasi')

@section('content')
<div class="row g-4">
    <!-- Form Tambah Admin -->
    <div class="col-lg-4" data-aos="fade-up">
        <div class="card-custom h-100">
            <div class="card-body-custom">
                <h6 class="fw-bold mb-4"><i class="bi bi-person-plus me-2" style="color:var(--primary)"></i> Buat Akun Admin Baru</h6>
                <form action="{{ route('superadmin.users.store') }}" method="POST">
                    @csrf
                    <div class="mb-3"><label class="form-label-custom">Nama Lengkap</label><input type="text" name="name" placeholder="Contoh: Pak Budi" required class="form-control-custom w-100"></div>
                    <div class="mb-3"><label class="form-label-custom">Username</label><input type="text" name="username" placeholder="Contoh: admin_budi" required class="form-control-custom w-100"></div>
                    <div class="mb-3"><label class="form-label-custom">NIP / Identitas (Opsional)</label><input type="text" name="nip" placeholder="Contoh: 198203..." class="form-control-custom w-100"></div>
                    <div class="mb-3"><label class="form-label-custom">Password</label><input type="password" name="password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" required class="form-control-custom w-100"></div>
                    <button type="submit" class="btn-primary-custom w-100 justify-center">Simpan Akun Admin</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Admin -->
    <div class="col-lg-8" data-aos="fade-up">
        <div class="card-custom">
            <div class="card-body-custom">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="fw-bold mb-0">Daftar Admin Terdaftar</h6>
                    <span class="badge badge-primary">Total: {{ $admins->count() }} Admin</span>
                </div>
                <div class="table-responsive">
                    <table class="table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Nama & NIP</th>
                                <th>Username</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($admins as $admin)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $admin->name }}</div>
                                    <div style="font-size:.7rem;color:var(--neutral-400)">NIP: {{ $admin->nisn_nip ?? '-' }}</div>
                                </td>
                                <td style="font-family:monospace;font-size:.75rem">{{ $admin->username }}</td>
                                <td><span class="badge {{ $admin->is_active ? 'badge-success' : 'badge-danger' }}">{{ $admin->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <form action="{{ route('superadmin.users.resetPassword', $admin->id) }}" method="POST" onsubmit="return confirm('Reset password ke default?')">
                                            @csrf
                                            <button type="submit" class="btn-warning-custom btn-sm-custom"><i class="bi bi-key"></i> Reset</button>
                                        </form>
                                        <form action="{{ route('superadmin.users.toggleStatus', $admin->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn-outline-custom btn-sm-custom">{{ $admin->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center" style="padding:32px;color:var(--neutral-400)">Belum ada akun Admin.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
