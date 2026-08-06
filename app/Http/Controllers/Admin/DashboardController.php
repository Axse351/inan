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
        $totalBarang = Barang::count();
        $stokMinimum = Barang::whereColumn('stok', '<=', 'stok_minimum')->count();

        // PERBAIKAN: gunakan created_at, bukan tanggal.
        // Kolom `tanggal` diisi manual oleh user di form (bisa backdate ke bulan lalu),
        // jadi kalau dipakai untuk hitung "bulan ini", hasilnya bisa salah/kecil
        // meskipun banyak transaksi baru dibuat hari ini.
        // created_at mencerminkan kapan transaksi benar-benar disimpan ke sistem.
        $masukBulanIni = BarangMasuk::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $keluarBulanIni = BarangKeluar::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $barangStokMenipis = Barang::whereColumn('stok', '<=', 'stok_minimum')
            ->orderBy('stok')
            ->limit(5)
            ->get();

        // Untuk daftar "Transaksi Terbaru", tetap urutkan berdasarkan created_at
        // (urutan sebenarnya transaksi dibuat), bukan tanggal manual, supaya
        // urutannya konsisten dengan apa yang baru saja terjadi di sistem.
        $masukTerbaru = BarangMasuk::with('pemasok')
            ->latest('created_at')
            ->limit(5)
            ->get()
            ->map(fn($m) => [
                'jenis'      => 'MASUK',
                'nomor'      => $m->nomor_masuk,
                'keterangan' => 'Dari: ' . ($m->pemasok->nama_pemasok ?? '-'),
                'tanggal'    => $m->tanggal,
                'created_at' => $m->created_at,
            ]);

        $keluarTerbaru = BarangKeluar::latest('created_at')
            ->limit(5)
            ->get()
            ->map(fn($k) => [
                'jenis'      => 'KELUAR',
                'nomor'      => $k->nomor_keluar,
                'keterangan' => 'Ke: ' . ($k->tujuan ?? '-'),
                'tanggal'    => $k->tanggal,
                'created_at' => $k->created_at,
            ]);

        $transaksiTerbaru = (new Collection())
            ->concat($masukTerbaru)
            ->concat($keluarTerbaru)
            ->sortByDesc('created_at')
            ->take(8)
            ->values();

        $totalPemasok = Pemasok::count();
        $nilaiStok    = Barang::selectRaw('SUM(stok * harga_beli) as total')->value('total') ?? 0;

        return view('admin.dashboard', compact(
            'totalBarang',
            'stokMinimum',
            'masukBulanIni',
            'keluarBulanIni',
            'transaksiTerbaru',
            'barangStokMenipis',
            'totalPemasok',
            'nilaiStok',
        ));
    }
}
