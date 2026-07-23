@extends('layouts.app')

@section('title', 'Data Barang')
@section('page_title', 'Data Barang')
@section('breadcrumb', 'Master Data / Barang')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="row align-items-center">
                <div class="col">
                    <form class="d-flex gap-2" method="GET">
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Cari kode / nama / barcode..." value="{{ request('search') }}" style="width:220px">

                        <div class="form-check form-check-inline align-self-center ms-1">
                            <input class="form-check-input" type="checkbox" name="stok_minimum" id="stok_minimum"
                                value="1" @checked(request('stok_minimum'))>
                            <label class="form-check-label small" for="stok_minimum">Stok kritis</label>
                        </div>

                        <select name="kategori" class="form-select form-select-sm" style="width:160px">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori }}" @selected(request('kategori') === $kategori)>
                                    {{ $kategori }}
                                </option>
                            @endforeach
                        </select>

                        <button class="btn btn-sm btn-primary">Cari</button>
                        @if (request()->hasAny(['search', 'kategori', 'stok_minimum']))
                            <a href="{{ route('admin.barang.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                        @endif
                    </form>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.barang.create') }}" class="btn btn-sm btn-success">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Barang
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
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Merk</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th class="text-end">Harga Beli</th>
                            <th class="text-end">Harga Jual</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangs as $i => $barang)
                            <tr>
                                <td class="text-muted small">{{ $barangs->firstItem() + $i }}</td>
                                <td><code class="small">{{ $barang->kode_barang }}</code></td>
                                <td>
                                    <div class="fw-semibold">{{ $barang->nama_barang }}</div>
                                    @if ($barang->lokasi_rak)
                                        <small class="text-muted"><i class="bi bi-geo-alt"></i>
                                            {{ $barang->lokasi_rak }}</small>
                                    @endif
                                </td>
                                <td>{{ $barang->merk ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $barang->kategori ?? '-' }}</span>
                                </td>
                                <td>{{ $barang->satuan?->nama_satuan ?? '-' }}</td>
                                <td class="text-end small">Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</td>
                                <td class="text-end small">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    @if ($barang->isStokMinimum())
                                        <span class="badge bg-danger">{{ $barang->stok }}</span>
                                    @else
                                        <span class="badge bg-success">{{ $barang->stok }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.barang.show', $barang) }}"
                                        class="btn btn-xs btn-outline-info btn-sm py-0 px-2">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.barang.edit', $barang) }}"
                                        class="btn btn-xs btn-outline-warning btn-sm py-0 px-2">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.barang.destroy', $barang) }}"
                                        class="d-inline" onsubmit="return confirm('Hapus barang ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-xs btn-outline-danger btn-sm py-0 px-2"><i
                                                class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i> Tidak ada data barang.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($barangs->hasPages())
            <div class="card-footer bg-white border-0">
                {{ $barangs->links() }}
            </div>
        @endif
    </div>
@endsection
