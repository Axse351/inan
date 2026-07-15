<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangMasuk;
use App\Models\DetailBarangMasuk;
use App\Models\Barang;
use App\Models\Pemasok;
use App\Models\StokMutasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = BarangMasuk::with(['pemasok', 'user']);

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }
        if ($request->filled('search')) {
            $query->where('nomor_masuk', 'like', '%' . $request->search . '%');
        }

        $barangMasuks = $query->latest()->paginate(10)->withQueryString();
        return view('admin.barang_masuk.index', compact('barangMasuks'));
    }

    public function create()
    {
        $pemasoks = Pemasok::orderBy('nama_pemasok')->get();
        $barangs = Barang::orderBy('nama_barang')->get(); // ← tambah with('kategori')
        $nomor    = BarangMasuk::generateNomor();

        return view('admin.barang_masuk.create', compact('pemasoks', 'barangs', 'nomor'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'           => 'required|date',
            'pemasok_id'        => 'required|exists:pemasoks,id',
            'keterangan'        => 'nullable|string',
            'details'           => 'required|array|min:1',
            'details.*.barang_id'   => 'required|exists:barangs,id',
            'details.*.qty'         => 'required|integer|min:1',
            'details.*.harga_beli'  => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $totalHarga = 0;
            $details    = [];

            foreach ($request->details as $item) {
                $subtotal    = $item['qty'] * $item['harga_beli'];
                $totalHarga += $subtotal;
                $details[]   = [
                    'barang_id'  => $item['barang_id'],
                    'qty'        => $item['qty'],
                    'harga_beli' => $item['harga_beli'],
                    'subtotal'   => $subtotal,
                ];
            }

            $barangMasuk = BarangMasuk::create([
                'nomor_masuk' => BarangMasuk::generateNomor(),
                'tanggal'     => $request->tanggal,
                'pemasok_id'  => $request->pemasok_id,
                'total_harga' => $totalHarga,
                'keterangan'  => $request->keterangan,
                'user_id'     => auth()->id(),
            ]);

            foreach ($details as $detail) {
                $barangMasuk->details()->create($detail);

                $barang = Barang::find($detail['barang_id']);
                $stokSebelum = $barang->stok;
                $barang->increment('stok', $detail['qty']);

                StokMutasi::create([
                    'barang_id'    => $detail['barang_id'],
                    'jenis'        => 'MASUK',
                    'qty'          => $detail['qty'],
                    'stok_sebelum' => $stokSebelum,
                    'stok_sesudah' => $stokSebelum + $detail['qty'],
                    'referensi'    => $barangMasuk->nomor_masuk,
                    'user_id'      => auth()->id(),
                    'keterangan'   => 'Barang masuk dari pemasok',
                    'tanggal'      => now(),
                ]);
            }
        });

        return redirect()->route('admin.barang_masuk.index')
            ->with('success', 'Barang masuk berhasil dicatat.');
    }

    public function show(BarangMasuk $barangMasuk)
    {
        $barangMasuk->load(['pemasok', 'user', 'details.barang.satuan']);
        return view('admin.barang_masuk.show', compact('barangMasuk'));
    }

    public function destroy(BarangMasuk $barangMasuk)
    {
        // Hanya bisa hapus jika stok cukup untuk dikurangi kembali
        DB::transaction(function () use ($barangMasuk) {
            foreach ($barangMasuk->details as $detail) {
                $barang = $detail->barang;
                $stokSebelum = $barang->stok;
                $barang->decrement('stok', $detail->qty);

                StokMutasi::create([
                    'barang_id'    => $detail->barang_id,
                    'jenis'        => 'ADJUSTMENT',
                    'qty'          => $detail->qty,
                    'stok_sebelum' => $stokSebelum,
                    'stok_sesudah' => $stokSebelum - $detail->qty,
                    'referensi'    => 'VOID-' . $barangMasuk->nomor_masuk,
                    'user_id'      => auth()->id(),
                    'keterangan'   => 'Pembatalan barang masuk',
                    'tanggal'      => now(),
                ]);
            }

            $barangMasuk->delete();
        });

        return redirect()->route('admin.barang_masuk.index')
            ->with('success', 'Barang masuk berhasil dibatalkan.');
    }
}
