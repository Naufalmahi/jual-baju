<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\BarcodeGeneratorPNG;

class ProductController extends Controller
{
    protected function generateBarcodeCode()
    {
        $year = date('Y');
        $prefix = "JB-{$year}-";

        $lastProduct = Product::where('barcode', 'like', $prefix . '%')
            ->orderBy('barcode', 'desc')
            ->first();

        if ($lastProduct && $lastProduct->barcode) {
            $lastNumber = (int) substr($lastProduct->barcode, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    protected function generateBarcodeImage($barcodeCode)
    {
        $generator = new BarcodeGeneratorPNG();
        $barcodeData = $generator->getBarcode($barcodeCode, $generator::TYPE_CODE_128);

        $filename = 'barcodes/' . $barcodeCode . '.png';
        Storage::disk('public')->put($filename, $barcodeData);

        return $filename;
    }

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
            'image'       => 'required|image|mimes:jpeg,jpg,png,webp|max:2048',

            'S'   => 'nullable|integer|min:0',
            'M'   => 'nullable|integer|min:0',
            'L'   => 'nullable|integer|min:0',
            'XL'  => 'nullable|integer|min:0',
            'XXL' => 'nullable|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {

            $imagePath = $request->file('image')->store('products', 'public');

            $barcodeCode = $request->barcode ?: $this->generateBarcodeCode();
            $barcodeImage = $this->generateBarcodeImage($barcodeCode);

            $product = Product::create([
                'name'          => $request->name,
                'category_id'   => $request->category_id,
                'barcode'       => $barcodeCode,
                'barcode_image' => $barcodeImage,
                'buy_price'     => $request->buy_price,
                'sell_price'    => $request->sell_price,
                'unit'          => $request->unit,
                'image'         => $imagePath,
                'is_active'     => true,
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
            'image'       => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',

            'S'   => 'nullable|integer|min:0',
            'M'   => 'nullable|integer|min:0',
            'L'   => 'nullable|integer|min:0',
            'XL'  => 'nullable|integer|min:0',
            'XXL' => 'nullable|integer|min:0',
        ]);

        DB::transaction(function () use ($request, $id) {

            $product = Product::findOrFail($id);

            $barcodeCode = $request->barcode;
            $barcodeImage = $product->barcode_image;

            if ($barcodeCode && $barcodeCode !== $product->barcode) {
                if ($product->barcode_image && Storage::disk('public')->exists($product->barcode_image)) {
                    Storage::disk('public')->delete($product->barcode_image);
                }
                $barcodeImage = $this->generateBarcodeImage($barcodeCode);
            } elseif (!$barcodeCode && !$product->barcode) {
                $barcodeCode = $this->generateBarcodeCode();
                $barcodeImage = $this->generateBarcodeImage($barcodeCode);
            }

            $updateData = [
                'name'          => $request->name,
                'category_id'   => $request->category_id,
                'barcode'       => $barcodeCode,
                'barcode_image' => $barcodeImage,
                'buy_price'     => $request->buy_price,
                'sell_price'    => $request->sell_price,
                'unit'          => $request->unit,
            ];

            if ($request->hasFile('image')) {
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                $updateData['image'] = $request->file('image')->store('products', 'public');
            }

            $product->update($updateData);

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
        $product = Product::findOrFail($id);

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        if ($product->barcode_image && Storage::disk('public')->exists($product->barcode_image)) {
            Storage::disk('public')->delete($product->barcode_image);
        }

        $product->delete();

        return redirect()->back()->with('success', 'Barang berhasil dihapus!');
    }

    public function printBarcode($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.print-barcode', compact('product'));
    }

    public function printBulkBarcodes(Request $request)
    {
        $ids = explode(',', $request->ids);
        $products = Product::whereIn('id', $ids)->whereNotNull('barcode_image')->get();
        return view('admin.products.print-barcodes', compact('products'));
    }
}