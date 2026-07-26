@extends('layouts.siswa')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Pesanan Saya</h2>
    <p class="text-gray-500 text-sm">Daftar pesanan kamu yang belum selesai</p>
</div>

<div class="space-y-4">
    @forelse($orders as $order)
        <div class="bg-white p-6 rounded-2xl border border-brand-200 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="font-bold text-brand-800 text-base">#{{ $order->order_code }}</span>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full 
                        {{ $order->status === 'Menunggu Pembayaran' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ $order->status }}
                    </span>
                    <span class="text-xs text-gray-400 uppercase font-semibold">({{ $order->payment_method }})</span>
                </div>
                <div class="text-xs text-gray-500 space-y-1">
                    <p>Jumlah Produk: {{ $order->items->sum('quantity') }} Pcs</p>
                    <p>Total Tagihan: <span class="font-bold text-gray-900 text-sm">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></p>
                </div>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                @if($order->payment_method === 'qris' && $order->status === 'Menunggu Pembayaran')
                    <!-- Tombol Simulasi QRIS Midtrans -->
                    <form action="{{ route('siswa.orders.payQris', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-emerald-700 text-white font-semibold text-xs rounded-xl hover:bg-emerald-800 transition flex items-center gap-2">
                            <i class="fa-solid fa-qrcode"></i>
                            <span>Bayar Sekarang (Simulasi QRIS)</span>
                        </button>
                    </form>
                @elseif($order->payment_method === 'cash' && $order->status === 'Menunggu Pembayaran')
                    <div class="text-xs bg-amber-50 text-amber-800 p-3 rounded-xl border border-amber-200">
                        <i class="fa-solid fa-info-circle mr-1"></i> Silakan bayar & ambil baju di Kasir Koperasi.
                    </div>
                @elseif($order->status === 'Siap Diambil')
                    <div class="text-xs bg-blue-50 text-blue-800 p-3 rounded-xl border border-blue-200 font-medium">
                        <i class="fa-solid fa-store mr-1"></i> Tunjukkan kode pesanan ini ke Kasir untuk mengambil baju.
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="bg-white p-12 rounded-2xl border border-brand-200 text-center">
            <i class="fa-solid fa-box-open text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500 font-medium">Tidak ada pesanan aktif saat ini.</p>
        </div>
    @endforelse
</div>
@endsection