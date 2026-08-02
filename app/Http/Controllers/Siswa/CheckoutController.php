<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    private function getQtyColumn()
    {
        if (Schema::hasColumn('carts', 'qty')) return 'qty';
        if (Schema::hasColumn('carts', 'jumlah')) return 'jumlah';
        return 'quantity';
    }

    public function index()
    {
        $userId = Auth::id();
        $directCheckout = session('direct_checkout');

        if ($directCheckout) {
            $product = Product::with('sizes')->findOrFail($directCheckout['product_id']);
            $price = (float) ($product->sell_price ?? 0);
            $quantity = max(1, (int) $directCheckout['quantity']);
            
            $items = collect([(object)[
                'product' => $product,
                'size' => $directCheckout['size'],
                'quantity' => $quantity,
                'price' => $price,
                'subtotal' => $price * $quantity,
            ]]);
            $totalAmount = $items->sum('subtotal');
        } else {
            $qtyCol = $this->getQtyColumn();
            $carts = Cart::with(['product.sizes', 'productSize'])->where('user_id', $userId)->get();
            
            if ($carts->isEmpty()) {
                return redirect()->route('siswa.products.index')->with('error', 'Keranjang kamu masih kosong!');
            }

            $items = $carts->map(function ($cart) use ($qtyCol) {
                $price = (float) ($cart->product->sell_price ?? 0);
                $quantity = max(1, (int) ($cart->{$qtyCol} ?? $cart->quantity ?? 1));
                $sizeName = $cart->size ?? ($cart->productSize->size ?? 'M');

                return (object)[
                    'cart_id' => $cart->id,
                    'product' => $cart->product,
                    'size' => $sizeName,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $price * $quantity,
                ];
            });
            $totalAmount = $items->sum('subtotal');
        }

        $qrisEnabled = Setting::where('key', 'enable_qris')->value('value') !== '0';

        return view('siswa.checkout.index', compact('items', 'totalAmount', 'qrisEnabled'));
    }

    public function store(Request $request)
    {
        $qrisEnabled = Setting::where('key', 'enable_qris')->value('value') !== '0';

        $request->validate([
            'payment_method' => ['required', Rule::in($qrisEnabled ? ['cash', 'qris'] : ['cash'])],
        ]);

        $userId = Auth::id();
        $directCheckout = session('direct_checkout');
        $orderCode = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5));
        $qtyCol = $this->getQtyColumn();

        if ($directCheckout) {
            $product = Product::with('sizes')->findOrFail($directCheckout['product_id']);
            $price = (float) ($product->sell_price ?? 0);
            $quantity = max(1, (int) $directCheckout['quantity']);
            $sizeName = $directCheckout['size'];
            $totalAmount = $price * $quantity;

            $ps = $product->sizes()->where('size', $sizeName)->first();
            if ($ps && $quantity > $ps->stock) {
                return redirect()->route('siswa.checkout')->with('error', "Stok ukuran {$sizeName} tidak mencukupi (sisa {$ps->stock} Pcs).");
            }

            $order = Order::create([
                'order_code' => $orderCode,
                'user_id' => $userId,
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'status' => 'Menunggu Pembayaran',
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'size' => $sizeName,
                'quantity' => $quantity,
                'price' => $price,
            ]);

            // SISWA BELI -> STOK ADMIN OTOMATIS BERKURANG
            if ($product->sizes) {
                $ps = $product->sizes()->where('size', $sizeName)->first();
                if ($ps && $ps->stock > 0) {
                    $ps->decrement('stock', min($ps->stock, $quantity));
                }
            }
            if (Schema::hasColumn('products', 'stock') && $product->stock > 0) {
                $product->decrement('stock', min($product->stock, $quantity));
            }

            session()->forget('direct_checkout');
        } else {
            $carts = Cart::with(['product.sizes', 'productSize'])->where('user_id', $userId)->get();
            if ($carts->isEmpty()) {
                return redirect()->route('siswa.products.index')->with('error', 'Keranjang kamu kosong!');
            }

            $totalAmount = 0;
            $stockErrors = [];
            foreach ($carts as $c) {
                $price = (float) ($c->product->sell_price ?? 0);
                $q = max(1, (int) ($c->{$qtyCol} ?? $c->quantity ?? 1));
                $totalAmount += $price * $q;

                if ($c->product) {
                    $sizeName = $c->size ?? ($c->productSize->size ?? 'M');
                    $ps = $c->product->sizes()->where('size', $sizeName)->first();
                    if ($ps && $q > $ps->stock) {
                        $stockErrors[] = "{$c->product->name} (ukuran {$sizeName}): sisa stok {$ps->stock} Pcs.";
                    }
                }
            }

            if (!empty($stockErrors)) {
                return redirect()->route('siswa.checkout')->with('error', 'Stok tidak mencukupi: ' . implode(' ', $stockErrors));
            }

            $order = Order::create([
                'order_code' => $orderCode,
                'user_id' => $userId,
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'status' => 'Menunggu Pembayaran',
            ]);

            foreach ($carts as $cart) {
                $price = (float) ($cart->product->sell_price ?? 0);
                $quantity = max(1, (int) ($cart->{$qtyCol} ?? $cart->quantity ?? 1));
                $sizeName = $cart->size ?? ($cart->productSize->size ?? 'M');

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'size' => $sizeName,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);

                // SISWA BELI -> STOK ADMIN OTOMATIS BERKURANG
                if ($cart->product && method_exists($cart->product, 'sizes')) {
                    $ps = $cart->product->sizes()->where('size', $sizeName)->first();
                    if ($ps && $ps->stock > 0) {
                        $ps->decrement('stock', min($ps->stock, $quantity));
                    }
                }
                if ($cart->product && Schema::hasColumn('products', 'stock') && $cart->product->stock > 0) {
                    $cart->product->decrement('stock', min($cart->product->stock, $quantity));
                }
            }

            Cart::where('user_id', $userId)->delete();
        }

        // If AJAX request for QRIS, return JSON with order ID
        if ($request->wantsJson() && $request->payment_method === 'qris') {
            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'order_code' => $order->order_code,
            ]);
        }

        return redirect()->route('siswa.orders.index')->with(
            'success', 
            'Pesanan #' . $orderCode . ' berhasil dibuat! Silakan bayar/ambil di Kasir.'
        );
    }
}