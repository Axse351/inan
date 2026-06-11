@extends('admin.layouts.app')

@section('title', 'Detail Barang Masuk')
@section('page_title', 'Detail Barang Masuk')
@section('breadcrumb', 'Transaksi / Barang Masuk / Detail')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-box-arrow-in-down me-2"></i>
                        {{ $barangMasuk->nomor_masuk }}
                    </h6>
                    <a href="{{ route('admin.barang_masuk.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>

                <div class="card-body border-bottom">
                    <div class="row g-2">
                        <div class="col-sm-3">
                            <div class="text-muted small">Tanggal</div>
                            <div class="fw-medium">{{ $barangMasuk->tanggal->format('d F Y') }}</div>
                        </div>
                        <div class="col-sm-3">
                            <div class="text-muted small">Pemasok</div>
                            <div class="fw-medium">{{ $barangMasuk->pemasok?->nama_pemasok ?? '-' }}</div>
                        </div>
                        <div class="col-sm-3">
                            <div class="text-muted small">Dicatat oleh</div>
                            <div class="fw-medium">{{ $barangMasuk->user?->name ?? '-' }}</div>
                        </div>
                        <div class="col-sm-3">
                            <div class="text-muted small">Keterangan</div>
                            <div class="fw-medium">{{ $barangMasuk->keterangan ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Barang</th>
                                    <th>Satuan</th>
                                    <th class="text-end">Qty</th>
                                    <th class="text-end">Harga Beli</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($barangMasuk->details as $i => $d)
                                    <tr>
                                        <td class="text-muted small">{{ $i + 1 }}</td>
                                        <td>
                                            <div class="fw-medium">{{ $d->barang?->nama_barang }}</div>
                                            <code class="small">{{ $d->barang?->kode_barang }}</code>
                                        </td>
                                        <td>{{ $d->barang?->satuan?->nama_satuan ?? '-' }}</td>
                                        <td class="text-end">{{ $d->qty }}</td>
                                        <td class="text-end">Rp {{ number_format($d->harga_beli, 0, ',', '.') }}</td>
                                        <td class="text-end fw-medium">Rp {{ number_format($d->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="5" class="text-end fw-semibold">Total</td>
                                    <td class="text-end fw-bold">Rp
                                        {{ number_format($barangMasuk->total_harga, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
