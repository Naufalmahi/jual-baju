@extends('layouts.siswa')

@section('content')
<div>
    <!-- HERO / SALAM -->
    <div class="mb-8 bg-gradient-to-r from-brand-800 to-brand-900 rounded-3xl p-8 text-white relative overflow-hidden shadow-lg">
        <div class="relative z-10 max-w-xl">
            <span class="px-3 py-1 bg-white/20 text-xs font-semibold rounded-full backdrop-blur-sm mb-3 inline-block">Koperasi Sekolah</span>
            <h2 class="text-3xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}! 👋</h2>
            <p class="text-brand-100 text-sm leading-relaxed">Pesan seragam sekolah kamu dengan mudah, bayar secara Cash di kasir atau langsung via QRIS secara cepat dan praktis.</p>
        </div>
    </div>

    <!-- STATISTIK RINGKASAN TRANSAKSI -->
    <div class="mb-8">
        <h3 class="font-bold text-lg text-gray-900 mb-4">Ringkasan Transaksi Saya</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Stat 1: Total Pesanan -->
            <div class="bg-white p-5 rounded-2xl border border-brand-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-brand-100 text-brand-800 rounded-xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-clipboard-list"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Total Pesanan</p>
                    <h4 class="text-xl font-bold text-gray-900">{{ $totalPesanan }}</h4>
                </div>
            </div>

            <!-- Stat 2: Menunggu Pembayaran -->
            <div class="bg-white p-5 rounded-2xl border border-brand-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-100 text-amber-800 rounded-xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Menunggu Pembayaran</p>
                    <h4 class="text-xl font-bold text-gray-900">{{ $pesananMenunggu }}</h4>
                </div>
            </div>

            <!-- Stat 3: Siap Diambil -->
            <div class="bg-white p-5 rounded-2xl border border-brand-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 text-blue-800 rounded-xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-store"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Siap Diambil</p>
                    <h4 class="text-xl font-bold text-gray-900">{{ $pesananSiap }}</h4>
                </div>
            </div>

            <!-- Stat 4: Selesai -->
            <div class="bg-white p-5 rounded-2xl border border-brand-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-800 rounded-xl flex items-center justify-center text-xl">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Pesanan Selesai</p>
                    <h4 class="text-xl font-bold text-gray-900">{{ $pesananSelesai }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- KATALOG RINGKAS PRODUK -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-lg text-gray-900">Rekomendasi Seragam</h3>
            <a href="{{ route('siswa.products.index') }}" class="text-xs font-bold text-brand-800 hover:underline flex items-center gap-1">
                <span>Lihat Semua Katalog</span>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-2xl border border-brand-200 overflow-hidden shadow-sm hover:shadow-md transition group">
                    <div class="h-48 bg-brand-100 relative overflow-hidden flex items-center justify-center p-4">
                        <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://placehold.co/400x400/EAE5DD/4A2E1B?text='.urlencode($product->name) }}" 
                             alt="{{ $product->name }}" 
                             class="max-h-full object-contain group-hover:scale-105 transition duration-300">
                    </div>
                    <div class="p-5">
                        <span class="text-[10px] font-bold text-brand-800 uppercase bg-brand-100 px-2 py-1 rounded-md">{{ $product->category->name ?? 'Seragam' }}</span>
                        <h4 class="font-bold text-gray-900 text-base mt-2 line-clamp-1">{{ $product->name }}</h4>
                        <p class="text-brand-800 font-bold text-lg mt-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <a href="{{ route('siswa.products.index') }}" class="mt-4 w-full block text-center py-2.5 bg-brand-800 text-white font-semibold rounded-xl text-xs hover:bg-brand-900 transition">
                            Lihat Katalog
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection