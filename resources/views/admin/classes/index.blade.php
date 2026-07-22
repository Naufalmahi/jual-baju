@extends('layouts.admin')

@section('title', 'Kelola Kelas & Jurusan')
@section('page_title', 'Kelola Kelas & Jurusan')

@section('content')
<div x-data="{ 
    openModal: false, 
    isEdit: false, 
    actionUrl: '{{ route('admin.classes.store') }}', 
    kelas: '', 
    jurusan: '', 
    nama_kelas: '',
    
    editClass(item) {
        this.isEdit = true;
        this.actionUrl = '{{ url('admin/classes') }}/' + item.id;
        // Sesuaikan dengan nama kolom dari database
        this.kelas = item.grade; 
        this.jurusan = item.major;
        this.nama_kelas = item.class_name;
        this.openModal = true;
    },
    
    addClass() {
        this.isEdit = false;
        this.actionUrl = '{{ route('admin.classes.store') }}';
        this.kelas = '';
        this.jurusan = '';
        this.nama_kelas = '';
        this.openModal = true;
    }
}">

    <!-- HEADER & BUTTON -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Daftar Kelas & Jurusan</h2>
        <button @click="addClass()" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2 rounded-lg text-sm transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Kelas
        </button>
    </div>

    <!-- TABEL KELAS -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-emerald-50 border-b text-emerald-900 font-bold uppercase text-xs">
                    <th class="p-4 w-12 text-center">No</th>
                    <th class="p-4">Tingkat Kelas</th>
                    <th class="p-4">Jurusan</th>
                    <th class="p-4">Nama Kelas Lengkap</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($classes as $index => $cls)
                <tr class="hover:bg-gray-50">
                    <td class="p-4 text-center font-bold text-gray-500">
                        {{ $classes->firstItem() + $index }}
                    </td>
                    <!-- PANGGIL MENGGUNAKAN NAMA KOLOM DATABASE -->
                    <td class="p-4 font-semibold text-gray-700">{{ $cls->grade }}</td>
                    <td class="p-4 font-semibold text-gray-700">{{ $cls->major }}</td>
                    <td class="p-4">
                        <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-md text-xs font-bold">
                            {{ $cls->class_name }}
                        </span>
                    </td>
                    <td class="p-4 text-center space-x-2">
                        <!-- EDIT BUTTON -->
                        <button @click="editClass({{ json_encode($cls) }})" class="px-3 py-1 bg-amber-500 text-white rounded hover:bg-amber-600 text-xs font-bold transition">
                            <i class="fas fa-edit"></i> Edit
                        </button>

                        <!-- HAPUS BUTTON -->
                        <form action="{{ route('admin.classes.destroy', $cls->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data kelas ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-xs font-bold transition">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">Belum ada data kelas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- PAGINATION -->
        <div class="p-4 border-t">
            {{ $classes->links() }}
        </div>
    </div>

    <!-- MODAL FORM (TAMBAH / EDIT) -->
    <div x-show="openModal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center" x-cloak>
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4" x-text="isEdit ? 'Edit Kelas & Jurusan' : 'Tambah Kelas Baru'"></h3>

            <form :action="actionUrl" method="POST" class="space-y-4">
                @csrf
                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tingkat Kelas</label>
                    <!-- Karena di migration kamu pakai Enum ('X', 'XI', 'XII'), disarankan pakai select -->
                    <select name="kelas" x-model="kelas" required class="w-full p-2.5 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white">
                        <option value="">-- Pilih Tingkat --</option>
                        <option value="X">X (Sepuluh)</option>
                        <option value="XI">XI (Sebelas)</option>
                        <option value="XII">XII (Dua Belas)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Jurusan</label>
                    <input type="text" name="jurusan" x-model="jurusan" placeholder="Contoh: RPL, TKJ, AKL" required class="w-full p-2.5 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Kelas Lengkap (Opsional)</label>
                    <input type="text" name="nama_kelas" x-model="nama_kelas" placeholder="Contoh: XI RPL 1 (Kosongkan jika ingin auto)" class="w-full p-2.5 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t">
                    <button type="button" @click="openModal = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700" x-text="isEdit ? 'Update' : 'Simpan'"></button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection