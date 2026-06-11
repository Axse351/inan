@extends('admin.layouts.app')

@section('title', 'Detail Stock Opname')
@section('page_title', 'Detail Stock Opname')
@section('breadcrumb', 'Transaksi / Stock Opname / Detail')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-clipboard-check me-2"></i>Detail Stock Opname</h6>
                    <a href="{{ route('admin.stock_opname.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-muted fw-normal">Tanggal</dt>
                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse($stockOpname->tanggal)->format('d F Y') }}</dd>

                        <dt class="col-sm-4 text-muted fw-normal">Barang</dt>
                        <dd class="col-sm-8 fw-medium">{{ $stockOpname->barang?->nama_barang ?? '-' }}</dd>

                        <dt class="col-sm-4 text-muted fw-normal">Kode Barang</dt>
                        <dd class="col-sm-8"><code>{{ $stockOpname->barang?->kode_barang ?? '-' }}</code></dd>

                        <dt class="col-sm-4 text-muted fw-normal">Satuan</dt>
                        <dd class="col-sm-8">{{ $stockOpname->barang?->satuan?->nama_satuan ?? '-' }}</dd>

                        <dt class="col-sm-4 text-muted fw-normal">Stok Sistem</dt>
                        <dd class="col-sm-8">{{ $stockOpname->stok_sistem }}</dd>

                        <dt class="col-sm-4 text-muted fw-normal">Stok Fisik</dt>
                        <dd class="col-sm-8">{{ $stockOpname->stok_fisik }}</dd>

                        <dt class="col-sm-4 text-muted fw-normal">Selisih</dt>
                        <dd class="col-sm-8">
                            @if ($stockOpname->selisih > 0)
                                <span class="badge bg-success">+{{ $stockOpname->selisih }}</span>
                            @elseif ($stockOpname->selisih < 0)
                                <span class="badge bg-danger">{{ $stockOpname->selisih }}</span>
                            @else
                                <span class="badge bg-secondary">0</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4 text-muted fw-normal">Dicatat oleh</dt>
                        <dd class="col-sm-8">{{ $stockOpname->user?->name ?? '-' }}</dd>

                        <dt class="col-sm-4 text-muted fw-normal">Keterangan</dt>
                        <dd class="col-sm-8">{{ $stockOpname->keterangan ?? '-' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
