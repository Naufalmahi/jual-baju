@extends('layouts.kasir')

@section('content')
<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Kelola Pesanan</h2>
        <p class="text-gray-500 text-sm">Kelola dan proses pesanan yang dibuat siswa.</p>
    </div>

    <!-- FILTER STATUS TABS TANPA DIBATALKAN -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-2">
            <a href="{{ route('kasir.orders.index') }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition {{ !request('status') ? 'bg-brand-200 text-brand-900 shadow-xs' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
                Semua
            </a>
            <a href="{{ route('kasir.orders.index', ['status' => 'Menunggu Pembayaran']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition {{ request('status') === 'Menunggu Pembayaran' ? 'bg-brand-200 text-brand-900 shadow-xs' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
                Menunggu Pembayaran
            </a>
            <a href="{{ route('kasir.orders.index', ['status' => 'Siap Diambil']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition {{ request('status') === 'Siap Diambil' ? 'bg-brand-200 text-brand-900 shadow-xs' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
                Siap Diambil
            </a>
            <a href="{{ route('kasir.orders.index', ['status' => 'Selesai']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-bold transition {{ request('status') === 'Selesai' ? 'bg-brand-200 text-brand-900 shadow-xs' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
                Selesai
            </a>
        </div>

        <form action="{{ route('kasir.orders.index') }}" method="GET" class="w-full md:w-80 relative">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari nama siswa atau no. pesanan..." 
                   class="w-full pl-4 pr-10 py-2 bg-white border border-gray-200 rounded-xl text-xs focus:ring-brand-800">
            <button type="submit" class="absolute right-3 top-2.5 text-gray-400"><i class="fa-solid fa-search"></i></button>
        </form>
    </div>

    <!-- TABEL KELOLA PESANAN -->
    <div class="bg-white rounded-2xl border border-brand-200 overflow-hidden shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-600">
                <tr>
                    <th class="p-4">No. Pesanan</th>
                    <th class="p-4">Siswa</th>
                    <th class="p-4">Kelas</th>
                    <th class="p-4">Total</th>
                    <th class="p-4">Metode</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                    <tr class="hover:bg-brand-50">
                        <td class="p-4 font-bold text-gray-900">#{{ $order->order_code }}</td>
                        <td class="p-4 font-semibold text-gray-800">{{ $order->user->name ?? 'Siswa' }}</td>
                        <td class="p-4 text-gray-500 text-xs">{{ $order->user->kelas->name ?? 'X RPL 1' }}</td>
                        <td class="p-4 font-bold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-bold uppercase 
                                {{ strtolower($order->payment_method) === 'qris' ? 'bg-purple-100 text-purple-800' : 'bg-emerald-100 text-emerald-800' }}">
                                <i class="fa-solid {{ strtolower($order->payment_method) === 'qris' ? 'fa-qrcode' : 'fa-money-bill' }} mr-1"></i>
                                {{ $order->payment_method }}
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="px-3 py-1 rounded-lg text-xs font-bold 
                                {{ $order->status === 'Siap Diambil' ? 'bg-blue-100 text-blue-800' : ($order->status === 'Selesai' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800') }}">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="p-4 flex items-center justify-center gap-2">
                            @if(in_array($order->status, ['Siap Diambil', 'Menunggu Pembayaran']))
                                <form action="{{ route('kasir.orders.complete', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-3 py-1.5 bg-emerald-100 text-emerald-800 font-bold text-xs rounded-xl hover:bg-emerald-200 transition flex items-center gap-1">
                                        <i class="fa-solid fa-check"></i>
                                        <span>Selesai</span>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-gray-400 font-medium">Belum ada pesanan masuk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection