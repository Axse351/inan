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
        Schema::create('barang_masuks', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_masuk')->unique();

            $table->date('tanggal');

            $table->foreignId('pemasok_id')
                ->constrained('pemasoks');

            $table->decimal('total_harga', 15, 2)
                ->default(0);

            $table->text('keterangan')->nullable();

            $table->foreignId('user_id')
                ->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_masuks');
    }
};
