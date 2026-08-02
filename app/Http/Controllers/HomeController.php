<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->get();
        $featuredProducts = Product::with('category')
            ->where('is_active', true)
            ->withCount('sizes')
            ->orderByDesc('id')
            ->limit(8)
            ->get();

        return view('home', compact('categories', 'featuredProducts'));
    }
}
