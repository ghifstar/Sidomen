<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekap_keuangan_cabangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cabang_id')->constrained('cabangs')->onDelete('cascade');
            $table->date('tanggal');
            $table->bigInteger('pemasukan_cash')->default(0);
            $table->bigInteger('pemasukan_cashless')->default(0);
            $table->bigInteger('pengeluaran_nominal')->default(0);
            $table->text('pengeluaran_keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekap_keuangan_cabangs');
    }
};
