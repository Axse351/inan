@extends('admin.layouts.app')

@section('title', 'Tambah Barang Masuk')
@section('page_title', 'Barang Masuk')
@section('breadcrumb', 'Transaksi / Barang Masuk / Tambah')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h6 class="mb-0 fw-bold">Form Barang Masuk</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.barang_masuk.store') }}" id="form-masuk">
                @csrf
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">No. Masuk</label>
                        <input type="text" class="form-control bg-light" value="{{ $nomor }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                            value="{{ old('tanggal', now()->format('Y-m-d')) }}" required>
                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Pemasok <span class="text-danger">*</span></label>
                        <select name="pemasok_id" class="form-select @error('pemasok_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Pemasok --</option>
                            @foreach ($pemasoks as $p)
                                <option value="{{ $p->id }}" @selected(old('pemasok_id') == $p->id)>{{ $p->nama_pemasok }}
                                </option>
                            @endforeach
                        </select>
                        @error('pemasok_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan') }}</textarea>
                    </div>
                </div>

                <h6 class="fw-bold mb-3">Detail Barang</h6>
                <div class="table-responsive mb-3">
                    <table class="table table-bordered align-middle" id="tabel-detail">
                        <thead class="table-light">
                            <tr>
                                <th>Barang</th>
                                <th style="width:100px">Qty</th>
                                <th style="width:160px">Harga Beli</th>
                                <th style="width:160px">Subtotal</th>
                                <th style="width:50px"></th>
                            </tr>
                        </thead>
                        <tbody id="detail-rows">
                            <tr class="detail-row">
                                <td>
                                    <select name="details[0][barang_id]" class="form-select form-select-sm barang-select"
                                        required>
                                        <option value="">-- Pilih Barang --</option>
                                        @foreach ($barangs as $b)
                                            <option value="{{ $b->id }}" data-harga="{{ $b->harga_beli }}">
                                                {{ $b->kode_barang }} - {{ $b->nama_barang }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="details[0][qty]"
                                        class="form-control form-control-sm qty-input" min="1" value="1"
                                        required></td>
                                <td><input type="number" name="details[0][harga_beli]"
                                        class="form-control form-control-sm harga-input" min="0" step="100"
                                        value="0" required></td>
                                <td><input type="text" class="form-control form-control-sm subtotal-display bg-light"
                                        readonly value="0"></td>
                                <td><button type="button" class="btn btn-sm btn-outline-danger btn-hapus"><i
                                            class="bi bi-trash"></i></button></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="3" class="text-end fw-bold">Total</td>
                                <td><input type="text" class="form-control form-control-sm bg-light fw-bold"
                                        id="grand-total" readonly value="Rp 0"></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <button type="button" class="btn btn-sm btn-outline-primary mb-4" id="btn-tambah-row">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Barang
                </button>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i> Simpan</button>
                    <a href="{{ route('admin.barang_masuk.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const barangs = @json($barangs->map(fn($b) => ['id' => $b->id, 'kode' => $b->kode_barang, 'nama' => $b->nama_barang, 'harga' => $b->harga_beli]));
        let rowIndex = 1;

        function buildOptions(selectedId = '') {
            let opts = '<option value="">-- Pilih Barang --</option>';
            barangs.forEach(b => {
                opts +=
                    `<option value="${b.id}" data-harga="${b.harga}" ${b.id == selectedId ? 'selected' : ''}>${b.kode} - ${b.nama}</option>`;
            });
            return opts;
        }

        function updateSubtotal(row) {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const harga = parseFloat(row.querySelector('.harga-input').value) || 0;
            const sub = qty * harga;
            row.querySelector('.subtotal-display').value = 'Rp ' + sub.toLocaleString('id-ID');
            return sub;
        }

        function updateGrandTotal() {
            let total = 0;
            document.querySelectorAll('.detail-row').forEach(row => {
                total += updateSubtotal(row);
            });
            document.getElementById('grand-total').value = 'Rp ' + total.toLocaleString('id-ID');
        }

        document.getElementById('btn-tambah-row').addEventListener('click', function() {
            const tbody = document.getElementById('detail-rows');
            const tr = document.createElement('tr');
            tr.className = 'detail-row';
            tr.innerHTML = `
        <td><select name="details[${rowIndex}][barang_id]" class="form-select form-select-sm barang-select" required>${buildOptions()}</select></td>
        <td><input type="number" name="details[${rowIndex}][qty]" class="form-control form-control-sm qty-input" min="1" value="1" required></td>
        <td><input type="number" name="details[${rowIndex}][harga_beli]" class="form-control form-control-sm harga-input" min="0" step="100" value="0" required></td>
        <td><input type="text" class="form-control form-control-sm subtotal-display bg-light" readonly value="0"></td>
        <td><button type="button" class="btn btn-sm btn-outline-danger btn-hapus"><i class="bi bi-trash"></i></button></td>
    `;
            tbody.appendChild(tr);
            bindRowEvents(tr);
            rowIndex++;
        });

        function bindRowEvents(row) {
            row.querySelector('.barang-select').addEventListener('change', function() {
                const opt = this.options[this.selectedIndex];
                if (opt.dataset.harga) row.querySelector('.harga-input').value = opt.dataset.harga;
                updateGrandTotal();
            });
            row.querySelector('.qty-input').addEventListener('input', updateGrandTotal);
            row.querySelector('.harga-input').addEventListener('input', updateGrandTotal);
            row.querySelector('.btn-hapus').addEventListener('click', function() {
                const rows = document.querySelectorAll('.detail-row');
                if (rows.length > 1) {
                    row.remove();
                    updateGrandTotal();
                }
            });
        }

        document.querySelectorAll('.detail-row').forEach(bindRowEvents);
    </script>
@endpush
