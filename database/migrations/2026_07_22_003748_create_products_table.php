<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('barcode')->unique()->nullable(); // Bermanfaat jika pakai barcode scanner
            $table->string('name');
            $table->string('size')->nullable()->default('All Size'); // <-- KOLOM UKURAN DITAMBAHKAN DI SINI
            $table->integer('buy_price')->default(0);  // Harga Beli / Modal
            $table->integer('sell_price')->default(0); // Harga Jual
            $table->integer('stock')->default(0);      // Jumlah Stok
            $table->string('unit')->default('Pcs');   // Satuan (Pcs, Set, Pack, Stel)
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};