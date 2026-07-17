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
        Schema::create('stok_cabangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cabang_id')->constrained('cabangs')->onDelete('cascade');
            $table->string('nama_bahan');
            $table->decimal('stok', 10, 2)->default(0);
            $table->string('satuan');
            $table->timestamps();

            $table->unique(['cabang_id', 'nama_bahan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_cabangs');
    }
};
