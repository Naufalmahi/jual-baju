@extends('layouts.superadmin')

@section('title', 'Master Kelas')
@section('page_title', 'Kelola Data Kelas & Jurusan')

@section('content')
<div class="row g-4">
    <!-- Form Tambah Kelas -->
    <div class="col-lg-4" data-aos="fade-up">
        <div class="card-custom h-100">
            <div class="card-body-custom">
                <h6 class="fw-bold mb-4">Tambah Kelas Baru</h6>
                <form action="{{ route('superadmin.classes.store') }}" method="POST">
                    @csrf
                    <div class="mb-3"><label class="form-label-custom">Nama Kelas</label><input type="text" name="class_name" placeholder="Contoh: XI RPL 1" required class="form-control-custom w-100"></div>
                    <div class="mb-3">
                        <label class="form-label-custom">Tingkat</label>
                        <select name="grade" required class="form-select-custom w-100">
                            <option value="X">X</option>
                            <option value="XI">XI</option>
                            <option value="XII">XII</option>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label-custom">Jurusan</label><input type="text" name="major" placeholder="Contoh: Rekayasa Perangkat Lunak" required class="form-control-custom w-100"></div>
                    <button type="submit" class="btn-primary-custom w-100 justify-center">Simpan Kelas</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Kelas -->
    <div class="col-lg-8" data-aos="fade-up">
        <div class="card-custom">
            <div class="card-body-custom">
                <div class="table-responsive">
                    <table class="table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Nama Kelas</th>
                                <th>Tingkat</th>
                                <th>Jurusan</th>
                                <th>Siswa</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($classes as $class)
                            <tr>
                                <td class="fw-semibold">{{ $class->class_name }}</td>
                                <td>{{ $class->grade }}</td>
                                <td>{{ $class->major }}</td>
                                <td>{{ $class->students_count }} Siswa</td>
                                <td class="text-center">
                                    <form action="{{ route('superadmin.classes.destroy', $class->id) }}" method="POST" onsubmit="return confirm('Hapus kelas ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-danger-custom btn-sm-custom">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center" style="padding:32px;color:var(--neutral-400)">Belum ada data kelas.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
