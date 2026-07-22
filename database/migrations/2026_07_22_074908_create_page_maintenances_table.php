<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_maintenances', function (Blueprint $table) {
            $table->id();
            $table->string('route_name')->unique(); // contoh: 'admin.products.index' atau path '/admin/products'
            $table->string('title'); // nama halaman, contoh: 'Kelola Barang & Stok'
            $table->boolean('is_maintenance')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_maintenances');
    }
};