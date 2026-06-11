@extends('owner.layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
    {{-- KPI Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 bg-primary bg-opacity-10">
                        <i class="bi bi-box-seam fs-3 text-primary"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Barang</div>
                        <div class="fs-4 fw-bold">{{ number_format($totalBarang) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 bg-danger bg-opacity-10">
                        <i class="bi bi-exclamation-triangle fs-3 text-danger"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Stok Kritis</div>
                        <div class="fs-4 fw-bold text-danger">{{ number_format($stokMinimum) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 bg-success bg-opacity-10">
                        <i class="bi bi-box-arrow-in-down fs-3 text-success"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Nilai Masuk (bulan ini)</div>
                        <div class="fs-5 fw-bold">Rp {{ number_format($totalMasukBulan, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 bg-warning bg-opacity-10">
                        <i class="bi bi-box-arrow-up fs-3 text-warning"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Nilai Keluar (bulan ini)</div>
                        <div class="fs-5 fw-bold">Rp {{ number_format($totalKeluarBulan, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Grafik --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold">Transaksi 6 Bulan Terakhir</h6>
                </div>
                <div class="card-body">
                    <canvas id="grafikTransaksi" height="100"></canvas>
                </div>
            </div>
        </div>

        {{-- Barang Kritis --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between">
                    <h6 class="mb-0 fw-bold">Stok Kritis</h6>
                    <a href="{{ route('owner.laporan.stok', ['stok_kritis' => 1]) }}" class="small text-primary">Lihat
                        semua</a>
                </div>
                <div class="card-body p-0">
                    @forelse($barangKritis as $b)
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                            <div>
                                <div class="fw-semibold small">{{ $b->nama_barang }}</div>
                                <small class="text-muted">{{ $b->kategori?->nama_kategori }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-danger">{{ $b->stok }} {{ $b->satuan?->nama_satuan }}</span>
                                <div class="small text-muted">min {{ $b->stok_minimum }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-check-circle text-success fs-3 d-block"></i>
                            Semua stok aman
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const masukData = @json($grafikMasuk);
        const keluarData = @json($grafikKeluar);

        const bulanNama = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        function toLabel(d) {
            return bulanNama[d.bulan - 1] + ' ' + d.tahun;
        }

        const allLabels = [...new Set([...masukData.map(toLabel), ...keluarData.map(toLabel)])];

        const masukMap = Object.fromEntries(masukData.map(d => [toLabel(d), d.total]));
        const keluarMap = Object.fromEntries(keluarData.map(d => [toLabel(d), d.total]));

        new Chart(document.getElementById('grafikTransaksi'), {
            type: 'bar',
            data: {
                labels: allLabels,
                datasets: [{
                        label: 'Masuk',
                        data: allLabels.map(l => masukMap[l] || 0),
                        backgroundColor: 'rgba(13,110,253,.7)'
                    },
                    {
                        label: 'Keluar',
                        data: allLabels.map(l => keluarMap[l] || 0),
                        backgroundColor: 'rgba(255,193,7,.7)'
                    },
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: v => 'Rp ' + (v / 1000000).toFixed(1) + 'jt'
                        }
                    }
                }
            }
        });
    </script>
@endpush
