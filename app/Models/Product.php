<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'barcode',
        'name',
        'buy_price',
        'sell_price',
        'unit',
        'image',
        'is_active',
    ];

    // Relasi ke kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke ukuran produk
    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }

    // Total stok semua ukuran
    public function getTotalStockAttribute()
    {
        return $this->sizes->sum('stock');
    }
}