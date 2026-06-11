<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokMutasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_id',
        'jenis',
        'qty',
        'stok_sebelum',
        'stok_sesudah',
        'referensi',
        'user_id',
        'keterangan',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
