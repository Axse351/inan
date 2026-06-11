<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasok extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_pemasok',
        'nama_pemasok',
        'alamat',
        'telepon',
        'email',
        'nama_kontak',
    ];

    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }

    public function barangMasuks()
    {
        return $this->hasMany(BarangMasuk::class);
    }
}
