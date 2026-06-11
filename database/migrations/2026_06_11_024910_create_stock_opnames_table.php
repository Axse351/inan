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
        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')
                ->constrained('barangs');

            $table->integer('stok_sistem');

            $table->integer('stok_fisik');

            $table->integer('selisih');

            $table->text('keterangan')->nullable();

            $table->foreignId('user_id')
                ->constrained('users');

            $table->date('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_opnames');
    }
};
