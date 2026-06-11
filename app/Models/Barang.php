<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barang',
        'barcode',
        'kategori_id',
        'satuan_id',
        'pemasok_id',
        'nama_barang',
        'harga_beli',
        'harga_jual',
        'stok',
        'stok_minimum',
        'lokasi_rak',
        'deskripsi',
    ];

    protected $casts = [
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    public function pemasok()
    {
        return $this->belongsTo(Pemasok::class);
    }

    public function detailBarangMasuks()
    {
        return $this->hasMany(DetailBarangMasuk::class);
    }

    public function detailBarangKeluars()
    {
        return $this->hasMany(DetailBarangKeluar::class);
    }

    public function stockOpnames()
    {
        return $this->hasMany(StockOpname::class);
    }

    public function stokMutasis()
    {
        return $this->hasMany(StokMutasi::class);
    }

    public function isStokMinimum(): bool
    {
        return $this->stok <= $this->stok_minimum;
    }
}
