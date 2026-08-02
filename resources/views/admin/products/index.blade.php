@extends('layouts.admin')

@section('title', 'Kelola Barang & Stok')
@section('page_title', 'Kelola Data Barang & Stok')

@section('content')
<div x-data="{
    openModal: false, isEdit: false, formAction: '',
    name: '', category_id: '', barcode: '', buy_price: '', sell_price: '', unit: 'Pcs',
    S: 0, M: 0, L: 0, XL: 0, XXL: 0,
    currentImage: '', imagePreview: '',
    selectedProducts: [], selectAll: false,

    editProduct(product, updateUrl) {
        this.isEdit = true; this.formAction = updateUrl;
        this.name = product.name; this.category_id = product.category_id;
        this.barcode = product.barcode ?? ''; this.buy_price = product.buy_price;
        this.sell_price = product.sell_price; this.unit = product.unit;
        this.S = 0; this.M = 0; this.L = 0; this.XL = 0; this.XXL = 0;
        this.currentImage = product.image ?? '';
        this.imagePreview = product.image ? '{{ url('storage') }}/' + product.image : '';
        if(product.sizes){ product.sizes.forEach(s => {
            if(s.size=='S') this.S=s.stock; if(s.size=='M') this.M=s.stock;
            if(s.size=='L') this.L=s.stock; if(s.size=='XL') this.XL=s.stock; if(s.size=='XXL') this.XXL=s.stock;
        }); }
        this.openModal = true;
    },
    addProduct(storeUrl) {
        this.isEdit = false; this.formAction = storeUrl;
        this.name=''; this.category_id=''; this.barcode=''; this.buy_price=''; this.sell_price='';
        this.unit='Pcs'; this.S=0; this.M=0; this.L=0; this.XL=0; this.XXL=0;
        this.currentImage = ''; this.imagePreview = '';
        this.openModal = true;
    },
    previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => { this.imagePreview = e.target.result; };
            reader.readAsDataURL(file);
        }
    },
    toggleSelectAll() {
        if (this.selectAll) {
            this.selectedProducts = @json($products->pluck('id'));
        } else {
            this.selectedProducts = [];
        }
    },
    printBulkBarcodes() {
        if (this.selectedProducts.length === 0) {
            alert('Pilih minimal 1 produk untuk print barcode');
            return;
        }
        window.open('{{ route('admin.products.print-bulk') }}?ids=' + this.selectedProducts.join(','), '_blank');
    }
}">

    <div class="section-header flex-column flex-md-row" data-aos="fade-up">
        <form method="GET" action="{{ route('admin.products.index') }}" class="d-flex flex-wrap gap-2 w-100 w-md-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / barcode..." class="form-control-custom" style="width:200px">
            <select name="category_id" class="form-select-custom" style="width:180px">
                <option value="">-- Semua Kategori --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-outline-custom"><i class="bi bi-search"></i> Cari</button>
        </form>
        <div class="d-flex gap-2 mt-2 mt-md-0">
            <button @click="printBulkBarcodes()" class="btn-outline-custom" x-show="selectedProducts.length > 0">
                <i class="bi bi-printer"></i> Print Barcode (<span x-text="selectedProducts.length"></span>)
            </button>
            <button @click="addProduct('{{ route('admin.products.store') }}')" class="btn-primary-custom">
                <i class="bi bi-plus-lg"></i> Tambah Barang
            </button>
        </div>
    </div>

    <div class="table-custom" data-aos="fade-up">
        <div class="table-responsive">
            <table class="table-custom mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width:40px">
                            <input type="checkbox" x-model="selectAll" @change="toggleSelectAll()" class="form-check-input">
                        </th>
                        <th>Gambar</th>
                        <th>Barcode</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" :value="{{ $product->id }}" x-model="selectedProducts" class="form-check-input">
                        </td>
                        <td>
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width:50px;height:50px;object-fit:cover;border-radius:8px">
                            @else
                                <div style="width:50px;height:50px;background:var(--neutral-100);border-radius:8px;display:flex;align-items:center;justify-content:center">
                                    <i class="bi bi-image" style="color:var(--neutral-400)"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($product->barcode_image)
                                <div class="d-flex flex-column align-items-center" style="cursor:pointer" onclick="window.open('{{ asset('storage/' . $product->barcode_image) }}', '_blank')">
                                    <img src="{{ asset('storage/' . $product->barcode_image) }}" alt="{{ $product->barcode }}" style="height:30px">
                                    <small style="font-size:.65rem;font-family:monospace;color:var(--neutral-500)">{{ $product->barcode }}</small>
                                </div>
                            @else
                                <span style="font-family:monospace;font-size:.75rem;color:var(--neutral-400)">{{ $product->barcode ?? '-' }}</span>
                            @endif
                        </td>
                        <td class="fw-bold">{{ $product->name }}</td>
                        <td><span class="badge badge-primary">{{ $product->category->name }}</span></td>
                        <td style="color:var(--neutral-600)">Rp {{ number_format($product->buy_price, 0, ',', '.') }}</td>
                        <td class="fw-bold" style="color:var(--success)">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <span class="badge badge-success">{{ $product->total_stock }} {{ $product->unit }}</span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                @if($product->barcode_image)
                                <a href="{{ route('admin.products.print-barcode', $product->id) }}" target="_blank" class="btn-outline-custom btn-sm-custom" title="Print Barcode">
                                    <i class="bi bi-printer"></i>
                                </a>
                                @endif
                                <button @click='editProduct(@json($product), "{{ route("admin.products.update", $product->id) }}")' class="btn-warning-custom btn-sm-custom">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus barang ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger-custom btn-sm-custom"><i class="bi bi-trash3"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center" style="padding:32px;color:var(--neutral-400)">Tidak ada data barang.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3" style="border-top:1px solid var(--neutral-100)">{{ $products->links() }}</div>
    </div>

    <!-- Modal Tambah/Edit Barang -->
    <div x-show="openModal" x-cloak style="position:fixed;inset:0;z-index:1060;background:rgba(0,0,0,.4);display:flex;align-items:center;justify-content:center;padding:16px" @keydown.escape.window="openModal = false">
        <div class="card-custom" style="max-width:600px;width:100%;max-height:90vh;overflow-y:auto" @click.outside="openModal = false" x-transition>
            <div class="card-body-custom">
                <h5 class="fw-bold mb-4" x-text="isEdit ? 'Edit Barang' : 'Tambah Barang Baru'"></h5>
                <form :action="formAction" method="POST" enctype="multipart/form-data">
                    @csrf
                    <template x-if="isEdit"><input type="hidden" name="_method" value="PUT"></template>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label-custom">Gambar Produk <span class="text-danger">*</span></label>
                            <input type="file" name="image" accept="image/jpeg,image/jpg,image/png,image/webp" @change="previewImage($event)" class="form-control-custom w-100" :required="!isEdit">
                            <small class="text-muted" style="font-size:.75rem">Format: JPG, PNG, WEBP. Maks: 2MB</small>
                            <template x-if="imagePreview">
                                <div class="mt-2">
                                    <img :src="imagePreview" alt="Preview" style="max-width:120px;max-height:120px;border-radius:8px;border:1px solid var(--neutral-200)">
                                </div>
                            </template>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Nama Barang</label>
                            <input type="text" name="name" x-model="name" required class="form-control-custom w-100">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Kategori</label>
                            <select name="category_id" x-model="category_id" required class="form-select-custom w-100">
                                <option value="">-- Pilih --</option>
                                @foreach($categories as $cat)<option value="{{ $cat->id }}">{{ $cat->name }}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Barcode (Opsional)</label>
                            <input type="text" name="barcode" x-model="barcode" class="form-control-custom w-100">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Satuan</label>
                            <input type="text" name="unit" x-model="unit" placeholder="Pcs, Set, Stel" required class="form-control-custom w-100">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Harga Beli</label>
                            <input type="number" name="buy_price" x-model="buy_price" required class="form-control-custom w-100">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Harga Jual</label>
                            <input type="number" name="sell_price" x-model="sell_price" required class="form-control-custom w-100">
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom mb-2">Stok Berdasarkan Ukuran</label>
                            <div class="row g-2">
                                @foreach(['S','M','L','XL','XXL'] as $size)
                                <div class="col"><label class="d-block fw-bold mb-1" style="font-size:.7rem;color:var(--neutral-600)">{{ $size }}</label><input type="number" name="{{ $size }}" x-model="{{ $size }}" min="0" class="form-control-custom w-100 text-center"></div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 pt-4 mt-3" style="border-top:1px solid var(--neutral-100)">
                        <button type="button" @click="openModal = false" class="btn-outline-custom">Batal</button>
                        <button type="submit" class="btn-primary-custom">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
