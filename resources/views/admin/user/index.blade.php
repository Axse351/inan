@extends('admin.layouts.app')

@section('title', 'User')
@section('page_title', 'Manajemen User')
@section('breadcrumb', 'Sistem / User')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="row align-items-center">
                <div class="col">
                    <p class="mb-0 text-muted small">Daftar pengguna sistem</p>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.user.create') }}" class="btn btn-sm btn-success">
                        <i class="bi bi-plus-lg me-1"></i> Tambah User
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
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No. HP</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $i => $u)
                            <tr>
                                <td class="text-muted small">{{ $users->firstItem() + $i }}</td>
                                <td class="fw-medium">
                                    {{ $u->name }}
                                    @if ($u->id === auth()->id())
                                        <span class="badge bg-light text-secondary border ms-1">Anda</span>
                                    @endif
                                </td>
                                <td>{{ $u->email }}</td>
                                <td>{{ $u->no_hp ?? '-' }}</td>
                                <td>
                                    <span
                                        class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">
                                        {{ $u->role?->nama_role ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    @if ($u->status === 'aktif')
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.user.edit', $u) }}"
                                        class="btn btn-sm btn-outline-primary py-0 px-2">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if ($u->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.user.destroy', $u) }}" class="d-inline"
                                            onsubmit="return confirm('Hapus user {{ $u->name }}?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger py-0 px-2">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="bi bi-people fs-2 d-block mb-2"></i> Belum ada data user.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($users->hasPages())
            <div class="card-footer bg-white border-0">{{ $users->links() }}</div>
        @endif
    </div>
@endsection
