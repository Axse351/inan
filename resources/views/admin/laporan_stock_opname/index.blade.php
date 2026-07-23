@extends('layouts.app')

@section('title', 'Laporan Stock Opname')
@section('page_title', 'Laporan Stock Opname')
@section('breadcrumb', 'Transaksi / Laporan Stock Opname')

@section('content')
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form class="row g-2 align-items-end" method="GET">
                <div class="col-auto">
                    <label class="form-label small fw-medium mb-1">Jenis Periode</label>
                    <select name="periode" id="periode" class="form-select form-select-sm">
                        <option value="bulanan" @selected($periode === 'bulanan')>Bulanan</option>
                        <option value="custom" @selected($periode === 'custom')>Rentang Tanggal</option>
                    </select>
                </div>

                <div class="col-auto periode-bulanan">
                    <label class="form-label small fw-medium mb-1">Bulan</label>
                    <select name="bulan" class="form-select form-select-sm">
                        @foreach (['1' => 'Januari', '2' => 'Februari', '3' => 'Maret', '4' => 'April', '5' => 'Mei', '6' => 'Juni', '7' => 'Juli', '8' => 'Agustus', '9' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'] as $val => $label)
                            <option value="{{ $val }}" @selected((int) $bulan === (int) $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto periode-bulanan">
                    <label class="form-label small fw-medium mb-1">Tahun</label>
                    <select name="tahun" class="form-select form-select-sm">
                        @for ($y = now()->year; $y >= now()->year - 5; $y--)
                            <option value="{{ $y }}" @selected((int) $tahun === $y)>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-auto periode-custom">
                    <label class="form-label small fw-medium mb-1">Dari</label>
                    <input type="date" name="tanggal_dari" class="form-control form-control-sm"
                        value="{{ $tanggal_dari }}">
                </div>
                <div class="col-auto periode-custom">
                    <label class="form-label small fw-medium mb-1">Sampai</label>
                    <input type="date" name="tanggal_sampai" class="form-control form-control-sm"
                        value="{{ $tanggal_sampai }}">
                </div>

                <div class="col-auto">
                    <button class="btn btn-sm btn-primary"><i class="bi bi-funnel me-1"></i> Tampilkan</button>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.laporan_stock_opname.pdf', request()->query()) }}" target="_blank"
                        class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="alert alert-secondary py-2 small">
        <i class="bi bi-calendar-range me-1"></i>
        Periode laporan: <strong>{{ $start->format('d/m/Y') }} — {{ $end->format('d/m/Y') }}</strong>
    </div>

    {{-- ═══════════ RINGKASAN KEUANGAN ═══════════ --}}
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1"><i class="bi bi-graph-up-arrow me-1"></i>Total Pendapatan</div>
                    <div class="fs-5 fw-bold text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                    <div class="text-muted small">{{ $jumlahTransaksiKeluar }} transaksi keluar</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1"><i class="bi bi-cart-plus me-1"></i>Modal Pembelian</div>
                    <div class="fs-5 fw-bold text-primary">Rp {{ number_format($totalModalPembelian, 0, ',', '.') }}</div>
                    <div class="text-muted small">{{ $jumlahTransaksiMasuk }} transaksi masuk</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1"><i class="bi bi-cash-stack me-1"></i>HPP (Est.)</div>
                    <div class="fs-5 fw-bold text-warning">Rp {{ number_format($totalHpp, 0, ',', '.') }}</div>
                    <div class="text-muted small">berdasarkan harga beli saat ini</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1"><i class="bi bi-piggy-bank me-1"></i>Laba Kotor (Est.)</div>
                    <div class="fs-5 fw-bold {{ $labaKotor >= 0 ? 'text-success' : 'text-danger' }}">
                        Rp {{ number_format($labaKotor, 0, ',', '.') }}
                    </div>
                    <div class="text-muted small">Pendapatan − HPP</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════ ARUS BARANG ═══════════ --}}
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1"><i class="bi bi-box-arrow-in-down me-1"></i>Total Qty Barang Masuk
                    </div>
                    <div class="fs-5 fw-bold">{{ number_format($totalQtyMasuk, 0, ',', '.') }} unit</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1"><i class="bi bi-box-arrow-up me-1"></i>Total Qty Barang Keluar</div>
                    <div class="fs-5 fw-bold">{{ number_format($totalQtyKeluar, 0, ',', '.') }} unit</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1"><i class="bi bi-clipboard-check me-1"></i>Penyesuaian Stock Opname
                    </div>
                    <div class="fs-6">
                        <span class="badge bg-success">+{{ $selisihPlus }}</span>
                        <span class="badge bg-danger">{{ $selisihMinus }}</span>
                        <span class="text-muted small">({{ $jumlahOpname }} kali opname)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════ KONDISI GUDANG SAAT INI ═══════════ --}}
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1"><i class="bi bi-boxes me-1"></i>Total Nilai Stok Saat Ini</div>
                    <div class="fs-5 fw-bold">Rp {{ number_format($totalNilaiStok, 0, ',', '.') }}</div>
                    <div class="text-muted small">{{ $jumlahJenisBarang }} jenis barang</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1"><i class="bi bi-exclamation-triangle me-1"></i>Barang Stok Kritis
                    </div>
                    <div class="fs-5 fw-bold text-danger">{{ $jumlahBarangKritis }} item</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small mb-1"><i class="bi bi-arrow-left-right me-1"></i>Mutasi Stok per Jenis
                    </div>
                    <div class="small">
                        Masuk: <strong>{{ $mutasiPerJenis['MASUK']->total_qty ?? 0 }}</strong>
                        ({{ $mutasiPerJenis['MASUK']->jumlah_transaksi ?? 0 }}x) &middot;
                        Keluar: <strong>{{ $mutasiPerJenis['KELUAR']->total_qty ?? 0 }}</strong>
                        ({{ $mutasiPerJenis['KELUAR']->jumlah_transaksi ?? 0 }}x) &middot;
                        Adj: <strong>{{ $mutasiPerJenis['ADJUSTMENT']->total_qty ?? 0 }}</strong>
                        ({{ $mutasiPerJenis['ADJUSTMENT']->jumlah_transaksi ?? 0 }}x)
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════ BARANG TERLARIS ═══════════ --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white border-0 py-3">
            <h6 class="mb-0 fw-semibold"><i class="bi bi-trophy me-2"></i>5 Barang Terlaris (Periode Ini)</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama Barang</th>
                            <th class="text-end">Qty Terjual</th>
                            <th class="text-end">Total Omzet</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($barangTerlaris as $i => $bt)
                            <tr>
                                <td class="text-muted small">{{ $i + 1 }}</td>
                                <td class="fw-medium">{{ $bt->nama_barang }}</td>
                                <td class="text-end">{{ $bt->total_qty }}</td>
                                <td class="text-end">Rp {{ number_format($bt->total_omzet, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Tidak ada penjualan pada periode
                                    ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ═══════════ DETAIL STOCK OPNAME ═══════════ --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h6 class="mb-0 fw-semibold"><i class="bi bi-clipboard-data me-2"></i>Detail Stock Opname (Periode Ini)</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Barang</th>
                            <th class="text-end">Stok Sistem</th>
                            <th class="text-end">Stok Fisik</th>
                            <th class="text-end">Selisih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($detailOpname as $i => $so)
                            <tr>
                                <td class="text-muted small">{{ $i + 1 }}</td>
                                <td>{{ $so->tanggal->format('d/m/Y') }}</td>
                                <td>{{ $so->barang?->nama_barang ?? '-' }}</td>
                                <td class="text-end">{{ $so->stok_sistem }}</td>
                                <td class="text-end">{{ $so->stok_fisik }}</td>
                                <td class="text-end">
                                    @if ($so->selisih > 0)
                                        <span class="badge bg-success">+{{ $so->selisih }}</span>
                                    @elseif ($so->selisih < 0)
                                        <span class="badge bg-danger">{{ $so->selisih }}</span>
                                    @else
                                        <span class="badge bg-secondary">0</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Tidak ada stock opname pada periode
                                    ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleFilterUI() {
            const periode = document.getElementById('periode').value;
            document.querySelectorAll('.periode-bulanan').forEach(el => {
                el.style.display = periode === 'bulanan' ? '' : 'none';
            });
            document.querySelectorAll('.periode-custom').forEach(el => {
                el.style.display = periode === 'custom' ? '' : 'none';
            });
        }
        document.getElementById('periode').addEventListener('change', toggleFilterUI);
        document.addEventListener('DOMContentLoaded', toggleFilterUI);
    </script>
@endpush
