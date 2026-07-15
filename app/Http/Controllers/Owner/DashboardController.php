<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang     = Barang::count();
        $stokMinimum     = Barang::whereColumn('stok', '<=', 'stok_minimum')->count();
        $totalMasukBulan = BarangMasuk::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total_harga');
        $totalKeluarBulan = BarangKeluar::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total_harga');

        // Barang dengan stok menipis
        $barangKritis = Barang::with(['satuan'])
            ->whereColumn('stok', '<=', 'stok_minimum')
            ->orderBy('stok')
            ->limit(5)
            ->get();

        // Grafik transaksi 6 bulan terakhir
        $grafikMasuk = BarangMasuk::select(
            DB::raw('MONTH(tanggal) as bulan'),
            DB::raw('YEAR(tanggal) as tahun'),
            DB::raw('SUM(total_harga) as total')
        )
            ->where('tanggal', '>=', now()->subMonths(6))
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        $grafikKeluar = BarangKeluar::select(
            DB::raw('MONTH(tanggal) as bulan'),
            DB::raw('YEAR(tanggal) as tahun'),
            DB::raw('SUM(total_harga) as total')
        )
            ->where('tanggal', '>=', now()->subMonths(6))
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        return view('owner.dashboard.index', compact(
            'totalBarang',
            'stokMinimum',
            'totalMasukBulan',
            'totalKeluarBulan',
            'barangKritis',
            'grafikMasuk',
            'grafikKeluar'
        ));
    }
}
