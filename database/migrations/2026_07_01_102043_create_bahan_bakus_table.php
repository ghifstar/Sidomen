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
    Schema::create('bahan_bakus', function (Blueprint $table) {
        $table->id();
        $table->string('nama_bahan'); // Contoh: Tepung Terigu, Glaze Choco, Box Donat
        $table->string('kategori')->default('Umum'); // Contoh: Kemasan, Glaze, Topping, Seragam, Bahan Pokok
        $table->integer('stok_pusat'); // Jumlah stok yang ada di Dapur Pusat
        $table->string('satuan'); // Contoh: Kg, Gram, Pcs, Dus, Pail, Pack
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_bakus');
    }
};
