@extends('admin.layouts.app')

@section('title', 'Stock Opname')
@section('page_title', 'Stock Opname')
@section('breadcrumb', 'Transaksi / Stock Opname')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="row align-items-center">
                <div class="col">
                    <form class="d-flex gap-2 flex-wrap" method="GET">
                        <input type="date" name="tanggal_dari" class="form-control form-control-sm"
                            value="{{ request('tanggal_dari') }}" style="width:145px">
                        <span class="align-self-center text-muted">s/d</span>
                        <input type="date" name="tanggal_sampai" class="form-control form-control-sm"
                            value="{{ request('tanggal_sampai') }}" style="width:145px">
                        <button class="btn btn-sm btn-primary">Filter</button>
                        @if (request()->hasAny(['tanggal_dari', 'tanggal_sampai']))
                            <a href="{{ route('admin.stock_opname.index') }}"
                                class="btn btn-sm btn-outline-secondary">Reset</a>
                        @endif
                    </form>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.stock_opname.create') }}" class="btn btn-sm btn-success">
                        <i class="bi bi-plus-lg me-1"></i> Catat Opname
                    </a>
                </div>
            </div>
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
                            <th>Dicatat oleh</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stockOpnames as $i => $so)
                            <tr>
                                <td class="text-muted small">{{ $stockOpnames->firstItem() + $i }}</td>
                                <td>{{ \Carbon\Carbon::parse($so->tanggal)->format('d/m/Y') }}</td>
                                <td class="fw-medium">{{ $so->barang?->nama_barang ?? '-' }}</td>
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
                                <td>{{ $so->user?->name ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.stock_opname.show', $so) }}"
                                        class="btn btn-sm btn-outline-info py-0 px-2">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="bi bi-clipboard-x fs-2 d-block mb-2"></i> Belum ada data stock opname.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($stockOpnames->hasPages())
            <div class="card-footer bg-white border-0">{{ $stockOpnames->links() }}</div>
        @endif
    </div>
@endsection
