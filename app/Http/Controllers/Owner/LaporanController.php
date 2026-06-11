<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\StokMutasi;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function stok(Request $request)
    {
        $barangs = Barang::with(['kategori', 'satuan'])
            ->when($request->filled('kategori_id'), fn($q) => $q->where('kategori_id', $request->kategori_id))
            ->when($request->filled('stok_kritis'), fn($q) => $q->whereColumn('stok', '<=', 'stok_minimum'))
            ->orderBy('nama_barang')
            ->paginate(20)
            ->withQueryString();

        return view('owner.laporan.stok', compact('barangs'));
    }

    public function masuk(Request $request)
    {
        $request->validate([
            'tanggal_dari'   => 'nullable|date',
            'tanggal_sampai' => 'nullable|date|after_or_equal:tanggal_dari',
        ]);

        $query = BarangMasuk::with(['pemasok', 'user', 'details.barang'])
            ->when($request->filled('tanggal_dari'), fn($q) => $q->whereDate('tanggal', '>=', $request->tanggal_dari))
            ->when($request->filled('tanggal_sampai'), fn($q) => $q->whereDate('tanggal', '<=', $request->tanggal_sampai));

        $barangMasuks = $query->latest()->paginate(15)->withQueryString();
        $totalNilai   = $query->sum('total_harga');

        return view('owner.laporan.masuk', compact('barangMasuks', 'totalNilai'));
    }

    public function keluar(Request $request)
    {
        $request->validate([
            'tanggal_dari'   => 'nullable|date',
            'tanggal_sampai' => 'nullable|date|after_or_equal:tanggal_dari',
        ]);

        $query = BarangKeluar::with(['user', 'details.barang'])
            ->when($request->filled('tanggal_dari'), fn($q) => $q->whereDate('tanggal', '>=', $request->tanggal_dari))
            ->when($request->filled('tanggal_sampai'), fn($q) => $q->whereDate('tanggal', '<=', $request->tanggal_sampai));

        $barangKeluars = $query->latest()->paginate(15)->withQueryString();
        $totalNilai    = $query->sum('total_harga');

        return view('owner.laporan.keluar', compact('barangKeluars', 'totalNilai'));
    }

    public function mutasi(Request $request)
    {
        $mutasis = StokMutasi::with(['barang', 'user'])
            ->when($request->filled('barang_id'), fn($q) => $q->where('barang_id', $request->barang_id))
            ->when($request->filled('jenis'), fn($q) => $q->where('jenis', $request->jenis))
            ->when($request->filled('tanggal_dari'), fn($q) => $q->whereDate('tanggal', '>=', $request->tanggal_dari))
            ->when($request->filled('tanggal_sampai'), fn($q) => $q->whereDate('tanggal', '<=', $request->tanggal_sampai))
            ->latest('tanggal')
            ->paginate(20)
            ->withQueryString();

        $barangs = Barang::orderBy('nama_barang')->get();

        return view('owner.laporan.mutasi', compact('mutasis', 'barangs'));
    }
}
