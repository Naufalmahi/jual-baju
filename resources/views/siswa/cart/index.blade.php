@extends('layouts.siswa')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Keranjang Belanja</h2>
    <p class="text-gray-500 text-sm">Kelola seragam yang ingin kamu beli sebelum checkout</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- ITEM KERANJANG -->
    <div class="lg:col-span-2 space-y-4">
        @forelse($carts as $cart)
            <div class="bg-white p-5 rounded-2xl border border-brand-200 shadow-sm flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-20 h-20 bg-brand-100 rounded-xl flex items-center justify-center p-2 flex-shrink-0">
                        <img src="{{ $cart->product && $cart->product->image ? asset('storage/'.$cart->product->image) : 'https://placehold.co/100x100/EAE5DD/4A2E1B?text='.urlencode($cart->product->name ?? 'Seragam') }}" 
                             alt="{{ $cart->product->name ?? 'Produk' }}" class="max-h-full object-contain">
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-base">{{ $cart->product->name ?? 'Produk Seragam' }}</h4>
                        <p class="text-xs text-gray-500 mt-0.5">Ukuran: <span class="font-semibold text-brand-800">{{ $cart->display_size }}</span></p>
                        <p class="text-brand-800 font-extrabold text-base mt-1">Rp {{ number_format($cart->item_price, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- TOMBOL - DAN + INDEPENDEN -->
                    <div class="flex items-center">
                        <!-- MINUS FORM -->
                        <form action="{{ route('siswa.cart.update', $cart->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="quantity" value="{{ max(1, $cart->display_qty - 1) }}">
                            <button type="submit" 
                                    class="w-9 h-9 flex items-center justify-center bg-gray-100 hover:bg-brand-100 text-gray-800 font-bold rounded-l-xl border border-gray-300 transition text-base"
                                    {{ $cart->display_qty <= 1 ? 'disabled' : '' }}>
                                &minus;
                            </button>
                        </form>

                        <!-- DISPLAY JUMLAH -->
                        <span class="w-12 h-9 flex items-center justify-center font-extrabold text-sm text-gray-900 border-t border-b border-gray-300 bg-white">
                            {{ $cart->display_qty }}
                        </span>

                        <!-- PLUS FORM -->
                        <form action="{{ route('siswa.cart.update', $cart->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="quantity" value="{{ $cart->display_qty + 1 }}">
                            <button type="submit" 
                                    class="w-9 h-9 flex items-center justify-center bg-gray-100 hover:bg-brand-100 text-gray-800 font-bold rounded-r-xl border border-gray-300 transition text-base">
                                &plus;
                            </button>
                        </form>
                    </div>

                    <!-- TOMBOL HAPUS (MERAH) -->
                    <form action="{{ route('siswa.cart.destroy', $cart->id) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-9 h-9 bg-red-50 text-red-500 rounded-xl hover:bg-red-100 hover:text-red-700 transition flex items-center justify-center text-sm shadow-xs" title="Hapus">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white p-12 rounded-2xl border border-brand-200 text-center">
                <i class="fa-solid fa-cart-shopping text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 font-medium">Keranjang kamu masih kosong.</p>
                <a href="{{ route('siswa.products.index') }}" class="inline-block mt-4 px-5 py-2.5 bg-brand-800 text-white font-semibold text-xs rounded-xl hover:bg-brand-900">
                    Lihat Katalog Seragam
                </a>
            </div>
        @endforelse
    </div>

    <!-- RINGKASAN BELANJA -->
    @if($carts->isNotEmpty())
        <div class="bg-white p-6 rounded-2xl border border-brand-200 shadow-sm h-fit space-y-4">
            <h3 class="font-bold text-lg text-gray-900 pb-3 border-b border-gray-100">Ringkasan Belanja</h3>

            <div class="space-y-3 text-sm text-gray-600">
                <div class="flex justify-between items-center">
                    <span>Total Items</span>
                    <span class="font-bold text-gray-900 bg-gray-100 px-3 py-1 rounded-full text-xs">{{ $totalItems }} Pcs</span>
                </div>
                <div class="flex justify-between items-center pt-3 border-t border-gray-100 text-base font-bold text-gray-900">
                    <span>Total Pembayaran</span>
                    <span class="text-brand-800 text-xl">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</span>
                </div>
            </div>

            <a href="{{ route('siswa.checkout') }}" class="w-full block text-center py-3.5 bg-brand-800 text-white font-bold rounded-xl text-sm hover:bg-brand-900 transition shadow-sm">
                Lanjut ke Checkout
            </a>
        </div>
    @endif
</div>
@endsection