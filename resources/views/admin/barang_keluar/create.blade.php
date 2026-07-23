@extends('layouts.app')

@section('title', 'Tambah Barang Keluar')
@section('page_title', 'Tambah Barang Keluar')
@section('breadcrumb', 'Transaksi / Barang Keluar / Tambah')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <h6 class="mb-0 fw-semibold"><i class="bi bi-box-arrow-up me-2"></i>Form Barang Keluar</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.barang_keluar.store') }}" id="formKeluar">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-md-2">
                        <label class="form-label fw-medium">No. Keluar</label>
                        <input type="text" class="form-control bg-light" value="{{ $nomor }}" disabled>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-medium">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                            value="{{ old('tanggal', date('Y-m-d')) }}">
                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Tujuan / Instansi</label>
                        <input type="text" name="tujuan" class="form-control @error('tujuan') is-invalid @enderror"
                            value="{{ old('tujuan') }}" placeholder="Nama toko / instansi tujuan">
                        @error('tujuan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Keterangan</label>
                        <input type="text" name="keterangan"
                            class="form-control @error('keterangan') is-invalid @enderror" value="{{ old('keterangan') }}"
                            placeholder="Catatan tambahan (opsional)">
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-medium">Nama Penerima</label>
                        <input type="text" name="nama_penerima"
                            class="form-control @error('nama_penerima') is-invalid @enderror"
                            value="{{ old('nama_penerima') }}" placeholder="Kepada Yth. ...">
                        @error('nama_penerima')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-medium">No. Telepon</label>
                        <input type="text" name="no_telepon_penerima"
                            class="form-control @error('no_telepon_penerima') is-invalid @enderror"
                            value="{{ old('no_telepon_penerima') }}" placeholder="08xxxxxxxxxx">
                        @error('no_telepon_penerima')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-medium">Alamat</label>
                        <input type="text" name="alamat_penerima"
                            class="form-control @error('alamat_penerima') is-invalid @enderror"
                            value="{{ old('alamat_penerima') }}" placeholder="Alamat lengkap penerima">
                        @error('alamat_penerima')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Tabel Detail --}}
                <div class="table-responsive mb-3">
                    <table class="table table-bordered align-middle" id="tabelDetail">
                        <thead class="table-light">
                            <tr>
                                <th style="width:35%">Barang</th>
                                <th style="width:12%">Stok</th>
                                <th style="width:13%">Qty</th>
                                <th style="width:20%">Harga Jual (Rp)</th>
                                <th class="text-end" style="width:10%">Subtotal</th>
                                <th class="text-center" style="width:10%">Hapus</th>
                            </tr>
                        </thead>
                        <tbody id="detailRows"></tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="4" class="text-end fw-semibold">Total</td>
                                <td class="text-end fw-bold" id="grandTotal">Rp 0</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex gap-2 align-items-center flex-wrap mb-4">
                    <select id="pilihBarang" class="form-select form-select-sm" style="max-width:340px">
                        <option value="">— Pilih barang —</option>
                        @foreach ($barangs as $b)
                            <option value="{{ $b->id }}" data-nama="{{ $b->nama_barang }}"
                                data-kode="{{ $b->kode_barang }}" data-satuan="{{ $b->satuan?->nama_satuan ?? '' }}"
                                data-stok="{{ $b->stok }}" data-harga="{{ $b->harga_jual }}">
                                {{ $b->nama_barang }} ({{ $b->kode_barang }}) — Stok: {{ $b->stok }}
                            </option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="btnTambah">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Barang
                    </button>
                </div>

                @error('details')
                    <div class="text-danger small mb-2">{{ $message }}</div>
                @enderror

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary" id="btnSimpan" disabled>
                        <i class="bi bi-check-lg me-1"></i> Simpan
                    </button>
                    <a href="{{ route('admin.barang_keluar.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const addedIds = new Set();
        let idx = 0;

        function formatRp(n) {
            return 'Rp ' + parseInt(n || 0).toLocaleString('id-ID');
        }

        function hitungTotal() {
            let total = 0;
            document.querySelectorAll('.row-subtotal').forEach(el => {
                total += parseInt(el.dataset.subtotal || 0);
            });
            document.getElementById('grandTotal').textContent = formatRp(total);
        }

        function updateRow(tr) {
            const qty = parseInt(tr.querySelector('.inp-qty').value) || 0;
            const harga = parseInt(tr.querySelector('.inp-harga').value) || 0;
            const stok = parseInt(tr.dataset.stok);
            const sub = qty * harga;
            const subEl = tr.querySelector('.row-subtotal');
            subEl.textContent = formatRp(sub);
            subEl.dataset.subtotal = sub;

            // Peringatan jika qty melebihi stok
            const qtyInput = tr.querySelector('.inp-qty');
            if (qty > stok) {
                qtyInput.classList.add('is-invalid');
            } else {
                qtyInput.classList.remove('is-invalid');
            }
            hitungTotal();
        }

        document.getElementById('btnTambah').addEventListener('click', function() {
            const sel = document.getElementById('pilihBarang');
            const opt = sel.options[sel.selectedIndex];
            const id = sel.value;

            if (!id) return;
            if (addedIds.has(id)) {
                alert('Barang sudah ada dalam daftar.');
                return;
            }
            addedIds.add(id);

            const stok = opt.dataset.stok;
            const tr = document.createElement('tr');
            tr.dataset.id = id;
            tr.dataset.stok = stok;
            tr.innerHTML = `
            <td>
                <div class="fw-medium">${opt.dataset.nama}</div>
                <div class="text-muted small">${opt.dataset.kode} · ${opt.dataset.satuan}</div>
                <input type="hidden" name="details[${idx}][barang_id]" value="${id}">
            </td>
            <td class="text-muted small">${stok}</td>
            <td>
                <input type="number" name="details[${idx}][qty]" class="form-control form-control-sm inp-qty"
                    value="1" min="1" max="${stok}" required>
                <div class="invalid-feedback">Melebihi stok!</div>
            </td>
            <td>
                <input type="number" name="details[${idx}][harga_jual]" class="form-control form-control-sm inp-harga"
                    value="${opt.dataset.harga}" min="0" required>
            </td>
            <td class="text-end fw-medium row-subtotal" data-subtotal="${opt.dataset.harga}">${formatRp(opt.dataset.harga)}</td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 btn-hapus" data-id="${id}">
                    <i class="bi bi-x-lg"></i>
                </button>
            </td>
        `;

            document.getElementById('detailRows').appendChild(tr);
            sel.value = '';
            idx++;
            hitungTotal();
            document.getElementById('btnSimpan').disabled = false;

            tr.querySelector('.inp-qty').addEventListener('input', () => updateRow(tr));
            tr.querySelector('.inp-harga').addEventListener('input', () => updateRow(tr));
            tr.querySelector('.btn-hapus').addEventListener('click', function() {
                addedIds.delete(this.dataset.id);
                tr.remove();
                hitungTotal();
                document.getElementById('btnSimpan').disabled =
                    document.getElementById('detailRows').children.length === 0;
            });
        });
    </script>
@endpush
