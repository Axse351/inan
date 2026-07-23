<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\StockOpname;
use App\Models\StokMutasi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanStockOpnameController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->buildReportData($request);

        return view('admin.laporan_stock_opname.index', $data);
    }

    public function pdf(Request $request)
    {
        $data = $this->buildReportData($request);

        $pdf = Pdf::loadView('admin.laporan_stock_opname.pdf', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-Stock-Opname-' . $data['periodeLabel'] . '.pdf');
    }

    /**
     * Tentukan rentang tanggal dan kumpulkan seluruh data laporan.
     */
    private function buildReportData(Request $request): array
    {
        $periode = $request->get('periode', 'bulanan');

        if ($periode === 'custom' && $request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $start = Carbon::parse($request->tanggal_dari)->startOfDay();
            $end   = Carbon::parse($request->tanggal_sampai)->endOfDay();
            $periodeLabel = $start->format('d-m-Y') . '_sd_' . $end->format('d-m-Y');
        } else {
            $periode = 'bulanan';
            $bulan   = (int) $request->get('bulan', now()->month);
            $tahun   = (int) $request->get('tahun', now()->year);
            $start   = Carbon::create($tahun, $bulan, 1)->startOfMonth();
            $end     = Carbon::create($tahun, $bulan, 1)->endOfMonth();
            $periodeLabel = $start->format('m-Y');
        }

        // ── Barang Masuk (Pembelian) ──────────────────────────────
        $barangMasukQuery = BarangMasuk::whereBetween('tanggal', [$start, $end]);
        $jumlahTransaksiMasuk = (clone $barangMasukQuery)->count();
        $totalModalPembelian  = (clone $barangMasukQuery)->sum('total_harga');

        $totalQtyMasuk = DB::table('detail_barang_masuks')
            ->join('barang_masuks', 'barang_masuks.id', '=', 'detail_barang_masuks.barang_masuk_id')
            ->whereBetween('barang_masuks.tanggal', [$start, $end])
            ->sum('detail_barang_masuks.qty');

        // ── Barang Keluar (Penjualan / Pendapatan) ────────────────
        $barangKeluarQuery = BarangKeluar::whereBetween('tanggal', [$start, $end]);
        $jumlahTransaksiKeluar = (clone $barangKeluarQuery)->count();
        $totalPendapatan       = (clone $barangKeluarQuery)->sum('total_harga');

        $totalQtyKeluar = DB::table('detail_barang_keluars')
            ->join('barang_keluars', 'barang_keluars.id', '=', 'detail_barang_keluars.barang_keluar_id')
            ->whereBetween('barang_keluars.tanggal', [$start, $end])
            ->sum('detail_barang_keluars.qty');

        // HPP (harga pokok penjualan) dihitung dari harga_beli barang SAAT INI,
        // karena tidak ada histori harga beli per transaksi keluar.
        $totalHpp = DB::table('detail_barang_keluars')
            ->join('barang_keluars', 'barang_keluars.id', '=', 'detail_barang_keluars.barang_keluar_id')
            ->join('barangs', 'barangs.id', '=', 'detail_barang_keluars.barang_id')
            ->whereBetween('barang_keluars.tanggal', [$start, $end])
            ->selectRaw('COALESCE(SUM(detail_barang_keluars.qty * barangs.harga_beli), 0) as total')
            ->value('total');

        $labaKotor = $totalPendapatan - $totalHpp;

        // ── 5 Barang Terlaris (berdasarkan qty keluar) ────────────
        $barangTerlaris = DB::table('detail_barang_keluars')
            ->join('barang_keluars', 'barang_keluars.id', '=', 'detail_barang_keluars.barang_keluar_id')
            ->join('barangs', 'barangs.id', '=', 'detail_barang_keluars.barang_id')
            ->whereBetween('barang_keluars.tanggal', [$start, $end])
            ->select(
                'barangs.nama_barang',
                DB::raw('SUM(detail_barang_keluars.qty) as total_qty'),
                DB::raw('SUM(detail_barang_keluars.subtotal) as total_omzet')
            )
            ->groupBy('barangs.id', 'barangs.nama_barang')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // ── Stock Opname (penyesuaian stok) ───────────────────────
        $opnameQuery   = StockOpname::whereBetween('tanggal', [$start, $end]);
        $jumlahOpname  = (clone $opnameQuery)->count();
        $selisihPlus   = (clone $opnameQuery)->where('selisih', '>', 0)->sum('selisih');
        $selisihMinus  = (clone $opnameQuery)->where('selisih', '<', 0)->sum('selisih');
        $detailOpname  = (clone $opnameQuery)->with('barang')->latest('tanggal')->get();

        // ── Mutasi Stok (breakdown per jenis) ─────────────────────
        $mutasiPerJenis = StokMutasi::whereBetween('tanggal', [$start, $end])
            ->select('jenis', DB::raw('COUNT(*) as jumlah_transaksi'), DB::raw('SUM(qty) as total_qty'))
            ->groupBy('jenis')
            ->get()
            ->keyBy('jenis');

        // ── Kondisi Gudang Saat Ini (snapshot, bukan per periode) ─
        $totalNilaiStok = Barang::selectRaw('COALESCE(SUM(stok * harga_beli), 0) as total')->value('total');
        $jumlahBarangKritis = Barang::whereColumn('stok', '<=', 'stok_minimum')->count();
        $jumlahJenisBarang  = Barang::count();

        return [
            'periode'              => $periode,
            'periodeLabel'         => $periodeLabel,
            'start'                => $start,
            'end'                  => $end,
            'bulan'                => $request->get('bulan', now()->month),
            'tahun'                => $request->get('tahun', now()->year),
            'tanggal_dari'         => $request->get('tanggal_dari'),
            'tanggal_sampai'       => $request->get('tanggal_sampai'),

            'jumlahTransaksiMasuk' => $jumlahTransaksiMasuk,
            'totalModalPembelian'  => $totalModalPembelian,
            'totalQtyMasuk'        => $totalQtyMasuk,

            'jumlahTransaksiKeluar' => $jumlahTransaksiKeluar,
            'totalPendapatan'       => $totalPendapatan,
            'totalQtyKeluar'        => $totalQtyKeluar,
            'totalHpp'              => $totalHpp,
            'labaKotor'             => $labaKotor,

            'barangTerlaris' => $barangTerlaris,

            'jumlahOpname'  => $jumlahOpname,
            'selisihPlus'   => $selisihPlus,
            'selisihMinus'  => $selisihMinus,
            'detailOpname'  => $detailOpname,

            'mutasiPerJenis' => $mutasiPerJenis,

            'totalNilaiStok'     => $totalNilaiStok,
            'jumlahBarangKritis' => $jumlahBarangKritis,
            'jumlahJenisBarang'  => $jumlahJenisBarang,
        ];
    }
}
