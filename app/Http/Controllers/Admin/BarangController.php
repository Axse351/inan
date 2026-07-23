<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Satuan;
use App\Models\Pemasok;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with(['satuan', 'pemasok']);

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($query) use ($q) {
                $query->where('nama_barang', 'like', "%{$q}%")
                    ->orWhere('kode_barang', 'like', "%{$q}%")
                    ->orWhere('barcode', 'like', "%{$q}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('stok_minimum')) {
            $query->whereColumn('stok', '<=', 'stok_minimum');
        }

        $barangs = $query->latest()->paginate(10)->withQueryString();

        $kategoris = Barang::whereNotNull('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');

        return view('admin.barang.index', compact('barangs', 'kategoris'));
    }

    public function create()
    {
        $satuans   = Satuan::orderBy('nama_satuan')->get();
        $pemasoks  = Pemasok::orderBy('nama_pemasok')->get();

        return view('admin.barang.create', compact('satuans', 'pemasoks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang'   => 'required|string|unique:barangs,kode_barang',
            'barcode'       => 'nullable|string|unique:barangs,barcode',
            'kategori'      => 'required|string|max:255',
            'satuan_id'     => 'required|exists:satuans,id',
            'pemasok_id'    => 'nullable|exists:pemasoks,id',
            'nama_barang'   => 'required|string|max:255',
            'merk'          => 'nullable|string|max:255',
            'harga_beli'    => 'required|numeric|min:0',
            'harga_jual'    => 'required|numeric|min:0',
            'stok'          => 'required|integer|min:0',
            'stok_minimum'  => 'required|integer|min:0',
            'lokasi_rak'    => 'nullable|string|max:255',
            'deskripsi'     => 'nullable|string',
        ]);

        Barang::create($request->all());

        return redirect()->route('admin.barang.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(Barang $barang)
    {
        $barang->load(['satuan', 'pemasok', 'stokMutasis' => function ($q) {
            $q->latest('tanggal')->limit(10);
        }]);

        return view('admin.barang.show', compact('barang'));
    }

    public function edit(Barang $barang)
    {
        $satuans   = Satuan::orderBy('nama_satuan')->get();
        $pemasoks  = Pemasok::orderBy('nama_pemasok')->get();

        return view('admin.barang.edit', compact('barang', 'satuans', 'pemasoks'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'kode_barang'   => 'required|string|unique:barangs,kode_barang,' . $barang->id,
            'barcode'       => 'nullable|string|unique:barangs,barcode,' . $barang->id,
            'kategori'      => 'required|string|max:255',
            'satuan_id'     => 'required|exists:satuans,id',
            'pemasok_id'    => 'nullable|exists:pemasoks,id',
            'nama_barang'   => 'required|string|max:255',
            'merk'          => 'nullable|string|max:255',
            'harga_beli'    => 'required|numeric|min:0',
            'harga_jual'    => 'required|numeric|min:0',
            'stok_minimum'  => 'required|integer|min:0',
            'lokasi_rak'    => 'nullable|string|max:255',
            'deskripsi'     => 'nullable|string',
        ]);

        $barang->update($request->except('stok')); // stok diubah via transaksi masuk/keluar

        return redirect()->route('admin.barang.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->detailBarangMasuks()->exists() || $barang->detailBarangKeluars()->exists()) {
            return back()->with('error', 'Barang tidak dapat dihapus karena memiliki riwayat transaksi.');
        }

        $barang->delete();

        return redirect()->route('admin.barang.index')
            ->with('success', 'Barang berhasil dihapus.');
    }
}
