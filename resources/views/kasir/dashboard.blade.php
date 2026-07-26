@extends('layouts.kasir')

@section('content')
<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Dashboard Kasir</h2>
        <p class="text-gray-500 text-sm">Selamat datang kembali, Kasir!</p>
    </div>

    <!-- STAT CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-brand-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500">Total Pesanan</p>
                <h3 class="text-2xl font-extrabold text-gray-900 mt-1">{{ $totalPesanan }}</h3>
                <p class="text-[11px] text-gray-400 mt-1">Pesanan</p>
            </div>
            <div class="w-12 h-12 bg-brand-100 text-brand-800 rounded-2xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-bag-shopping"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-brand-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500">Menunggu Pembayaran</p>
                <h3 class="text-2xl font-extrabold text-amber-800 mt-1">{{ $pesananMenunggu }}</h3>
                <p class="text-[11px] text-gray-400 mt-1">Pesanan</p>
            </div>
            <div class="w-12 h-12 bg-amber-100 text-amber-800 rounded-2xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-clock"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-brand-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500">Siap Diambil</p>
                <h3 class="text-2xl font-extrabold text-blue-800 mt-1">{{ $pesananSiap }}</h3>
                <p class="text-[11px] text-gray-400 mt-1">Pesanan</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 text-blue-800 rounded-2xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-box"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-brand-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500">Pesanan Selesai</p>
                <h3 class="text-2xl font-extrabold text-emerald-800 mt-1">{{ $pesananSelesai }}</h3>
                <p class="text-[11px] text-gray-400 mt-1">Pesanan</p>
            </div>
            <div class="w-12 h-12 bg-emerald-100 text-emerald-800 rounded-2xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>
    </div>

    <!-- PESANAN TERBARU & DONUT CHART RINGKASAN -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-brand-200 shadow-sm">
            <h3 class="font-bold text-lg text-gray-900 mb-4">Pesanan Terbaru</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-xs text-gray-500 border-b border-gray-100 uppercase">
                        <tr>
                            <th class="pb-3">No. Pesanan</th>
                            <th class="pb-3">Siswa</th>
                            <th class="pb-3">Total</th>
                            <th class="pb-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($recentOrders as $order)
                            <tr class="hover:bg-brand-50">
                                <td class="py-3 font-bold text-gray-900">#{{ $order->order_code }}</td>
                                <td class="py-3">
                                    <p class="font-semibold text-gray-800">{{ $order->user->name ?? 'Siswa' }}</p>
                                    <p class="text-xs text-gray-400">{{ $order->user->kelas->name ?? 'X RPL 1' }}</p>
                                </td>
                                <td class="py-3 font-bold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="py-3">
                                    <span class="px-2.5 py-1 text-[11px] font-bold rounded-lg 
                                        {{ $order->status === 'Siap Diambil' ? 'bg-blue-100 text-blue-800' : ($order->status === 'Selesai' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800') }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <a href="{{ route('kasir.orders.index') }}" class="w-full block text-center py-3 bg-brand-100 text-brand-800 font-bold rounded-xl text-xs mt-4 hover:bg-brand-200 transition">
                Lihat Semua Pesanan &rarr;
            </a>
        </div>

        <!-- DONUT CHART RINGKASAN PESANAN -->
        <div class="bg-white p-6 rounded-2xl border border-brand-200 shadow-sm flex flex-col items-center justify-center">
            <h3 class="font-bold text-lg text-gray-900 mb-4 self-start">Ringkasan Pesanan</h3>
            <div class="w-56 h-56">
                <canvas id="orderChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('orderChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Menunggu Pembayaran', 'Siap Diambil', 'Selesai'],
            datasets: [{
                data: [{{ $pesananMenunggu }}, {{ $pesananSiap }}, {{ $pesananSelesai }}],
                backgroundColor: ['#F59E0B', '#3B82F6', '#10B981']
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
</script>
@endsection