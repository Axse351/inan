@extends('owner.layouts.app')

@section('title', 'Mutasi Stok')
@section('page_title', 'Mutasi Stok')
@section('breadcrumb', 'Riwayat lengkap perubahan stok barang')

@section('content')

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('owner.laporan.mutasi') }}" class="row g-2 align-items-end">
                <div class="col-sm-3">
                    <label class="form-label small text-muted mb-1">Barang</label>
                    <select name="barang_id" class="form-select form-select-sm">
                        <option value="">Semua Barang</option>
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang->id }}" {{ request('barang_id') == $barang->id ? 'selected' : '' }}>
                                {{ $barang->nama_barang }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <label class="form-label small text-muted mb-1">Jenis</label>
                    <select name="jenis" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        <option value="masuk" {{ request('jenis') === 'masuk' ? 'selected' : '' }}>Masuk</option>
                        <option value="keluar" {{ request('jenis') === 'keluar' ? 'selected' : '' }}>Keluar</option>
                        <option value="koreksi" {{ request('jenis') === 'koreksi' ? 'selected' : '' }}>Koreksi</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <label class="form-label small text-muted mb-1">Tanggal Dari</label>
                    <input type="date" name="tanggal_dari" class="form-control form-control-sm"
                        value="{{ request('tanggal_dari') }}">
                </div>
                <div class="col-sm-2">
                    <label class="form-label small text-muted mb-1">Tanggal Sampai</label>
                    <input type="date" name="tanggal_sampai" class="form-control form-control-sm"
                        value="{{ request('tanggal_sampai') }}">
                </div>
                <div class="col-sm-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                    <a href="{{ route('owner.laporan.mutasi') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <span class="fw-semibold">
                <i class="bi bi-arrow-left-right text-primary me-2"></i>Riwayat Mutasi Stok
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            <th>Tanggal</th>
                            <th>Barang</th>
                            <th>Jenis</th>
                            <th class="text-center">Qty</th>
                            <th class="text-center">Stok Sebelum</th>
                            <th class="text-center">Stok Sesudah</th>
                            <th>Keterangan</th>
                            <th>Dicatat Oleh</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mutasis as $item)
                            <tr>
                                <td class="ps-3 text-muted small">{{ $mutasis->firstItem() + $loop->index }}</td>
                                <td class="text-muted small">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                </td>
                                <td>
                                    <div class="fw-medium">{{ $item->barang->nama_barang ?? '-' }}</div>
                                    <div class="text-muted small">{{ $item->barang->kode_barang ?? '' }}</div>
                                </td>
                                <td>
                                    @if ($item->jenis === 'masuk')
                                        <span class="badge bg-success-subtle text-success">
                                            <i class="bi bi-arrow-down-short"></i> Masuk
                                        </span>
                                    @elseif ($item->jenis === 'keluar')
                                        <span class="badge bg-danger-subtle text-danger">
                                            <i class="bi bi-arrow-up-short"></i> Keluar
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">
                                            <i class="bi bi-pencil"></i> Koreksi
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center fw-medium">{{ $item->qty }}</td>
                                <td class="text-center text-muted">{{ $item->stok_sebelum ?? '-' }}</td>
                                <td class="text-center text-muted">{{ $item->stok_sesudah ?? '-' }}</td>
                                <td class="text-muted small">{{ $item->keterangan ?? '-' }}</td>
                                <td class="text-muted small">{{ $item->user->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                                    Tidak ada data mutasi stok
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($mutasis->hasPages())
            <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3">
                <small class="text-muted">
                    Menampilkan {{ $mutasis->firstItem() }}–{{ $mutasis->lastItem() }}
                    dari {{ $mutasis->total() }} data
                </small>
                {{ $mutasis->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

@endsection
