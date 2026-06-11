@extends('owner.layouts.app')

@section('title', 'Laporan Stok')
@section('page_title', 'Laporan Stok Barang')
@section('breadcrumb', 'Laporan / Stok')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <form class="row g-2 align-items-end" method="GET">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold mb-1">Kategori</label>
                    <select name="kategori_id" class="form-select form-select-sm">
                        <option value="">Semua Kategori</option>
                        @foreach (\App\Models\Kategori::orderBy('nama_kategori')->get() as $kat)
                            <option value="{{ $kat->id }}" @selected(request('kategori_id') == $kat->id)>{{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" name="stok_kritis" id="stok_kritis" value="1"
                            @checked(request('stok_kritis'))>
                        <label class="form-check-label small" for="stok_kritis">Hanya stok kritis</label>
                    </div>
                </div>
                <div class="col-auto">
                    <button class="btn btn-sm btn-primary">Filter</button>
                    @if (request()->hasAny(['kategori_id', 'stok_kritis']))
                        <a href="{{ route('owner.laporan.stok') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                    @endif
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center">Min.</th>
                            <th class="text-end">Nilai (Beli)</th>
                            <th class="text-end">Nilai (Jual)</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangs as $i => $b)
                            <tr @class(['table-danger bg-opacity-10' => $b->isStokMinimum()])>
                                <td class="text-muted small">{{ $barangs->firstItem() + $i }}</td>
                                <td><code class="small">{{ $b->kode_barang }}</code></td>
                                <td class="fw-semibold">{{ $b->nama_barang }}</td>
                                <td>{{ $b->kategori?->nama_kategori ?? '-' }}</td>
                                <td>{{ $b->satuan?->nama_satuan ?? '-' }}</td>
                                <td class="text-center fw-bold">{{ $b->stok }}</td>
                                <td class="text-center text-muted">{{ $b->stok_minimum }}</td>
                                <td class="text-end small">Rp {{ number_format($b->stok * $b->harga_beli, 0, ',', '.') }}
                                </td>
                                <td class="text-end small">Rp {{ number_format($b->stok * $b->harga_jual, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    @if ($b->isStokMinimum())
                                        <span class="badge bg-danger">Kritis</span>
                                    @else
                                        <span class="badge bg-success">Aman</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i> Tidak ada data.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="7" class="text-end fw-bold">Total Nilai</td>
                            <td class="text-end fw-bold">Rp
                                {{ number_format($barangs->sum(fn($b) => $b->stok * $b->harga_beli), 0, ',', '.') }}</td>
                            <td class="text-end fw-bold">Rp
                                {{ number_format($barangs->sum(fn($b) => $b->stok * $b->harga_jual), 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @if ($barangs->hasPages())
            <div class="card-footer bg-white border-0">{{ $barangs->links() }}</div>
        @endif
    </div>
@endsection
