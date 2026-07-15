@extends('layouts.app')

@section('title', 'Satuan')
@section('page_title', 'Satuan')
@section('breadcrumb', 'Master Data / Satuan')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="row align-items-center">
                <div class="col">
                    <p class="mb-0 text-muted small">Daftar satuan ukuran barang (pcs, unit, set, dll.)</p>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.satuan.create') }}" class="btn btn-sm btn-success">
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
                            <th>Nama Satuan</th>
                            <th>Keterangan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($satuans as $i => $s)
                            <tr>
                                <td class="text-muted small">{{ $satuans->firstItem() + $i }}</td>
                                <td class="fw-medium">{{ $s->nama_satuan }}</td>
                                <td class="text-muted small">{{ $s->keterangan ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.satuan.edit', $s) }}"
                                        class="btn btn-sm btn-outline-primary py-0 px-2">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.satuan.destroy', $s) }}" class="d-inline"
                                        onsubmit="return confirm('Hapus satuan {{ $s->nama_satuan }}?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger py-0 px-2">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i> Belum ada data satuan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($satuans->hasPages())
            <div class="card-footer bg-white border-0">{{ $satuans->links() }}</div>
        @endif
    </div>
@endsection

