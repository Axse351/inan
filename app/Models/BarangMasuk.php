<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_masuk',
        'tanggal',
        'pemasok_id',
        'total_harga',
        'keterangan',
        'user_id',
    ];

    protected $casts = [
        'tanggal'     => 'date',
        'total_harga' => 'decimal:2',
    ];

    public function pemasok()
    {
        return $this->belongsTo(Pemasok::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(DetailBarangMasuk::class);
    }

    /**
     * Generate nomor masuk otomatis: BM-YYYYMMDD-XXXX
     */
    public static function generateNomor(): string
    {
        $prefix = 'BM-' . now()->format('Ymd') . '-';
        $last = self::where('nomor_masuk', 'like', $prefix . '%')
            ->orderByDesc('nomor_masuk')
            ->first();

        $seq = $last ? (int) substr($last->nomor_masuk, -4) + 1 : 1;

        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
