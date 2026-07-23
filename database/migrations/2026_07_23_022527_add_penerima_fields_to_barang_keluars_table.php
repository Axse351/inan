<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang_keluars', function (Blueprint $table) {
            $table->string('nama_penerima')->nullable()->after('tujuan');
            $table->string('no_telepon_penerima')->nullable()->after('nama_penerima');
            $table->text('alamat_penerima')->nullable()->after('no_telepon_penerima');
        });
    }

    public function down(): void
    {
        Schema::table('barang_keluars', function (Blueprint $table) {
            $table->dropColumn(['nama_penerima', 'no_telepon_penerima', 'alamat_penerima']);
        });
    }
};
