@extends('layouts.app')

@section('title', 'Barang Keluar')
@section('page_title', 'Barang Keluar')
@section('breadcrumb', 'Transaksi / Barang Keluar')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="row align-items-center">
                <div class="col">
                    <form class="d-flex gap-2 flex-wrap" method="GET">
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="No. keluar / tujuan..." value="{{ request('search') }}" style="width:180px">
                        <input type="date" name="tanggal_dari" class="form-control form-control-sm"
                            value="{{ request('tanggal_dari') }}" style="width:145px">
                        <span class="align-self-center text-muted small">s/d</span>
                        <input type="date" name="tanggal_sampai" class="form-control form-control-sm"
                            value="{{ request('tanggal_sampai') }}" style="width:145px">
                        <button class="btn btn-sm btn-primary">Filter</button>
                        @if (request()->hasAny(['search', 'tanggal_dari', 'tanggal_sampai']))
                            <a href="{{ route('admin.barang_keluar.index') }}"
                                class="btn btn-sm btn-outline-secondary">Reset</a>
                        @endif
                    </form>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.barang_keluar.create') }}" class="btn btn-sm btn-success">
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
                            <th style="width:50px">#</th>
                            <th>No. Keluar</th>
                            <th>Tanggal</th>
                            <th>Tujuan</th>
                            <th class="text-end">Total</th>
                            <th>Dicatat oleh</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangKeluars as $i => $bk)
                            <tr>
                                <td class="text-muted small">{{ $barangKeluars->firstItem() + $i }}</td>
                                <td><code class="small">{{ $bk->nomor_keluar }}</code></td>
                                <td>{{ $bk->tanggal->format('d/m/Y') }}</td>
                                <td>{{ $bk->tujuan ?? '-' }}</td>
                                <td class="text-end fw-semibold">Rp {{ number_format($bk->total_harga, 0, ',', '.') }}</td>
                                <td>{{ $bk->user?->name ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.barang_keluar.show', $bk) }}"
                                        class="btn btn-sm btn-outline-info py-0 px-2" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.barang_keluar.destroy', $bk) }}"
                                        class="d-inline"
                                        onsubmit="return confirm('Batalkan transaksi ini? Stok akan dikembalikan.')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger py-0 px-2" title="Batalkan">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    Belum ada data barang keluar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($barangKeluars->hasPages())
            <div class="card-footer bg-white border-0">
                {{ $barangKeluars->links() }}
            </div>
        @endif
    </div>
@endsection

