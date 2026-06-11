@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('breadcrumb', 'Ringkasan sistem inventaris spare parts')

@section('content')

    {{-- KPI Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 flex-shrink-0" style="background:#eaf0fb;">
                        <i class="bi bi-box-seam fs-4" style="color:#3d6fd4;"></i>
                    </div>
                    <div>
                        <div class="text-muted small mb-1">Total Barang</div>
                        <div class="fs-4 fw-bold lh-1">{{ $totalBarang }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 flex-shrink-0" style="background:#fef3e2;">
                        <i class="bi bi-exclamation-triangle fs-4" style="color:#e67e22;"></i>
                    </div>
                    <div>
                        <div class="text-muted small mb-1">Stok Menipis</div>
                        <div class="fs-4 fw-bold lh-1">{{ $stokMinimum }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 flex-shrink-0" style="background:#e8f8f0;">
                        <i class="bi bi-box-arrow-in-down fs-4" style="color:#1e8449;"></i>
                    </div>
                    <div>
                        <div class="text-muted small mb-1">Masuk Bulan Ini</div>
                        <div class="fs-4 fw-bold lh-1">{{ $masukBulanIni }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 flex-shrink-0" style="background:#fdecea;">
                        <i class="bi bi-box-arrow-up fs-4" style="color:#c0392b;"></i>
                    </div>
                    <div>
                        <div class="text-muted small mb-1">Keluar Bulan Ini</div>
                        <div class="fs-4 fw-bold lh-1">{{ $keluarBulanIni }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stok Menipis & Transaksi Terbaru --}}
    <div class="row g-3 mb-4">

        {{-- Stok Menipis --}}
        <div class="col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <span class="fw-semibold"><i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Stok
                        Menipis</span>
                    <a href="{{ route('admin.barang.index', ['stok_minimum' => 1]) }}"
                        class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    @forelse ($barangMenipis as $b)
                        <div class="d-flex align-items-center px-3 py-2 border-bottom gap-3">
                            <div class="flex-grow-1 min-w-0">
                                <div class="fw-medium text-truncate">{{ $b->nama_barang }}</div>
                                <div class="text-muted small">{{ $b->kode_barang }} ·
                                    {{ $b->kategori->nama_kategori ?? '-' }}</div>
                            </div>
                            <div class="text-end flex-shrink-0">
                                <span class="badge {{ $b->stok == 0 ? 'bg-danger' : 'bg-warning text-dark' }}">
                                    Stok: {{ $b->stok }}
                                </span>
                                <div class="text-muted small mt-1">Min: {{ $b->stok_minimum }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-check-circle fs-3 d-block mb-1 text-success"></i>
                            Semua stok dalam kondisi aman
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Transaksi Terbaru --}}
        <div class="col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <span class="fw-semibold"><i class="bi bi-clock-history text-primary me-2"></i>Transaksi Terbaru</span>
                </div>
                <div class="card-body p-0">
                    @forelse ($transaksiTerbaru as $t)
                        <div class="d-flex align-items-center px-3 py-2 border-bottom gap-3">
                            <div class="flex-shrink-0">
                                @if ($t['jenis'] === 'MASUK')
                                    <span class="badge rounded-pill bg-success"><i class="bi bi-arrow-down-short"></i>
                                        Masuk</span>
                                @else
                                    <span class="badge rounded-pill bg-danger"><i class="bi bi-arrow-up-short"></i>
                                        Keluar</span>
                                @endif
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="fw-medium text-truncate">{{ $t['nomor'] }}</div>
                                <div class="text-muted small">{{ $t['keterangan'] }}</div>
                            </div>
                            <div class="text-muted small flex-shrink-0">
                                {{ \Carbon\Carbon::parse($t['tanggal'])->format('d M Y') }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                            Belum ada transaksi
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Ringkasan Bawah --}}
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Total Kategori</div>
                        <div class="fs-5 fw-bold">{{ $totalKategori }}</div>
                    </div>
                    <i class="bi bi-tags fs-2 text-secondary opacity-50"></i>
                </div>
                <div class="card-footer bg-white border-top-0 pt-0">
                    <a href="{{ route('admin.kategori.index') }}" class="small text-decoration-none">Kelola Kategori →</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Total Pemasok</div>
                        <div class="fs-5 fw-bold">{{ $totalPemasok }}</div>
                    </div>
                    <i class="bi bi-truck fs-2 text-secondary opacity-50"></i>
                </div>
                <div class="card-footer bg-white border-top-0 pt-0">
                    <a href="{{ route('admin.pemasok.index') }}" class="small text-decoration-none">Kelola Pemasok →</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-muted small">Nilai Stok (HPP)</div>
                        <div class="fs-5 fw-bold">Rp {{ number_format($nilaiStok, 0, ',', '.') }}</div>
                    </div>
                    <i class="bi bi-currency-dollar fs-2 text-secondary opacity-50"></i>
                </div>
                <div class="card-footer bg-white border-top-0 pt-0">
                    <span class="small text-muted">Berdasarkan harga beli</span>
                </div>
            </div>
        </div>
    </div>

@endsection
