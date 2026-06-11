@extends('admin.layouts.app')

@section('title', 'Pemasok')
@section('page_title', 'Pemasok')
@section('breadcrumb', 'Master Data / Pemasok')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="row align-items-center">
                <div class="col">
                    <form class="d-flex gap-2 flex-wrap" method="GET">
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Nama / kode pemasok..." value="{{ request('search') }}" style="width:200px">
                        <button class="btn btn-sm btn-primary">Filter</button>
                        @if (request('search'))
                            <a href="{{ route('admin.pemasok.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                        @endif
                    </form>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.pemasok.create') }}" class="btn btn-sm btn-success">
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
                            <th>Nama Pemasok</th>
                            <th>Nama Kontak</th>
                            <th>Telepon</th>
                            <th>Email</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pemasoks as $i => $p)
                            <tr>
                                <td class="text-muted small">{{ $pemasoks->firstItem() + $i }}</td>
                                <td><code class="small">{{ $p->kode_pemasok }}</code></td>
                                <td class="fw-medium">{{ $p->nama_pemasok }}</td>
                                <td>{{ $p->nama_kontak ?? '-' }}</td>
                                <td>{{ $p->telepon ?? '-' }}</td>
                                <td>{{ $p->email ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.pemasok.edit', $p) }}"
                                        class="btn btn-sm btn-outline-primary py-0 px-2">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.pemasok.destroy', $p) }}" class="d-inline"
                                        onsubmit="return confirm('Hapus pemasok {{ $p->nama_pemasok }}?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger py-0 px-2">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i> Belum ada data pemasok.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($pemasoks->hasPages())
            <div class="card-footer bg-white border-0">{{ $pemasoks->links() }}</div>
        @endif
    </div>
@endsection
