@extends('layouts.superadmin')

@section('title', 'Pemeliharaan & Backup Database')
@section('page_title', 'Pemeliharaan Database')

@section('content')

<!-- NOTIFIKASI SUKSES / ERROR -->
@if(session('success'))
    <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i class="fas fa-exclamation-triangle"></i>
            <span>{{ session('error') }}</span>
        </div>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

    <!-- CARD 1: BACKUP DATABASE -->
    <div class="bg-white p-6 rounded-xl shadow border-t-4 border-blue-500 flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-xl">
                    <i class="fas fa-database"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-base">Backup DB</h3>
                    <p class="text-xs text-gray-500">Unduh cadangan data</p>
                </div>
            </div>
            <p class="text-xs text-gray-600 mb-6 leading-relaxed">
                Unduh seluruh struktur data beserta isinya dalam bentuk file <code class="bg-gray-100 px-1 py-0.5 rounded text-red-500 font-mono">.sql</code> untuk cadangan.
            </p>
        </div>

        <form action="{{ route('superadmin.database.backup') }}" method="POST">
            @csrf
            <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-lg transition flex items-center justify-center gap-2">
                <i class="fas fa-download"></i> Download SQL
            </button>
        </form>
    </div>

    <!-- CARD 2: RESTORE DATABASE -->
    <div class="bg-white p-6 rounded-xl shadow border-t-4 border-amber-500 flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center text-xl">
                    <i class="fas fa-file-upload"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-base">Restore DB</h3>
                    <p class="text-xs text-gray-500">Upload file backup</p>
                </div>
            </div>
            <p class="text-xs text-gray-600 mb-4 leading-relaxed">
                Kembalikan data dari file <code class="bg-gray-100 px-1 py-0.5 rounded text-amber-600 font-mono">.sql</code> hasil backup jika data terhapus.
            </p>
        </div>

        <form action="{{ route('superadmin.database.restore') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Apakah Anda yakin ingin menimpa database saat ini dengan data dari file backup ini?')">
            @csrf
            <div class="mb-3">
                <input type="file" name="backup_file" accept=".sql" required class="block w-full text-[11px] text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 cursor-pointer border rounded-lg p-1">
            </div>
            <button type="submit" class="w-full py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-lg transition flex items-center justify-center gap-2">
                <i class="fas fa-upload"></i> Restore Data
            </button>
        </form>
    </div>

    <!-- CARD 3: CLEAR SYSTEM CACHE -->
    <div class="bg-white p-6 rounded-xl shadow border-t-4 border-emerald-500 flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center text-xl">
                    <i class="fas fa-broom"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-base">Clear Cache</h3>
                    <p class="text-xs text-gray-500">Optimalkan performa</p>
                </div>
            </div>
            <p class="text-xs text-gray-600 mb-6 leading-relaxed">
                Bersihkan cache route, config, dan tampilan Blade untuk memperbarui perubahan sistem aplikasi.
            </p>
        </div>
        <form action="{{ route('superadmin.database.clear-cache') }}" method="POST">
            @csrf
            <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg transition flex items-center justify-center gap-2">
                <i class="fas fa-sync-alt"></i> Bersihkan Cache
            </button>
        </form>
    </div>

    <!-- CARD 4: RESET TRANSAKSI LAMA -->
    <div class="bg-white p-6 rounded-xl shadow border-t-4 border-red-500 flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-red-100 text-red-600 rounded-lg flex items-center justify-center text-xl">
                    <i class="fas fa-trash-alt"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-base">Reset Transaksi</h3>
                    <p class="text-xs text-gray-500">Pergantian Tahun</p>
                </div>
            </div>
            <p class="text-xs text-gray-600 mb-6 leading-relaxed">
                Menghapus seluruh riwayat transaksi untuk dikosongkan. <span class="text-red-600 font-bold">Aksi tidak bisa dibatalkan!</span>
            </p>
        </div>

        <!-- Button Modal Trigger -->
        <button type="button" onclick="toggleModal('modalReset')" class="w-full py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-lg transition flex items-center justify-center gap-2">
            <i class="fas fa-exclamation-triangle"></i> Reset Transaksi
        </button>
    </div>

</div>

<!-- MODAL KONFIRMASI RESET DATA -->
<div id="modalReset" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-6 shadow-2xl">
        <div class="text-center mb-4">
            <div class="w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-2">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Konfirmasi Reset Data</h3>
            <p class="text-xs text-gray-500 mt-1">Aksi ini akan menghapus semua riwayat transaksi kasir secara permanen.</p>
        </div>

        <form action="{{ route('superadmin.database.reset-transactions') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-700 mb-1">
                    Ketik <span class="text-red-600 font-mono">HAPUS TRANSAKSI</span> untuk melanjutkan:
                </label>
                <input type="text" name="confirm_text" class="w-full border text-xs p-2.5 rounded-lg focus:ring-red-500 focus:border-red-500 font-mono" placeholder="HAPUS TRANSAKSI" required autocomplete="off">
            </div>

            <div class="flex gap-2">
                <button type="button" onclick="toggleModal('modalReset')" class="w-1/2 py-2 bg-gray-200 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-300 transition">
                    Batal
                </button>
                <button type="submit" class="w-1/2 py-2 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition">
                    Ya, Reset Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.classList.toggle('hidden');
    }
</script>

@endsection