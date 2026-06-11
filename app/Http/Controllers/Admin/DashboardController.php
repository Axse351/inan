<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\Kategori;
use App\Models\Pemasok;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang   = Barang::count();
        $stokMinimum   = Barang::whereColumn('stok', '<=', 'stok_minimum')->count();
        $masukBulanIni = BarangMasuk::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)->count();
        $keluarBulanIni = BarangKeluar::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)->count();

        $barangMenipis = Barang::with('kategori')
            ->whereColumn('stok', '<=', 'stok_minimum')
            ->orderBy('stok')
            ->limit(8)
            ->get();

        $masukTerbaru = BarangMasuk::with('pemasok')
            ->latest('tanggal')
            ->limit(5)
            ->get()
            ->map(fn($m) => [
                'jenis'      => 'MASUK',
                'nomor'      => $m->nomor_masuk,
                'keterangan' => 'Dari: ' . ($m->pemasok->nama_pemasok ?? '-'),
                'tanggal'    => $m->tanggal,
            ]);

        $keluarTerbaru = BarangKeluar::latest('tanggal')
            ->limit(5)
            ->get()
            ->map(fn($k) => [
                'jenis'      => 'KELUAR',
                'nomor'      => $k->nomor_keluar,
                'keterangan' => 'Ke: ' . ($k->tujuan ?? '-'),
                'tanggal'    => $k->tanggal,
            ]);

        $transaksiTerbaru = (new Collection())
            ->concat($masukTerbaru)
            ->concat($keluarTerbaru)
            ->sortByDesc('tanggal')
            ->take(8)
            ->values();

        $totalKategori = Kategori::count();
        $totalPemasok  = Pemasok::count();
        $nilaiStok     = Barang::selectRaw('SUM(stok * harga_beli) as total')->value('total') ?? 0;

        return view('admin.dashboard', compact(
            'totalBarang',
            'stokMinimum',
            'masukBulanIni',
            'keluarBulanIni',
            'barangMenipis',
            'transaksiTerbaru',
            'totalKategori',
            'totalPemasok',
            'nilaiStok',
        ));
    }
}
