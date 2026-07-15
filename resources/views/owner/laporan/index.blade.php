@extends('layouts.owner')

@section('title', 'Laporan')

@section('content')
    <div class="container-fluid py-4">

        <div class="mb-4">
            <p class="text-muted small text-uppercase fw-semibold mb-1" style="letter-spacing:.05em">Manajemen Inventaris</p>
            <h4 class="fw-semibold mb-1">Laporan</h4>
            <p class="text-muted mb-0">Pantau kondisi stok, arus barang masuk &amp; keluar, serta riwayat mutasi.</p>
        </div>

        <div class="row g-3">

            {{-- Stok Barang --}}
            <div class="col-md-6 col-xl-3">
                <a href="{{ route('owner.laporan.stok') }}" class="text-decoration-none">
                    <div class="card h-100 border shadow-sm hover-shadow transition">
                        <div class="card-body d-flex flex-column gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-2 p-2 bg-primary-subtle text-primary fs-4">
                                    <i class="ti ti-box"></i>
                                </div>
                                <h6 class="fw-semibold mb-0 text-body">Stok Barang</h6>
                            </div>
                            <p class="text-muted small mb-0">
                                Lihat saldo stok semua barang, filter per kategori, atau tampilkan barang yang mendekati
                                stok minimum.
                            </p>
                            <div class="d-flex align-items-center justify-content-between mt-auto pt-2 border-top">
                                <span class="badge bg-primary-subtle text-primary fw-normal" style="font-size:.75rem">
                                    Filter kategori &amp; stok kritis
                                </span>
                                <i class="ti ti-arrow-right text-muted"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Barang Masuk --}}
            <div class="col-md-6 col-xl-3">
                <a href="{{ route('owner.laporan.masuk') }}" class="text-decoration-none">
                    <div class="card h-100 border shadow-sm">
                        <div class="card-body d-flex flex-column gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-2 p-2 bg-success-subtle text-success fs-4">
                                    <i class="ti ti-arrow-bar-to-down"></i>
                                </div>
                                <h6 class="fw-semibold mb-0 text-body">Barang Masuk</h6>
                            </div>
                            <p class="text-muted small mb-0">
                                Rekap penerimaan barang dari pemasok berdasarkan rentang tanggal beserta total nilai
                                pembelian.
                            </p>
                            <div class="d-flex align-items-center justify-content-between mt-auto pt-2 border-top">
                                <span class="badge bg-success-subtle text-success fw-normal" style="font-size:.75rem">
                                    Filter tanggal · total nilai
                                </span>
                                <i class="ti ti-arrow-right text-muted"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Barang Keluar --}}
            <div class="col-md-6 col-xl-3">
                <a href="{{ route('owner.laporan.keluar') }}" class="text-decoration-none">
                    <div class="card h-100 border shadow-sm">
                        <div class="card-body d-flex flex-column gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-2 p-2 bg-warning-subtle text-warning fs-4">
                                    <i class="ti ti-arrow-bar-up"></i>
                                </div>
                                <h6 class="fw-semibold mb-0 text-body">Barang Keluar</h6>
                            </div>
                            <p class="text-muted small mb-0">
                                Rekap pengeluaran barang berdasarkan rentang tanggal beserta total nilai yang dikeluarkan.
                            </p>
                            <div class="d-flex align-items-center justify-content-between mt-auto pt-2 border-top">
                                <span class="badge bg-warning-subtle text-warning fw-normal" style="font-size:.75rem">
                                    Filter tanggal · total nilai
                                </span>
                                <i class="ti ti-arrow-right text-muted"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Mutasi Stok --}}
            <div class="col-md-6 col-xl-3">
                <a href="{{ route('owner.laporan.mutasi') }}" class="text-decoration-none">
                    <div class="card h-100 border shadow-sm">
                        <div class="card-body d-flex flex-column gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-2 p-2 bg-info-subtle text-info fs-4">
                                    <i class="ti ti-transfer"></i>
                                </div>
                                <h6 class="fw-semibold mb-0 text-body">Mutasi Stok</h6>
                            </div>
                            <p class="text-muted small mb-0">
                                Riwayat lengkap perubahan stok per barang — filter berdasarkan jenis mutasi, barang, atau
                                periode.
                            </p>
                            <div class="d-flex align-items-center justify-content-between mt-auto pt-2 border-top">
                                <span class="badge bg-info-subtle text-info fw-normal" style="font-size:.75rem">
                                    Filter barang · jenis · tanggal
                                </span>
                                <i class="ti ti-arrow-right text-muted"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
@endsection

