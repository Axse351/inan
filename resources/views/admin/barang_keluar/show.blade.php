@extends('layouts.app')

@section('title', 'Detail Barang Keluar')
@section('page_title', 'Detail Barang Keluar')
@section('breadcrumb', 'Transaksi / Barang Keluar / Detail')

@section('content')
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-semibold">
                <i class="bi bi-box-arrow-up me-2"></i>
                {{ $barangKeluar->nomor_keluar }}
            </h6>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.barang_keluar.invoice', $barangKeluar) }}" target="_blank"
                    class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Cetak Invoice
                </a>
                <a href="{{ route('admin.barang_keluar.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-3 mb-2">
                <div class="col-md-3">
                    <div class="text-muted small">Tanggal</div>
                    <div class="fw-medium">{{ $barangKeluar->tanggal->format('d/m/Y') }}</div>
                </div>
                <div class="col-md-3">
                    <div class="text-muted small">Tujuan</div>
                    <div class="fw-medium">{{ $barangKeluar->tujuan ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="text-muted small">Dicatat oleh</div>
                    <div class="fw-medium">{{ $barangKeluar->user?->name ?? '-' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="text-muted small">Total</div>
                    <div class="fw-bold text-success">Rp {{ number_format($barangKeluar->total_harga, 0, ',', '.') }}</div>
                </div>
            </div>

            @if ($barangKeluar->nama_penerima || $barangKeluar->no_telepon_penerima || $barangKeluar->alamat_penerima)
                <hr>
                <div class="row g-3 mb-2">
                    <div class="col-md-4">
                        <div class="text-muted small">Nama Penerima</div>
                        <div class="fw-medium">{{ $barangKeluar->nama_penerima ?? '-' }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted small">No. Telepon</div>
                        <div class="fw-medium">{{ $barangKeluar->no_telepon_penerima ?? '-' }}</div>
                    </div>
                    <div class="col-md-5">
                        <div class="text-muted small">Alamat</div>
                        <div class="fw-medium">{{ $barangKeluar->alamat_penerima ?? '-' }}</div>
                    </div>
                </div>
            @endif

            @if ($barangKeluar->keterangan)
                <hr>
                <div class="text-muted small">Keterangan</div>
                <div>{{ $barangKeluar->keterangan }}</div>
            @endif
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h6 class="mb-0 fw-semibold"><i class="bi bi-list-ul me-2"></i>Detail Barang</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:50px">#</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Harga Jual</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($barangKeluar->details as $i => $detail)
                            <tr>
                                <td class="text-muted small">{{ $i + 1 }}</td>
                                <td class="fw-medium">{{ $detail->barang?->nama_barang ?? '-' }}</td>
                                <td>{{ $detail->barang?->satuan?->nama_satuan ?? '-' }}</td>
                                <td class="text-center">{{ $detail->qty }}</td>
                                <td class="text-end">Rp {{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                                <td class="text-end fw-medium">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Tidak ada detail barang.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="5" class="text-end fw-semibold">Total</td>
                            <td class="text-end fw-bold">Rp {{ number_format($barangKeluar->total_harga, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
