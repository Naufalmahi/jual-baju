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
        Schema::create('carts', function (Blueprint $table) {

            $table->id();

            // User yang menambahkan ke keranjang
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Produk yang dipilih
            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            // Ukuran yang dipilih
            $table->foreignId('product_size_id')
                ->constrained('product_sizes')
                ->cascadeOnDelete();

            // Jumlah yang dibeli
            $table->unsignedInteger('qty')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};