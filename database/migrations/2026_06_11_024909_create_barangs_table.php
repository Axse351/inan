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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique();

            $table->string('barcode')->nullable();

            $table->foreignId('kategori_id')
                ->constrained('kategoris');

            $table->foreignId('satuan_id')
                ->constrained('satuans');

            $table->foreignId('pemasok_id')
                ->nullable()
                ->constrained('pemasoks');

            $table->string('nama_barang');

            $table->decimal('harga_beli', 15, 2);

            $table->decimal('harga_jual', 15, 2);

            $table->integer('stok')->default(0);

            $table->integer('stok_minimum')->default(5);

            $table->string('lokasi_rak')->nullable();

            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
