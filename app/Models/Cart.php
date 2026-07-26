<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'product_size_id',
        'qty',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke ukuran produk
    public function productSize()
    {
        return $this->belongsTo(ProductSize::class);
    }

    // Subtotal
    public function getSubtotalAttribute()
    {
        return $this->qty * $this->product->sell_price;
    }
}