<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockOpname;
use App\Models\Barang;
use App\Models\StokMutasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockOpnameController extends Controller
{
    public function index(Request $request)
    {
        $query = StockOpname::with(['barang', 'user']);

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        $stockOpnames = $query->latest()->paginate(10)->withQueryString();
        return view('admin.stock_opname.index', compact('stockOpnames'));
    }

    public function create()
    {
        $barangs = Barang::with('satuan')->orderBy('nama_barang')->get();
        return view('admin.stock_opname.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'    => 'required|date',
            'keterangan' => 'nullable|string',
            'items'      => 'required|array|min:1',
            'items.*.barang_id'  => 'required|exists:barangs,id',
            'items.*.stok_fisik' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->items as $item) {
                $barang     = Barang::find($item['barang_id']);
                $stokSistem = $barang->stok;
                $stokFisik  = (int) $item['stok_fisik'];
                $selisih    = $stokFisik - $stokSistem;

                StockOpname::create([
                    'barang_id'   => $item['barang_id'],
                    'stok_sistem' => $stokSistem,
                    'stok_fisik'  => $stokFisik,
                    'selisih'     => $selisih,
                    'keterangan'  => $request->keterangan,
                    'user_id'     => auth()->id(),
                    'tanggal'     => $request->tanggal,
                ]);

                // Update stok barang sesuai stok fisik
                if ($selisih !== 0) {
                    $barang->update(['stok' => $stokFisik]);

                    StokMutasi::create([
                        'barang_id'    => $item['barang_id'],
                        'jenis'        => 'ADJUSTMENT',
                        'qty'          => abs($selisih),
                        'stok_sebelum' => $stokSistem,
                        'stok_sesudah' => $stokFisik,
                        'referensi'    => 'OPNAME-' . $request->tanggal,
                        'user_id'      => auth()->id(),
                        'keterangan'   => 'Stock opname ' . $request->tanggal,
                        'tanggal'      => now(),
                    ]);
                }
            }
        });

        return redirect()->route('admin.stock_opname.index')
            ->with('success', 'Stock opname berhasil dicatat.');
    }

    public function show(StockOpname $stockOpname)
    {
        $stockOpname->load(['barang.satuan', 'user']);
        return view('admin.stock_opname.show', compact('stockOpname'));
    }
}
