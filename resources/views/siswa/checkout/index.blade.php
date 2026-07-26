@extends('layouts.siswa')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Checkout Pesanan</h2>
    <p class="text-gray-500 text-sm">Periksa pesanan kamu dan pilih metode pembayaran</p>
</div>

<form action="{{ route('siswa.checkout.store') }}" method="POST">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- ITEM RINGKASAN & METODE PEMBAYARAN -->
        <div class="lg:col-span-2 space-y-6">
            <!-- DAFTAR ITEM SINKRON -->
            <div class="bg-white p-6 rounded-2xl border border-brand-200 shadow-sm">
                <h3 class="font-bold text-base text-gray-900 mb-4">Item Yang Dibeli</h3>
                <div class="divide-y divide-gray-100">
                    @foreach($items as $item)
                        <div class="py-4 flex justify-between items-center">
                            <div>
                                <h4 class="font-bold text-gray-900 text-base">{{ $item->product->name }}</h4>
                                <p class="text-xs text-gray-500 mt-1">
                                    Ukuran: <span class="font-bold text-brand-800">{{ $item->size }}</span> | 
                                    <span class="font-bold text-gray-700">{{ $item->quantity }} Pcs</span> x Rp {{ number_format($item->price, 0, ',', '.') }}
                                </p>
                            </div>
                            <span class="font-extrabold text-brand-800 text-base">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- PILIH METODE PEMBAYARAN -->
            <div class="bg-white p-6 rounded-2xl border border-brand-200 shadow-sm">
                <h3 class="font-bold text-base text-gray-900 mb-4">Pilih Metode Pembayaran</h3>
                
                <div class="space-y-3">
                    <label class="flex items-center justify-between p-4 border border-gray-200 rounded-xl cursor-pointer hover:border-brand-800 transition">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment_method" value="cash" checked class="text-brand-800 focus:ring-brand-800">
                            <div>
                                <p class="font-bold text-sm text-gray-900">Bayar Cash / Tunai</p>
                                <p class="text-xs text-gray-500">Bayar langsung di kasir Koperasi saat mengambil baju seragam</p>
                            </div>
                        </div>
                        <i class="fa-solid fa-money-bill-wave text-xl text-emerald-700"></i>
                    </label>

                    <label class="flex items-center justify-between p-4 border border-gray-200 rounded-xl cursor-pointer hover:border-brand-800 transition">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment_method" value="qris" class="text-brand-800 focus:ring-brand-800">
                            <div>
                                <p class="font-bold text-sm text-gray-900">QRIS Online (Midtrans)</p>
                                <p class="text-xs text-gray-500">Scan QRIS pake GoPay, OVO, Dana, ShopeePay, atau Mobile Banking</p>
                            </div>
                        </div>
                        <i class="fa-solid fa-qrcode text-xl text-brand-800"></i>
                    </label>
                </div>
            </div>
        </div>

        <!-- TOTAL TAGIHAN -->
        <div class="bg-white p-6 rounded-2xl border border-brand-200 shadow-sm h-fit space-y-4">
            <h3 class="font-bold text-lg text-gray-900 pb-3 border-b border-gray-100">Ringkasan Pembayaran</h3>

            <div class="space-y-2 text-sm text-gray-600">
                <div class="flex justify-between">
                    <span>Subtotal Produk</span>
                    <span>Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-xs text-gray-400">
                    <span>Biaya Ambil di Koperasi</span>
                    <span>Gratis</span>
                </div>
                <div class="flex justify-between pt-3 border-t border-gray-100 text-base font-bold text-gray-900">
                    <span>Total Pembayaran</span>
                    <span class="text-brand-800 text-lg">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 bg-brand-800 text-white font-bold rounded-xl text-sm hover:bg-brand-900 transition shadow-sm">
                Buat Pesanan Sekarang
            </button>
        </div>
    </div>
</form>
@endsection