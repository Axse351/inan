<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBarangKeluar extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_keluar_id',
        'barang_id',
        'qty',
        'harga_jual',
        'subtotal',
    ];

    protected $casts = [
        'harga_jual' => 'decimal:2',
        'subtotal'   => 'decimal:2',
    ];

    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
