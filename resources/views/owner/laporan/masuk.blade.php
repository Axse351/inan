@extends('owner.layouts.app')

@section('title', 'Laporan Barang Masuk')
@section('page_title', 'Laporan Barang Masuk')
@section('breadcrumb', 'Rekap penerimaan barang dari pemasok')

@section('content')

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('owner.laporan.masuk') }}" class="row g-2 align-items-end">
                <div class="col-sm-4">
                    <label class="form-label small text-muted mb-1">Tanggal Dari</label>
                    <input type="date" name="tanggal_dari" class="form-control form-control-sm"
                        value="{{ request('tanggal_dari') }}">
                </div>
                <div class="col-sm-4">
                    <label class="form-label small text-muted mb-1">Tanggal Sampai</label>
                    <input type="date" name="tanggal_sampai" class="form-control form-control-sm"
                        value="{{ request('tanggal_sampai') }}">
                </div>
                <div class="col-sm-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                    <a href="{{ route('owner.laporan.masuk') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
            <span class="fw-semibold">
                <i class="bi bi-box-arrow-in-down text-success me-2"></i>Daftar Barang Masuk
            </span>
            <span class="badge bg-success-subtle text-success fw-normal">
                Total Nilai: Rp {{ number_format($totalNilai, 0, ',', '.') }}
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>No. Masuk</th>
                            <th>Tanggal</th>
                            <th>Pemasok</th>
                            <th>Dicatat Oleh</th>
                            <th class="text-end pe-3">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($barangMasuks as $item)
                            <tr>
                                <td class="ps-3 text-muted small">{{ $barangMasuks->firstItem() + $loop->index }}</td>
                                <td class="fw-medium">{{ $item->nomor_masuk }}</td>
                                <td class="text-muted small">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                </td>
                                <td>{{ $item->pemasok->nama_pemasok ?? '-' }}</td>
                                <td class="text-muted small">{{ $item->user->name ?? '-' }}</td>
                                <td class="text-end pe-3 fw-medium">Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                                    Tidak ada data barang masuk
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($barangMasuks->hasPages())
            <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3">
                <small class="text-muted">
                    Menampilkan {{ $barangMasuks->firstItem() }}–{{ $barangMasuks->lastItem() }}
                    dari {{ $barangMasuks->total() }} data
                </small>
                {{ $barangMasuks->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

@endsection
