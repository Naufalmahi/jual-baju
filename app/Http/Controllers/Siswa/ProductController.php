<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'sizes']);

        // Filter Pencarian Nama Baju
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter Kategori (misal: Seragam Utama, Batik, Olahraga)
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->latest()->get();
        $categories = Category::all();

        return view('siswa.products.index', compact('products', 'categories'));
    }
}