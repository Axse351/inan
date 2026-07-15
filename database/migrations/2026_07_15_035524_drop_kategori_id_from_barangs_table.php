<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            // Hapus foreign key terlebih dahulu
            $table->dropForeign(['kategori_id']);

            // Hapus kolom
            $table->dropColumn('kategori_id');
        });
    }

    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->foreignId('kategori_id')->constrained('kategoris');
        });
    }
};
