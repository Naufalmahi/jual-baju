<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class CartController extends Controller
{
    // Detect nama kolom jumlah di tabel carts (qty / jumlah / quantity)
    private function getQtyColumn()
    {
        if (Schema::hasColumn('carts', 'qty')) return 'qty';
        if (Schema::hasColumn('carts', 'jumlah')) return 'jumlah';
        return 'quantity';
    }

    public function index()
    {
        $carts = Cart::where('user_id', Auth::id())->get();
        $qtyCol = $this->getQtyColumn();

        $carts->transform(function ($cart) use ($qtyCol) {
            if (method_exists($cart, 'product')) {
                $cart->load('product.sizes');
            }
            if (method_exists($cart, 'productSize')) {
                $cart->load('productSize');
            }

            $sizeName = $cart->size;
            if (empty($sizeName) && isset($cart->productSize)) {
                $sizeName = $cart->productSize->size;
            }
            if (empty($sizeName) && isset($cart->product->sizes) && $cart->product->sizes->isNotEmpty()) {
                $sizeName = $cart->product->sizes->first()->size;
            }

            $cart->display_size = $sizeName ?: 'M';
            $sellPrice = (float) ($cart->product->sell_price ?? 0);
            $cart->item_price = $sellPrice;
            
            $rawQty = $cart->{$qtyCol} ?? $cart->quantity ?? $cart->qty ?? 1;
            $cart->display_qty = max(1, (int) $rawQty);
            $cart->subtotal = $sellPrice * $cart->display_qty;

            return $cart;
        });

        $totalItems = $carts->sum('display_qty');
        $totalPembayaran = $carts->sum('subtotal');

        return view('siswa.cart.index', compact('carts', 'totalItems', 'totalPembayaran'));
    }

    public function store(Request $request, Product $product)
    {
        $sizeInput = $request->input('size', 'M');
        $quantity = max(1, (int) $request->input('quantity', 1));
        $qtyCol = $this->getQtyColumn();

        $productSizeId = null;
        if (method_exists($product, 'sizes')) {
            $productSize = null;
            if (is_numeric($sizeInput)) {
                $productSize = $product->sizes()->where('id', $sizeInput)->first();
            }
            if (!$productSize) {
                $productSize = $product->sizes()->where('size', $sizeInput)->first();
            }
            if (!$productSize) {
                $productSize = $product->sizes()->first();
            }

            if ($productSize) {
                $productSizeId = $productSize->id;
                $sizeInput = $productSize->size;
            }
        }

        $cartData = [
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            $qtyCol => $quantity,
        ];

        if (Schema::hasColumn('carts', 'product_size_id')) {
            $cartData['product_size_id'] = $productSizeId;
        }
        if (Schema::hasColumn('carts', 'size')) {
            $cartData['size'] = $sizeInput;
        }

        $query = Cart::where('user_id', Auth::id())->where('product_id', $product->id);
        if (Schema::hasColumn('carts', 'product_size_id') && $productSizeId) {
            $query->where('product_size_id', $productSizeId);
        } elseif (Schema::hasColumn('carts', 'size')) {
            $query->where('size', $sizeInput);
        }

        $cart = $query->first();

        if ($cart) {
            $cart->increment($qtyCol, $quantity);
        } else {
            Cart::create($cartData);
        }

        return redirect()->route('siswa.cart.index')->with('success', 'Berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request, Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) abort(403);

        $qtyCol = $this->getQtyColumn();
        $qty = max(1, (int) $request->input('quantity', 1));

        $cart->update([$qtyCol => $qty]);

        return redirect()->route('siswa.cart.index')->with('success', 'Jumlah berhasil diperbarui!');
    }

    public function destroy(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) abort(403);
        $cart->delete();

        return redirect()->route('siswa.cart.index')->with('success', 'Produk dihapus dari keranjang.');
    }

    public function buyNow(Request $request, Product $product)
    {
        $sizeInput = $request->input('size', 'M');
        $quantity = max(1, (int) $request->input('quantity', 1));

        session()->put('direct_checkout', [
            'product_id' => $product->id,
            'size' => $sizeInput,
            'quantity' => $quantity,
        ]);

        return redirect()->route('siswa.checkout');
    }
}