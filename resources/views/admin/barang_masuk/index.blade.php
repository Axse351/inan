@extends('layouts.app')

@section('title', 'Barang Masuk')
@section('page_title', 'Barang Masuk')
@section('breadcrumb', 'Transaksi / Barang Masuk')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="row align-items-center">
                <div class="col">
                    <form class="d-flex gap-2 flex-wrap" method="GET">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="No. masuk..."
                            value="{{ request('search') }}" style="width:160px">
                        <input type="date" name="tanggal_dari" class="form-control form-control-sm"
                            value="{{ request('tanggal_dari') }}" style="width:145px">
                        <span class="align-self-center text-muted">s/d</span>
                        <input type="date" name="tanggal_sampai" class="form-control form-control-sm"
                            value="{{ request('tanggal_sampai') }}" style="width:145px">
                        <button class="btn btn-sm btn-primary">Filter</button>
                        @if (request()->hasAny(['search', 'tanggal_dari', 'tanggal_sampai']))
                            <a href="{{ route('admin.barang_masuk.index') }}"
                                class="btn btn-sm btn-outline-secondary">Reset</a>
                        @endif
                    </form>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.barang_masuk.create') }}" class="btn btn-sm btn-success">
                        <i class="bi bi-plus-lg me-1"></i> Tambah
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
                            <th>No. Masuk</th>
                            <th>Tanggal</th>
                            <th>Pemasok</th>
                            <th class="text-end">Total</th>
                            <th>Dicatat oleh</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangMasuks as $i => $bm)
                            <tr>
                                <td class="text-muted small">{{ $barangMasuks->firstItem() + $i }}</td>
                                <td><code class="small">{{ $bm->nomor_masuk }}</code></td>
                                <td>{{ $bm->tanggal->format('d/m/Y') }}</td>
                                <td>{{ $bm->pemasok?->nama_pemasok ?? '-' }}</td>
                                <td class="text-end">Rp {{ number_format($bm->total_harga, 0, ',', '.') }}</td>
                                <td>{{ $bm->user?->name ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.barang_masuk.show', $bm) }}"
                                        class="btn btn-sm btn-outline-info py-0 px-2">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.barang_masuk.destroy', $bm) }}"
                                        class="d-inline"
                                        onsubmit="return confirm('Batalkan transaksi ini? Stok akan dikembalikan.')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger py-0 px-2"><i
                                                class="bi bi-x-circle"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i> Belum ada data barang masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($barangMasuks->hasPages())
            <div class="card-footer bg-white border-0">{{ $barangMasuks->links() }}</div>
        @endif
    </div>
@endsection

