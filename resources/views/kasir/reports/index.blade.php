@extends('layouts.kasir')

@section('content')
<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Laporan Penjualan</h2>
        <p class="text-gray-500 text-sm">Lihat ringkasan penjualan dan transaksi dalam periode tertentu.</p>
    </div>

    <!-- FILTER PERIODE & BOTOM DOWNLOAD EXCEL -->
    <form action="{{ route('kasir.reports.index') }}" method="GET" class="bg-white p-5 rounded-2xl border border-brand-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4 mb-8">
        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Mulai Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="px-3 py-2 border rounded-xl text-xs">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="px-3 py-2 border rounded-xl text-xs">
            </div>
            <button type="submit" class="mt-4 px-4 py-2 bg-brand-800 text-white font-bold rounded-xl text-xs hover:bg-brand-900">
                Filter
            </button>
        </div>

        <a href="{{ route('kasir.reports.exportExcel', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="px-5 py-2.5 bg-amber-100 text-amber-900 font-bold rounded-xl text-xs hover:bg-amber-200 transition flex items-center gap-2">
            <i class="fa-solid fa-file-excel"></i>
            <span>Download Laporan (Excel)</span>
        </a>
    </form>

    <!-- STAT CARDS PERIODE -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-brand-200 shadow-sm">
            <p class="text-xs text-gray-500 font-semibold">Total Transaksi</p>
            <h3 class="text-2xl font-extrabold text-gray-900 mt-1">{{ $totalTransaksi }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-brand-200 shadow-sm">
            <p class="text-xs text-gray-500 font-semibold">Total Pendapatan</p>
            <h3 class="text-2xl font-extrabold text-emerald-700 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-brand-200 shadow-sm">
            <p class="text-xs text-gray-500 font-semibold">Rata-rata Transaksi</p>
            <h3 class="text-2xl font-extrabold text-brand-800 mt-1">Rp {{ number_format($rataRata, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-brand-200 shadow-sm">
            <p class="text-xs text-gray-500 font-semibold">Produk Terjual</p>
            <h3 class="text-2xl font-extrabold text-blue-700 mt-1">{{ $totalProdukTerjual }} Pcs</h3>
        </div>
    </div>

    <!-- TABEL PRODUK TERLARIS -->
    <div class="bg-white p-6 rounded-2xl border border-brand-200 shadow-sm">
        <h3 class="font-bold text-lg text-gray-900 mb-4">Produk Terlaris Periode Ini</h3>
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-xs font-bold text-gray-500 uppercase border-b">
                <tr>
                    <th class="p-3">Produk</th>
                    <th class="p-3">Jumlah Terjual</th>
                    <th class="p-3">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($topProducts as $tp)
                    <tr>
                        <td class="p-3 font-bold text-gray-900">{{ $tp->product->name ?? 'Produk' }}</td>
                        <td class="p-3 font-semibold text-blue-800">{{ $tp->total_qty }} Pcs</td>
                        <td class="p-3 font-bold text-emerald-800">Rp {{ number_format($tp->total_revenue, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="p-4 text-center text-gray-400">Belum ada transaksi di periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection