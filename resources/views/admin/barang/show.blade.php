@extends('layouts.app')

@section('title', 'Detail Barang')
@section('page_title', 'Detail Barang')
@section('breadcrumb', 'Master Data / Barang / Detail')

@section('content')
    <div class="row g-3">
        {{-- Info Utama --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-box-seam me-2"></i>Info Barang</h6>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.barang.edit', $barang) }}" class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-pencil me-1"></i> Edit
                        </a>
                        <a href="{{ route('admin.barang.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5 text-muted fw-normal">Kode Barang</dt>
                        <dd class="col-sm-7"><code>{{ $barang->kode_barang }}</code></dd>

                        <dt class="col-sm-5 text-muted fw-normal">Barcode</dt>
                        <dd class="col-sm-7">{{ $barang->barcode ?? '-' }}</dd>

                        <dt class="col-sm-5 text-muted fw-normal">Nama Barang</dt>
                        <dd class="col-sm-7 fw-semibold">{{ $barang->nama_barang }}</dd>

                        <dt class="col-sm-5 text-muted fw-normal">Kategori</dt>
                        <dd class="col-sm-7">
                            <span class="badge bg-secondary">{{ $barang->kategori?->nama_kategori ?? '-' }}</span>
                        </dd>

                        <dt class="col-sm-5 text-muted fw-normal">Satuan</dt>
                        <dd class="col-sm-7">{{ $barang->satuan?->nama_satuan ?? '-' }}</dd>

                        <dt class="col-sm-5 text-muted fw-normal">Pemasok</dt>
                        <dd class="col-sm-7">{{ $barang->pemasok?->nama_pemasok ?? '-' }}</dd>

                        <dt class="col-sm-5 text-muted fw-normal">Lokasi Rak</dt>
                        <dd class="col-sm-7">{{ $barang->lokasi_rak ?? '-' }}</dd>

                        <dt class="col-sm-5 text-muted fw-normal">Deskripsi</dt>
                        <dd class="col-sm-7 text-muted small">{{ $barang->deskripsi ?? '-' }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Harga & Stok --}}
        <div class="col-lg-7">
            <div class="row g-3 mb-3">
                <div class="col-sm-4">
                    <div class="card border-0 shadow-sm text-center py-3">
                        <div class="text-muted small mb-1">Harga Beli</div>
                        <div class="fs-6 fw-bold">Rp {{ number_format($barang->harga_beli, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card border-0 shadow-sm text-center py-3">
                        <div class="text-muted small mb-1">Harga Jual</div>
                        <div class="fs-6 fw-bold">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div
                        class="card border-0 shadow-sm text-center py-3 {{ $barang->stok <= $barang->stok_minimum ? 'border-danger border' : '' }}">
                        <div class="text-muted small mb-1">Stok</div>
                        <div
                            class="fs-4 fw-bold {{ $barang->stok <= $barang->stok_minimum ? 'text-danger' : 'text-success' }}">
                            {{ $barang->stok }}
                        </div>
                        <div class="text-muted" style="font-size:.7rem">Min: {{ $barang->stok_minimum }}</div>
                    </div>
                </div>
            </div>

            {{-- Riwayat Mutasi --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-clock-history me-2"></i>Riwayat Mutasi Stok (10 Terakhir)
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jenis</th>
                                    <th class="text-end">Qty</th>
                                    <th class="text-end">Sebelum</th>
                                    <th class="text-end">Sesudah</th>
                                    <th>Referensi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($barang->stokMutasis as $m)
                                    <tr>
                                        <td class="small">{{ \Carbon\Carbon::parse($m->tanggal)->format('d/m/Y') }}</td>
                                        <td>
                                            @if ($m->jenis === 'MASUK')
                                                <span class="badge bg-success">Masuk</span>
                                            @elseif ($m->jenis === 'KELUAR')
                                                <span class="badge bg-danger">Keluar</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Adjustment</span>
                                            @endif
                                        </td>
                                        <td class="text-end small">{{ $m->qty }}</td>
                                        <td class="text-end small text-muted">{{ $m->stok_sebelum }}</td>
                                        <td class="text-end small fw-medium">{{ $m->stok_sesudah }}</td>
                                        <td class="small"><code>{{ $m->referensi }}</code></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">
                                            <i class="bi bi-inbox me-1"></i> Belum ada riwayat mutasi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

