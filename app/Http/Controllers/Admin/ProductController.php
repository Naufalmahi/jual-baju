<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'sizes']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->paginate(10);
        $products->withQueryString();

        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'barcode'     => 'nullable|string|unique:products,barcode',
            'buy_price'   => 'required|numeric|min:0',
            'sell_price'  => 'required|numeric|min:0',
            'unit'        => 'required|string',

            'S'   => 'nullable|integer|min:0',
            'M'   => 'nullable|integer|min:0',
            'L'   => 'nullable|integer|min:0',
            'XL'  => 'nullable|integer|min:0',
            'XXL' => 'nullable|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {

            $product = Product::create([
                'name'        => $request->name,
                'category_id' => $request->category_id,
                'barcode'     => $request->barcode,
                'buy_price'   => $request->buy_price,
                'sell_price'  => $request->sell_price,
                'unit'        => $request->unit,
                'is_active'   => true,
            ]);

            foreach (['S','M','L','XL','XXL'] as $size) {
                ProductSize::create([
                    'product_id' => $product->id,
                    'size'       => $size,
                    'stock'      => $request->$size ?? 0,
                ]);
            }

        });

        return redirect()->back()->with('success', 'Barang berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'barcode'     => 'nullable|string|unique:products,barcode,' . $id,
            'buy_price'   => 'required|numeric|min:0',
            'sell_price'  => 'required|numeric|min:0',
            'unit'        => 'required|string',

            'S'   => 'nullable|integer|min:0',
            'M'   => 'nullable|integer|min:0',
            'L'   => 'nullable|integer|min:0',
            'XL'  => 'nullable|integer|min:0',
            'XXL' => 'nullable|integer|min:0',
        ]);

        DB::transaction(function () use ($request, $id) {

            $product = Product::findOrFail($id);

            $product->update([
                'name'        => $request->name,
                'category_id' => $request->category_id,
                'barcode'     => $request->barcode,
                'buy_price'   => $request->buy_price,
                'sell_price'  => $request->sell_price,
                'unit'        => $request->unit,
            ]);

            foreach (['S','M','L','XL','XXL'] as $size) {

                ProductSize::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'size'       => $size,
                    ],
                    [
                        'stock'      => $request->$size ?? 0,
                    ]
                );

            }

        });

        return redirect()->back()->with('success', 'Barang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Barang berhasil dihapus!');
    }
}