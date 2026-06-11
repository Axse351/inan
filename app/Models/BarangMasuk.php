<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public function details()
    {
        return $this->hasMany(DetailBarangMasuk::class);
    }

    public function pemasok()
    {
        return $this->belongsTo(Pemasok::class);
    }
}
