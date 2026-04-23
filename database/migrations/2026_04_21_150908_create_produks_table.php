<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_produk_id')->constrained('kategori_produks')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nama_produk');
            $table->text('deskripsi');
            $table->integer('harga');
            $table->integer('stok')->nullable();
            $table->json('gambar');
            $table->json('varian')->nullable();
            $table->json('ukuran')->nullable();
            $table->boolean('is_custom')->default(false);
            $table->integer('harga_custom')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
