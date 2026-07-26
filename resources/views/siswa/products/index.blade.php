@extends('layouts.siswa')

@section('content')
<div>
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Katalog Seragam Sekolah</h2>
            <p class="text-gray-500 text-sm">Pilih seragam resmi sekolah yang disediakan Koperasi</p>
        </div>

        <form action="{{ route('siswa.products.index') }}" method="GET" class="flex gap-2 w-full md:w-80">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari nama baju..." 
                   class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-sm focus:ring-brand-800 focus:border-brand-800">
            <button type="submit" class="px-4 py-2.5 bg-brand-800 text-white rounded-xl text-sm font-semibold hover:bg-brand-900 transition">
                <i class="fa-solid fa-search"></i>
            </button>
        </form>
    </div>

    <!-- FILTER KATEGORI -->
    <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-6">
        <a href="{{ route('siswa.products.index') }}" 
           class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ !request('category') ? 'bg-brand-800 text-white' : 'bg-white text-gray-700 border border-gray-200 hover:bg-brand-100' }}">
            Semua Seragam
        </a>
        @foreach($categories as $category)
            <a href="{{ route('siswa.products.index', ['category' => $category->id]) }}" 
               class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition {{ request('category') == $category->id ? 'bg-brand-800 text-white' : 'bg-white text-gray-700 border border-gray-200 hover:bg-brand-100' }}">
                {{ $category->name }}
            </a>
        @endforeach
    </div>

    <!-- GRID PRODUK -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($products as $product)
            @php
                $totalStok = isset($product->sizes) ? $product->sizes->sum('stock') : ($product->stock ?? 0);
                $isHabis = $totalStok <= 0;
            @endphp

            <div class="bg-white rounded-2xl border border-brand-200 overflow-hidden shadow-sm flex flex-col justify-between relative">
                <div>
                    <div class="h-52 bg-brand-100 flex items-center justify-center p-4 relative">
                        <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://placehold.co/400x400/EAE5DD/4A2E1B?text='.urlencode($product->name) }}" 
                             alt="{{ $product->name }}" class="max-h-full object-contain {{ $isHabis ? 'opacity-40 grayscale' : '' }}">

                        @if($isHabis)
                            <span class="absolute bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md">Stok Habis</span>
                        @endif
                    </div>

                    <div class="p-5">
                        <span class="text-[10px] font-bold text-brand-800 uppercase bg-brand-100 px-2.5 py-1 rounded-md">
                            {{ $product->category->name ?? 'Seragam' }}
                        </span>
                        <h3 class="font-bold text-gray-900 text-base mt-2 line-clamp-2">{{ $product->name }}</h3>
                        <p class="text-brand-800 font-extrabold text-xl mt-2">
                            Rp {{ number_format($product->sell_price, 0, ',', '.') }}
                            <span class="text-xs text-gray-400 font-normal">/ {{ $product->unit ?? 'Pcs' }}</span>
                        </p>
                    </div>
                </div>

                <div class="p-5 pt-0">
                    <form action="{{ route('siswa.cart.store', $product->id) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-2 gap-2 mb-3">
                            <!-- DROPDOWN UKURAN HANYA NAMA UKURAN -->
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 mb-1">Ukuran:</label>
                                <select name="size" class="w-full px-2.5 py-1.5 border border-gray-300 rounded-xl text-xs font-bold focus:ring-brand-800 focus:border-brand-800" {{ $isHabis ? 'disabled' : '' }} required>
                                    @if(isset($product->sizes) && $product->sizes->isNotEmpty())
                                        @foreach($product->sizes as $s)
                                            <option value="{{ $s->size }}" {{ $s->stock <= 0 ? 'disabled' : '' }}>
                                                {{ $s->size }} {{ $s->stock <= 0 ? '(Habis)' : '' }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="S">S</option>
                                        <option value="M" selected>M</option>
                                        <option value="L">L</option>
                                        <option value="XL">XL</option>
                                        <option value="XXL">XXL</option>
                                    @endif
                                </select>
                            </div>

                            <!-- INPUT JUMLAH QTY -->
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 mb-1">Jumlah:</label>
                                <input type="number" name="quantity" value="1" min="1" max="100" class="w-full px-2.5 py-1.5 border border-gray-300 rounded-xl text-xs font-bold text-center focus:ring-brand-800 focus:border-brand-800" {{ $isHabis ? 'disabled' : '' }} required>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <!-- TOMBOL DIDEACTIVATED JIKA STOK HABIS -->
                            <button type="submit" class="w-full py-2.5 px-4 font-bold rounded-xl text-xs transition flex items-center justify-center gap-2 {{ $isHabis ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-brand-100 text-brand-800 hover:bg-brand-200' }}" {{ $isHabis ? 'disabled' : '' }}>
                                <i class="fa-solid fa-cart-plus"></i>
                                <span>{{ $isHabis ? 'Stok Habis' : 'Masukkan Keranjang' }}</span>
                            </button>

                            <button type="submit" formaction="{{ route('siswa.buy.now', $product->id) }}" class="w-full py-2.5 px-4 font-bold rounded-xl text-xs transition flex items-center justify-center gap-2 shadow-sm {{ $isHabis ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'bg-brand-800 text-white hover:bg-brand-900' }}" {{ $isHabis ? 'disabled' : '' }}>
                                <i class="fa-solid fa-bolt"></i>
                                <span>Bayar Sekarang</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white p-12 rounded-2xl border border-brand-200 text-center">
                <i class="fa-solid fa-shirt text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 font-medium">Belum ada seragam sekolah di kategori ini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection