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
        Schema::create('stok_mutasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')
                ->constrained('barangs');

            $table->enum('jenis', [
                'MASUK',
                'KELUAR',
                'ADJUSTMENT'
            ]);

            $table->integer('qty');

            $table->integer('stok_sebelum');

            $table->integer('stok_sesudah');

            $table->string('referensi');

            $table->foreignId('user_id')
                ->constrained('users');

            $table->text('keterangan')->nullable();

            $table->timestamp('tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_mutasis');
    }
};
