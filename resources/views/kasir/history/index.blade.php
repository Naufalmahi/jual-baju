@extends('layouts.kasir')

@section('content')
<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Riwayat Transaksi</h2>
        <p class="text-gray-500 text-sm">Daftar transaksi yang sudah selesai</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- TABEL UTAMA SISI KIRI -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-brand-200 overflow-hidden shadow-sm">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-600">
                    <tr>
                        <th class="p-4">No. Pesanan</th>
                        <th class="p-4">Siswa</th>
                        <th class="p-4">Tanggal</th>
                        <th class="p-4">Total</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($orders as $order)
                        <tr class="hover:bg-brand-50 {{ isset($selectedOrder) && $selectedOrder->id == $order->id ? 'bg-brand-100/50' : '' }}">
                            <td class="p-4 font-bold text-gray-900">{{ $order->order_code }}</td>
                            <td class="p-4 font-semibold text-gray-800">{{ $order->user->name ?? 'Siswa' }}</td>
                            <td class="p-4 text-xs text-gray-500">{{ $order->updated_at->format('d M Y H:i') }}</td>
                            <td class="p-4 font-bold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="p-4 flex items-center justify-center gap-2">
                                <a href="{{ route('kasir.history.index', ['detail_id' => $order->id]) }}" class="px-3 py-1.5 bg-brand-100 text-brand-800 font-bold text-xs rounded-xl hover:bg-brand-200">
                                    Detail
                                </a>
                                <a href="{{ route('kasir.orders.receipt', $order->id) }}" target="_blank" class="px-3 py-1.5 bg-brand-800 text-white font-bold text-xs rounded-xl hover:bg-brand-900 flex items-center gap-1">
                                    <i class="fa-solid fa-print"></i>
                                    <span>Cetak Struk</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- PANEL STRUK KANAN -->
        @if($selectedOrder)
            <div class="bg-white p-6 rounded-2xl border border-brand-200 shadow-sm h-fit space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-md">Selesai</span>
                    <h3 class="font-extrabold text-base text-gray-900">{{ $selectedOrder->order_code }}</h3>
                </div>

                <div class="text-xs space-y-2 text-gray-600">
                    <p><span class="text-gray-400">Siswa:</span> <strong class="text-gray-800">{{ $selectedOrder->user->name ?? '-' }}</strong></p>
                    <p><span class="text-gray-400">Metode:</span> <strong class="text-gray-800 uppercase">{{ $selectedOrder->payment_method }}</strong></p>
                    <p><span class="text-gray-400">Tanggal:</span> <strong class="text-gray-800">{{ $selectedOrder->updated_at->format('d M Y H:i') }}</strong></p>
                </div>

                <div class="border-t border-gray-100 pt-3">
                    <h4 class="font-bold text-xs text-gray-700 mb-2">Daftar Produk</h4>
                    <div class="space-y-2 text-xs">
                        @foreach($selectedOrder->items as $item)
                            <div class="flex justify-between">
                                <span>{{ $item->product->name ?? 'Baju' }} ({{ $item->size }}) x{{ $item->quantity }}</span>
                                <span class="font-bold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-3 space-y-1 text-xs">
                    <div class="flex justify-between font-extrabold text-sm text-gray-900">
                        <span>Total</span>
                        <span class="text-brand-800">Rp {{ number_format($selectedOrder->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <a href="{{ route('kasir.orders.receipt', $selectedOrder->id) }}" target="_blank" class="w-full block text-center py-3 bg-brand-800 text-white font-bold rounded-xl text-xs hover:bg-brand-900 transition">
                    <i class="fa-solid fa-print mr-1"></i> Cetak Struk
                </a>
            </div>
        @endif
    </div>
</div>
@endsection