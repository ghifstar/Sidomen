<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permintaan_belanjas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cabang_id')->constrained('cabangs')->onDelete('cascade');
            $table->string('nama_bahan');
            $table->float('jumlah');
            $table->string('satuan')->default('Kg');
            $table->text('keterangan')->nullable();
            $table->string('status')->default('Menunggu Persetujuan'); // Menunggu Persetujuan, Diproses, Selesai, Ditolak
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaan_belanjas');
    }
};
