<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarangKeluar;
use App\Models\Barang;
use App\Models\StokMutasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = BarangKeluar::with('user');

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }
        if ($request->filled('search')) {
            $query->where('nomor_keluar', 'like', '%' . $request->search . '%')
                ->orWhere('tujuan', 'like', '%' . $request->search . '%');
        }

        $barangKeluars = $query->latest()->paginate(10)->withQueryString();
        return view('admin.barang_keluar.index', compact('barangKeluars'));
    }

    public function create()
    {
        $barangs = Barang::where('stok', '>', 0)->orderBy('nama_barang')->get();
        $nomor   = BarangKeluar::generateNomor();

        return view('admin.barang_keluar.create', compact('barangs', 'nomor'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'               => 'required|date',
            'tujuan'                => 'nullable|string|max:255',
            'nama_penerima'         => 'nullable|string|max:255',
            'no_telepon_penerima'   => 'nullable|string|max:30',
            'alamat_penerima'       => 'nullable|string',
            'keterangan'            => 'nullable|string',
            'details'               => 'required|array|min:1',
            'details.*.barang_id'   => 'required|exists:barangs,id',
            'details.*.qty'         => 'required|integer|min:1',
            'details.*.harga_jual'  => 'required|numeric|min:0',
        ]);

        // Cek stok cukup
        foreach ($request->details as $item) {
            $barang = Barang::findOrFail($item['barang_id']);
            if ($barang->stok < $item['qty']) {
                return back()->withInput()
                    ->with('error', "Stok {$barang->nama_barang} tidak mencukupi. Stok tersedia: {$barang->stok}");
            }
        }

        $barangKeluarId = null;

        DB::transaction(function () use ($request, &$barangKeluarId) {
            $totalHarga = 0;
            $details    = [];

            foreach ($request->details as $item) {
                $subtotal    = $item['qty'] * $item['harga_jual'];
                $totalHarga += $subtotal;
                $details[]   = [
                    'barang_id'  => $item['barang_id'],
                    'qty'        => $item['qty'],
                    'harga_jual' => $item['harga_jual'],
                    'subtotal'   => $subtotal,
                ];
            }

            $barangKeluar = BarangKeluar::create([
                'nomor_keluar'         => BarangKeluar::generateNomor(),
                'tanggal'              => $request->tanggal,
                'tujuan'               => $request->tujuan,
                'nama_penerima'        => $request->nama_penerima,
                'no_telepon_penerima'  => $request->no_telepon_penerima,
                'alamat_penerima'      => $request->alamat_penerima,
                'total_harga'          => $totalHarga,
                'keterangan'           => $request->keterangan,
                'user_id'              => auth()->id(),
            ]);

            foreach ($details as $detail) {
                $barangKeluar->details()->create($detail);

                $barang      = Barang::find($detail['barang_id']);
                $stokSebelum = $barang->stok;
                $barang->decrement('stok', $detail['qty']);

                StokMutasi::create([
                    'barang_id'    => $detail['barang_id'],
                    'jenis'        => 'KELUAR',
                    'qty'          => $detail['qty'],
                    'stok_sebelum' => $stokSebelum,
                    'stok_sesudah' => $stokSebelum - $detail['qty'],
                    'referensi'    => $barangKeluar->nomor_keluar,
                    'user_id'      => auth()->id(),
                    'keterangan'   => 'Barang keluar ke: ' . ($request->tujuan ?? '-'),
                    'tanggal'      => now(),
                ]);
            }

            $barangKeluarId = $barangKeluar->id;
        });

        return redirect()->route('admin.barang_keluar.show', $barangKeluarId)
            ->with('success', 'Barang keluar berhasil dicatat.');
    }

    public function show(BarangKeluar $barangKeluar)
    {
        $barangKeluar->load(['user', 'details.barang.satuan']);
        return view('admin.barang_keluar.show', compact('barangKeluar'));
    }

    public function invoice(BarangKeluar $barangKeluar)
    {
        $barangKeluar->load(['user', 'details.barang.satuan']);

        $pdf = Pdf::loadView('admin.barang_keluar.invoice', compact('barangKeluar'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Invoice-' . $barangKeluar->nomor_keluar . '.pdf');
        // Ganti .stream() jadi .download() kalau mau langsung terunduh:
        // return $pdf->download('Invoice-' . $barangKeluar->nomor_keluar . '.pdf');
    }

    public function destroy(BarangKeluar $barangKeluar)
    {
        DB::transaction(function () use ($barangKeluar) {
            foreach ($barangKeluar->details as $detail) {
                $barang      = $detail->barang;
                $stokSebelum = $barang->stok;
                $barang->increment('stok', $detail->qty);

                StokMutasi::create([
                    'barang_id'    => $detail->barang_id,
                    'jenis'        => 'ADJUSTMENT',
                    'qty'          => $detail->qty,
                    'stok_sebelum' => $stokSebelum,
                    'stok_sesudah' => $stokSebelum + $detail->qty,
                    'referensi'    => 'VOID-' . $barangKeluar->nomor_keluar,
                    'user_id'      => auth()->id(),
                    'keterangan'   => 'Pembatalan barang keluar',
                    'tanggal'      => now(),
                ]);
            }

            $barangKeluar->delete();
        });

        return redirect()->route('admin.barang_keluar.index')
            ->with('success', 'Barang keluar berhasil dibatalkan.');
    }
}
