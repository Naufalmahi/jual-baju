@extends('layouts.siswa')

@section('title', 'Katalog Seragam')
@section('page_title', 'Katalog Produk')

@section('content')
<div class="section-header flex-column flex-md-row" data-aos="fade-up">
    <div>
        <h4 class="fw-bold">Katalog Seragam Sekolah</h4>
        <p style="font-size:.85rem;color:var(--neutral-500)">Pilih seragam resmi sekolah yang disediakan Koperasi</p>
    </div>
    <form action="{{ route('siswa.products.index') }}" method="GET" class="d-flex mt-2 mt-md-0" style="width:300px">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama baju..." class="form-control-custom me-2" style="flex:1">
        <button type="submit" class="btn-primary-custom btn-sm-custom"><i class="bi bi-search"></i></button>
    </form>
</div>

<!-- Category Filter -->
<div class="d-flex gap-2 overflow-auto pb-2 mb-4" data-aos="fade-up" style="-ms-overflow-style:none;scrollbar-width:none">
    <a href="{{ route('siswa.products.index') }}" class="btn {{ !request('category') ? 'btn-primary-custom' : 'btn-outline-custom' }} btn-sm-custom text-nowrap">Semua Seragam</a>
    @foreach($categories as $category)
        <a href="{{ route('siswa.products.index', ['category' => $category->id]) }}" class="btn {{ request('category') == $category->id ? 'btn-primary-custom' : 'btn-outline-custom' }} btn-sm-custom text-nowrap">{{ $category->name }}</a>
    @endforeach
</div>

<!-- Product Grid -->
<div class="row g-4">
    @forelse($products as $product)
        @php
            $totalStok = isset($product->sizes) ? $product->sizes->sum('stock') : ($product->stock ?? 0);
            $isHabis = $totalStok <= 0;
        @endphp
        <div class="col-sm-6 col-lg-4 col-xl-3" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
            <div class="product-card" style="height:100%">
                <div class="product-image" style="height:200px">
                    @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="{{ $isHabis ? 'opacity-50 grayscale' : '' }}">
                    @else
                        <div class="text-center"><i class="bi bi-bag" style="font-size:2.5rem;color:var(--primary);opacity:.3"></i></div>
                    @endif
                    @if($isHabis)
                        <span class="position-absolute top-0 end-0 badge badge-danger m-3">Stok Habis</span>
                    @endif
                </div>
                <div class="product-body">
                    <span class="product-category">{{ $product->category->name ?? 'Seragam' }}</span>
                    <h5 class="product-name">{{ $product->name }}</h5>
                    <p class="product-price">Rp {{ number_format($product->sell_price, 0, ',', '.') }} <span style="font-size:.7rem;font-weight:400;color:var(--neutral-400)">/ {{ $product->unit ?? 'Pcs' }}</span></p>
                    <form action="{{ route('siswa.cart.store', $product->id) }}" method="POST">
                        @csrf
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label style="font-size:.65rem;font-weight:700;color:var(--neutral-600)">Ukuran:</label>
                                <select name="size" class="form-select-custom w-100" style="padding:6px 8px;font-size:.75rem" {{ $isHabis ? 'disabled' : '' }} required>
                                    @if(isset($product->sizes) && $product->sizes->isNotEmpty())
                                        @foreach($product->sizes as $s)
                                            <option value="{{ $s->size }}" {{ $s->stock <= 0 ? 'disabled' : '' }}>{{ $s->size }}{{ $s->stock <= 0 ? ' (Habis)' : '' }}</option>
                                        @endforeach
                                    @else
                                        <option value="S">S</option><option value="M" selected>M</option><option value="L">L</option><option value="XL">XL</option><option value="XXL">XXL</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-6">
                                <label style="font-size:.65rem;font-weight:700;color:var(--neutral-600)">Jumlah:</label>
                                <input type="number" name="quantity" value="1" min="1" max="100" class="form-control-custom w-100 text-center" style="padding:6px 8px;font-size:.75rem" {{ $isHabis ? 'disabled' : '' }} required>
                            </div>
                        </div>
                        <button type="submit" class="w-100 mb-1 {{ $isHabis ? 'btn-outline-custom' : 'btn-primary-custom' }} btn-sm-custom justify-center" {{ $isHabis ? 'disabled' : '' }}>
                            <i class="bi bi-cart-plus"></i> {{ $isHabis ? 'Stok Habis' : 'Keranjang' }}
                        </button>
                        <button type="submit" formaction="{{ route('siswa.buy.now', $product->id) }}" class="w-100 {{ $isHabis ? 'btn-outline-custom' : 'btn-accent-custom' }} btn-sm-custom justify-center" {{ $isHabis ? 'disabled' : '' }}>
                            <i class="bi bi-lightning"></i> Bayar Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card-custom text-center" style="padding:48px">
                <i class="bi bi-bag" style="font-size:3rem;color:var(--neutral-300);margin-bottom:12px;display:block"></i>
                <p style="color:var(--neutral-500)">Belum ada seragam di kategori ini.</p>
            </div>
        </div>
    @endforelse
</div>
@endsection
