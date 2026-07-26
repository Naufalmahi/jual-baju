@extends('layouts.siswa')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Riwayat Transaksi</h2>
    <p class="text-gray-500 text-sm">Daftar transaksi baju seragam yang telah selesai kamu ambil</p>
</div>

<div class="space-y-4">
    @forelse($orders as $order)
        <div class="bg-white p-6 rounded-2xl border border-brand-200 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="font-bold text-brand-800 text-base">#{{ $order->order_code }}</span>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">
                        {{ $order->status }}
                    </span>
                    <span class="text-xs text-gray-400 uppercase font-semibold">({{ $order->payment_method }})</span>
                </div>
                <div class="text-xs text-gray-500 space-y-1">
                    <p>Tanggal Selesai: {{ $order->updated_at ? $order->updated_at->translatedFormat('d F Y, H:i') : '-' }}</p>
                    <p>Total Transaksi: <span class="font-bold text-gray-900 text-sm">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-xs text-emerald-700 font-semibold bg-emerald-50 px-3 py-2 rounded-xl border border-emerald-200">
                    <i class="fa-solid fa-circle-check mr-1"></i> Baju Sudah Diambil
                </span>
            </div>
        </div>
    @empty
        <div class="bg-white p-12 rounded-2xl border border-brand-200 text-center">
            <i class="fa-solid fa-receipt text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500 font-medium">Belum ada riwayat transaksi yang selesai.</p>
        </div>
    @endforelse
</div>
@endsection