<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama produk
            $table->text('description'); // Deskripsi produk
            $table->decimal('price', 10, 2); // Harga (max 99,999,999.99)
            $table->integer('stock')->default(0); // Stok tersedia
            $table->enum('category', [
                'action_figure',
                'lego',
                'puzzle',
                'board_game',
                'educational',
                'collector'
            ])->default('action_figure');
            $table->string('image_url')->nullable(); // URL gambar produk
            $table->enum('age_range', ['0-3', '4-7', '8-12', '13+'])->default('4-7');
            $table->string('brand')->nullable(); // Brand/merek
            $table->decimal('rating', 2, 1)->default(0.0); // Rating 0.0-5.0
            $table->boolean('is_featured')->default(false); // Produk unggulan
            $table->timestamps();
            $table->softDeletes(); // Soft delete
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
