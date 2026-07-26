@extends('layouts.admin')

@section('title', 'Kelola Barang & Stok')
@section('page_title', 'Kelola Data Barang & Stok')

@section('content')
<div x-data="{ 
    openModal: false, 
    isEdit: false, 
    formAction: '', 
    name: '', 
    category_id: '', 
    barcode: '', 
    buy_price: '', 
    sell_price: '', 
    unit: 'Pcs',
    S: 0,
    M: 0,
    L: 0,
    XL: 0,
    XXL: 0,

    editProduct(product, updateUrl) {
        this.isEdit = true;
        this.formAction = updateUrl;
        this.name = product.name;
        this.category_id = product.category_id;
        this.barcode = product.barcode ?? '';
        this.buy_price = product.buy_price;
        this.sell_price = product.sell_price;
        this.unit = product.unit;
        this.S = 0;
        this.M = 0;
        this.L = 0;
        this.XL = 0;
        this.XXL = 0;

        if(product.sizes){
            product.sizes.forEach(size => {
                if(size.size == 'S') this.S = size.stock;
                if(size.size == 'M') this.M = size.stock;
                if(size.size == 'L') this.L = size.stock;
                if(size.size == 'XL') this.XL = size.stock;
                if(size.size == 'XXL') this.XXL = size.stock;
            });
        }

        this.openModal = true;
    },

    addProduct(storeUrl) {
        this.isEdit = false;
        this.formAction = storeUrl;
        this.name = '';
        this.category_id = '';
        this.barcode = '';
        this.buy_price = '';
        this.sell_price = '';
        this.unit = 'Pcs';
        this.S = 0;
        this.M = 0;
        this.L = 0;
        this.XL = 0;
        this.XXL = 0;
        this.openModal = true;
    }
}">

    <!-- HEADER & BUTTON TAMBAH -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <!-- FORM PENCARIAN & FILTER -->
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-wrap items-center gap-2 w-full md:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / barcode..." 
                class="px-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            
            <select name="category_id" class="px-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="">-- Semua Kategori --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 bg-slate-700 text-white text-sm rounded-lg hover:bg-slate-800 transition">
                <i class="fas fa-search"></i> Cari
            </button>
        </form>

        <button @click="addProduct('{{ route('admin.products.store') }}')" 
            class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2 rounded-lg text-sm transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Barang Baru
        </button>
    </div>

    <!-- TABEL DATA BARANG -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-emerald-50 border-b text-emerald-900 font-bold uppercase text-xs">
                    <th class="p-4">Barcode</th>
                    <th class="p-4">Nama Barang</th>
                    <th class="p-4">Kategori</th>
                    <th class="p-4">Harga Beli</th>
                    <th class="p-4">Harga Jual</th>
                    <th class="p-4 text-center">Stok</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="p-4 text-gray-500 font-mono text-xs">{{ $product->barcode ?? '-' }}</td>
                    <td class="p-4 font-bold text-gray-800">{{ $product->name }}</td>
                    <td class="p-4 text-gray-600"><span class="px-2 py-1 bg-gray-100 rounded text-xs font-semibold">{{ $product->category->name }}</span></td>
                    <td class="p-4 text-gray-600">Rp {{ number_format($product->buy_price, 0, ',', '.') }}</td>
                    <td class="p-4 text-emerald-700 font-bold">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</td>
                    <td class="p-4 text-center">
                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700">
                            {{ $product->total_stock }} {{ $product->unit }}
                        </span>
                    </td>
                    <td class="p-4 text-center space-x-2">
                        <button @click='editProduct(@json($product), "{{ route("admin.products.update", $product->id) }}")' 
                            class="px-3 py-1 bg-amber-500 text-white rounded hover:bg-amber-600 text-xs font-bold transition">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus barang ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-xs font-bold transition">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-4 text-center text-gray-500">Tidak ada data barang.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <!-- PAGINATION -->
        <div class="p-4 border-t">
            {{ $products->links() }}
        </div>
    </div>

    <!-- MODAL TAMBAH / EDIT BARANG -->
    <div x-show="openModal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center" x-cloak>
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4" x-text="isEdit ? 'Edit Barang' : 'Tambah Barang Baru'"></h3>
            
            <form :action="formAction" method="POST" class="space-y-4">
                @csrf
                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Barang</label>
                        <input type="text" name="name" x-model="name" required class="w-full p-2 border rounded text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Kategori</label>
                        <select name="category_id" x-model="category_id" required class="w-full p-2 border rounded text-sm">
                            <option value="">-- Pilih --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Barcode (Opsional)</label>
                        <input type="text" name="barcode" x-model="barcode" class="w-full p-2 border rounded text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Satuan</label>
                        <input type="text" name="unit" x-model="unit" placeholder="Pcs, Set, Stel" required class="w-full p-2 border rounded text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Harga Beli</label>
                        <input type="number" name="buy_price" x-model="buy_price" required class="w-full p-2 border rounded text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Harga Jual</label>
                        <input type="number" name="sell_price" x-model="sell_price" required class="w-full p-2 border rounded text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Stok Berdasarkan Ukuran</label>
                    <div class="grid grid-cols-5 gap-3">
                        <div>
                            <label class="text-xs font-bold text-gray-700">S</label>
                            <input type="number" name="S" x-model="S" min="0" class="w-full border rounded p-2 text-sm">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700">M</label>
                            <input type="number" name="M" x-model="M" min="0" class="w-full border rounded p-2 text-sm">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700">L</label>
                            <input type="number" name="L" x-model="L" min="0" class="w-full border rounded p-2 text-sm">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700">XL</label>
                            <input type="number" name="XL" x-model="XL" min="0" class="w-full border rounded p-2 text-sm">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700">XXL</label>
                            <input type="number" name="XXL" x-model="XXL" min="0" class="w-full border rounded p-2 text-sm">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t">
                    <button type="button" @click="openModal = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection