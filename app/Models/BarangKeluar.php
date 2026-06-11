<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_keluar',
        'tanggal',
        'tujuan',
        'total_harga',
        'keterangan',
        'user_id',
    ];

    protected $casts = [
        'tanggal'     => 'date',
        'total_harga' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(DetailBarangKeluar::class);
    }

    /**
     * Generate nomor keluar otomatis: BK-YYYYMMDD-XXXX
     */
    public static function generateNomor(): string
    {
        $prefix = 'BK-' . now()->format('Ymd') . '-';
        $last = self::where('nomor_keluar', 'like', $prefix . '%')
            ->orderByDesc('nomor_keluar')
            ->first();

        $seq = $last ? (int) substr($last->nomor_keluar, -4) + 1 : 1;

        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
