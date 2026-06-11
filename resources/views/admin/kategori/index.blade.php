@extends('admin.layouts.app')

@section('title', 'Kategori')
@section('page_title', 'Kategori')
@section('breadcrumb', 'Master Data / Kategori')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="row align-items-center">
                <div class="col">
                    <form class="d-flex gap-2 flex-wrap" method="GET">
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Nama / kode kategori..." value="{{ request('search') }}" style="width:200px">
                        <button class="btn btn-sm btn-primary">Filter</button>
                        @if (request('search'))
                            <a href="{{ route('admin.kategori.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                        @endif
                    </form>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.kategori.create') }}" class="btn btn-sm btn-success">
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
                            <th>Kode</th>
                            <th>Nama Kategori</th>
                            <th>Keterangan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kategoris as $i => $k)
                            <tr>
                                <td class="text-muted small">{{ $kategoris->firstItem() + $i }}</td>
                                <td><code class="small">{{ $k->kode_kategori }}</code></td>
                                <td class="fw-medium">{{ $k->nama_kategori }}</td>
                                <td class="text-muted small">{{ $k->keterangan ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.kategori.edit', $k) }}"
                                        class="btn btn-sm btn-outline-primary py-0 px-2">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.kategori.destroy', $k) }}" class="d-inline"
                                        onsubmit="return confirm('Hapus kategori {{ $k->nama_kategori }}?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger py-0 px-2">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i> Belum ada data kategori.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($kategoris->hasPages())
            <div class="card-footer bg-white border-0">{{ $kategoris->links() }}</div>
        @endif
    </div>
@endsection
