@extends('layouts.superadmin')

@section('title', 'Pengaturan Aplikasi')
@section('page_title', 'Pengaturan Sistem & Koperasi')

@section('content')

@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-800 rounded-lg shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-2 text-sm font-medium">
            <i class="fas fa-check-circle text-emerald-600 text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 text-xs">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif

<form action="{{ route('superadmin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- KOLOM KIRI & TENGAH (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- CARD 1: INFORMASI SEKOLAH & KOPERASI -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-t-4 border-indigo-600">
                <div class="flex items-center gap-3 mb-4 border-b border-gray-100 pb-3">
                    <div class="w-9 h-9 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-school text-base"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-sm">Profil Sekolah & Koperasi</h3>
                        <p class="text-[11px] text-gray-400">Identitas lembaga yang akan dicetak di laporan & struk</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Nama Koperasi / Toko Sekolah</label>
                        <input type="text" name="school_name" value="{{ $settings['school_name'] ?? '' }}" placeholder="Contoh: Koperasi Mandiri SMKN 1" class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Nomor Telepon / WA Koperasi</label>
                            <input type="text" name="school_phone" value="{{ $settings['school_phone'] ?? '' }}" placeholder="081234567890" class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Email Resmi Koperasi</label>
                            <input type="email" name="school_email" value="{{ $settings['school_email'] ?? '' }}" placeholder="koperasi@smkn1.sch.id" class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Alamat Lengkap Koperasi/Sekolah</label>
                        <textarea name="school_address" rows="2" class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none" placeholder="Jl. Raya Pendidikan No. 45...">{{ $settings['school_address'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- CARD 2: ATURAN TRANSAKSI & PEMBAYARAN (BARU) -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-t-4 border-emerald-600">
                <div class="flex items-center gap-3 mb-4 border-b border-gray-100 pb-3">
                    <div class="w-9 h-9 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-cash-register text-base"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-sm">Kebijakan Pembayaran & Kasir</h3>
                        <p class="text-[11px] text-gray-400">Aturan batasan kredit dan fitur kasir</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Batas Maksimal Kasbon / Hutang Siswa (Rp)</label>
                            <input type="number" name="max_debt_limit" value="{{ $settings['max_debt_limit'] ?? '50000' }}" placeholder="50000" class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            <p class="text-[10px] text-gray-400 mt-1">Set 0 jika tidak mengizinkan kasbon/hutang sama sekali.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Batas Minimal Warning Stok Barang</label>
                            <input type="number" name="stock_warning_limit" value="{{ $settings['stock_warning_limit'] ?? '5' }}" placeholder="5" class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            <p class="text-[10px] text-gray-400 mt-1">Sistem akan memberi peringatan jika stok di bawah angka ini.</p>
                        </div>
                    </div>

                    <!-- TOGGLE ATURAN PEMBAYARAN -->
                    <div class="pt-2 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 p-2 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="checkbox" name="enable_qris" value="1" {{ ($settings['enable_qris'] ?? '1') == '1' ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                            <div>
                                <span class="block text-xs font-bold text-gray-700">Terima QRIS / E-Wallet</span>
                                <span class="block text-[10px] text-gray-400">Tampilkan opsi QRIS saat checkout</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-2 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="checkbox" name="allow_debt" value="1" {{ ($settings['allow_debt'] ?? '0') == '1' ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                            <div>
                                <span class="block text-xs font-bold text-gray-700">Izinkan Kasbon Siswa</span>
                                <span class="block text-[10px] text-gray-400">Kasir bisa mencatat transaksi hutang</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- CARD 3: FORMAT & PESAN STRUK -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-t-4 border-amber-500">
                <div class="flex items-center gap-3 mb-4 border-b border-gray-100 pb-3">
                    <div class="w-9 h-9 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-receipt text-base"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-sm">Pengaturan Struk Cetak / Thermal</h3>
                        <p class="text-[11px] text-gray-400">Pesan penutup di bagian bawah nota cetak</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Pesan Footer Struk Baris 1</label>
                        <input type="text" name="receipt_footer_1" value="{{ $settings['receipt_footer_1'] ?? 'Terima Kasih Atas Kunjungan Anda!' }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:ring-2 focus:ring-amber-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Pesan Footer Struk Baris 2 (Ketentuan Retur)</label>
                        <input type="text" name="receipt_footer_2" value="{{ $settings['receipt_footer_2'] ?? 'Barang yang sudah dibeli tidak dapat dikembalikan.' }}" class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:ring-2 focus:ring-amber-500 focus:outline-none">
                    </div>
                </div>
            </div>

        </div>

        <!-- KOLOM KANAN (1/3) -->
        <div class="space-y-6">
            
            <!-- CARD 4: UPLOAD LOGO -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-t-4 border-blue-600">
                <div class="flex items-center gap-3 mb-4 border-b border-gray-100 pb-3">
                    <div class="w-9 h-9 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-image text-base"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-sm">Logo Sekolah / Koperasi</h3>
                        <p class="text-[11px] text-gray-400">Format PNG/JPG (Maks. 2MB)</p>
                    </div>
                </div>

                <div class="text-center mb-4">
                    @if(!empty($settings['app_logo']))
                        <img src="{{ asset('storage/' . $settings['app_logo']) }}" alt="Logo App" class="w-28 h-28 object-contain mx-auto border rounded-xl p-2 bg-gray-50 shadow-inner">
                    @else
                        <div class="w-28 h-28 bg-gray-50 text-gray-400 rounded-xl flex items-center justify-center mx-auto border-2 border-dashed border-gray-300">
                            <i class="fas fa-school text-4xl"></i>
                        </div>
                    @endif
                </div>

                <div>
                    <input type="file" name="app_logo" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-gray-200 rounded-lg p-1">
                </div>
            </div>

            <!-- CARD 5: PAJAK & POIN SISWA -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-t-4 border-purple-600">
                <div class="flex items-center gap-3 mb-4 border-b border-gray-100 pb-3">
                    <div class="w-9 h-9 bg-purple-50 text-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-coins text-base"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-sm">Pajak & Reward</h3>
                        <p class="text-[11px] text-gray-400">Konfigurasi PPN dan Poin Belanja</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Pajak Pertambahan Nilai / PPN (%)</label>
                        <div class="relative">
                            <input type="number" step="0.1" name="tax_percentage" value="{{ $settings['tax_percentage'] ?? '0' }}" placeholder="0" class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none pr-8">
                            <span class="absolute right-3 top-2.5 text-xs text-gray-400 font-bold">%</span>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1">Set 0 jika transaksi tidak dikenakan PPN.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Minimal Belanja Dapatkan 1 Poin (Rp)</label>
                        <input type="number" name="point_multiplier" value="{{ $settings['point_multiplier'] ?? '10000' }}" placeholder="10000" class="w-full border border-gray-300 rounded-lg p-2.5 text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        <p class="text-[10px] text-gray-400 mt-1">Set 0 jika fitur poin tidak diaktifkan.</p>
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                            <i class="fas fa-save"></i> Simpan Semua Pengaturan
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </div>
</form>

@endsection