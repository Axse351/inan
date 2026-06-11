@extends('admin.layouts.app')

@section('title', 'Catat Stock Opname')
@section('page_title', 'Catat Stock Opname')
@section('breadcrumb', 'Transaksi / Stock Opname / Catat')

@push('styles')
    <style>
        .selisih-plus {
            color: #1e8449;
            font-weight: 600;
        }

        .selisih-minus {
            color: #c0392b;
            font-weight: 600;
        }

        .selisih-zero {
            color: #7f8c8d;
        }
    </style>
@endpush

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <h6 class="mb-0 fw-semibold"><i class="bi bi-clipboard-check me-2"></i>Form Stock Opname</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.stock_opname.store') }}" id="formOpname">
                @csrf

                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label fw-medium">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                            value="{{ old('tanggal', date('Y-m-d')) }}">
                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-9">
                        <label class="form-label fw-medium">Keterangan</label>
                        <input type="text" name="keterangan"
                            class="form-control @error('keterangan') is-invalid @enderror" value="{{ old('keterangan') }}"
                            placeholder="Keterangan opname (opsional)">
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0" id="tabelOpname">
                        <thead class="table-light">
                            <tr>
                                <th style="width:40%">Barang</th>
                                <th class="text-end" style="width:15%">Stok Sistem</th>
                                <th style="width:20%">Stok Fisik</th>
                                <th class="text-end" style="width:15%">Selisih</th>
                                <th class="text-center" style="width:10%">Hapus</th>
                            </tr>
                        </thead>
                        <tbody id="opnameRows">
                            {{-- Rows diisi via JS --}}
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 d-flex gap-2 align-items-center flex-wrap">
                    <select id="pilihBarang" class="form-select form-select-sm" style="max-width:320px">
                        <option value="">— Pilih barang untuk ditambahkan —</option>
                        @foreach ($barangs as $b)
                            <option value="{{ $b->id }}" data-nama="{{ $b->nama_barang }}"
                                data-kode="{{ $b->kode_barang }}" data-satuan="{{ $b->satuan?->nama_satuan ?? '' }}"
                                data-stok="{{ $b->stok }}">
                                {{ $b->nama_barang }} ({{ $b->kode_barang }}) — Stok: {{ $b->stok }}
                            </option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="btnTambahBaris">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Barang
                    </button>
                </div>

                @error('items')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                @enderror

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary" id="btnSimpan" disabled>
                        <i class="bi bi-check-lg me-1"></i> Simpan Opname
                    </button>
                    <a href="{{ route('admin.stock_opname.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const addedIds = new Set();
        let rowIndex = 0;

        document.getElementById('btnTambahBaris').addEventListener('click', function() {
            const sel = document.getElementById('pilihBarang');
            const opt = sel.options[sel.selectedIndex];
            const id = sel.value;

            if (!id) return;
            if (addedIds.has(id)) {
                alert('Barang ini sudah ada dalam daftar.');
                return;
            }

            addedIds.add(id);

            const nama = opt.dataset.nama;
            const kode = opt.dataset.kode;
            const satuan = opt.dataset.satuan;
            const stok = parseInt(opt.dataset.stok);
            const idx = rowIndex++;

            const tr = document.createElement('tr');
            tr.dataset.id = id;
            tr.innerHTML = `
            <td>
                <div class="fw-medium">${nama}</div>
                <div class="text-muted small">${kode} · ${satuan}</div>
                <input type="hidden" name="items[${idx}][barang_id]" value="${id}">
            </td>
            <td class="text-end fw-medium">${stok}</td>
            <td>
                <input type="number" name="items[${idx}][stok_fisik]" class="form-control form-control-sm stok-fisik"
                    value="${stok}" min="0" data-sistem="${stok}" required>
            </td>
            <td class="text-end selisih-cell"><span class="selisih-zero">0</span></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 btn-hapus" data-id="${id}">
                    <i class="bi bi-x-lg"></i>
                </button>
            </td>
        `;

            document.getElementById('opnameRows').appendChild(tr);
            sel.value = '';
            updateSimpan();

            // Hitung selisih realtime
            tr.querySelector('.stok-fisik').addEventListener('input', function() {
                const fisik = parseInt(this.value) || 0;
                const sistem = parseInt(this.dataset.sistem);
                const selisih = fisik - sistem;
                const cell = tr.querySelector('.selisih-cell');
                const cls = selisih > 0 ? 'selisih-plus' : selisih < 0 ? 'selisih-minus' : 'selisih-zero';
                cell.innerHTML = `<span class="${cls}">${selisih > 0 ? '+' : ''}${selisih}</span>`;
            });

            // Hapus baris
            tr.querySelector('.btn-hapus').addEventListener('click', function() {
                addedIds.delete(this.dataset.id);
                tr.remove();
                updateSimpan();
            });
        });

        function updateSimpan() {
            document.getElementById('btnSimpan').disabled =
                document.getElementById('opnameRows').children.length === 0;
        }
    </script>
@endpush
