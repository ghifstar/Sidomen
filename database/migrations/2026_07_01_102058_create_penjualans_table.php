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
    Schema::create('penjualans', function (Blueprint $table) {
        $table->id();
        // Menghubungkan transaksi dengan data Cabang
        $table->foreignId('cabang_id')->constrained('cabangs')->onDelete('cascade');
        $table->date('tanggal'); // Tanggal penjualan harian
        $table->integer('total_donat_terjual'); // Jumlah donat yang laku hari itu
        $table->integer('sisa_stok_bahan'); // Sisa bahan di cabang (untuk mendeteksi kapan menyentuh ROP)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
