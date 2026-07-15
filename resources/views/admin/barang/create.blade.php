@extends('layouts.app')

@section('title', 'Tambah Barang')
@section('page_title', 'Tambah Barang')
@section('breadcrumb', 'Master Data / Barang / Tambah')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-9">

            @if ($errors->any())
                <div class="alert alert-danger d-flex align-items-start gap-2" id="alertErrors">
                    <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                    <div>
                        <div class="fw-semibold mb-1">Ada {{ $errors->count() }} isian yang perlu diperbaiki:</div>
                        <ul class="mb-0 ps-3 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-box-seam me-2"></i>Form Tambah Barang</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.barang.store') }}" id="formBarang" novalidate>
                        @csrf

                        <div class="row g-3">
                            {{-- Identitas --}}
                            <div class="col-12">
                                <h6 class="text-muted fw-semibold small text-uppercase mb-0">Identitas Barang</h6>
                                <hr class="mt-1">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-medium">Kode Barang <span class="text-danger">*</span></label>
                                <input type="text" name="kode_barang"
                                    class="form-control @error('kode_barang') is-invalid @enderror"
                                    value="{{ old('kode_barang') }}" placeholder="cth: BRG-001">
                                @error('kode_barang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-medium">Barcode</label>
                                <input type="text" name="barcode"
                                    class="form-control @error('barcode') is-invalid @enderror" value="{{ old('barcode') }}"
                                    placeholder="Opsional">
                                @error('barcode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">Nama Barang <span class="text-danger">*</span></label>
                                <input type="text" name="nama_barang"
                                    class="form-control @error('nama_barang') is-invalid @enderror"
                                    value="{{ old('nama_barang') }}" placeholder="Nama lengkap barang">
                                @error('nama_barang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-medium">Merk</label>
                                <input type="text" name="merk"
                                    class="form-control @error('merk') is-invalid @enderror" value="{{ old('merk') }}"
                                    placeholder="cth: Yamaha, Denso, Aspira">
                                @error('merk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-medium">
                                    Kategori <span class="text-danger">*</span>
                                </label>

                                <input type="text" name="kategori"
                                    class="form-control @error('kategori') is-invalid @enderror"
                                    value="{{ old('kategori') }}" placeholder="Contoh: Oli, Ban, Aki">

                                @error('kategori')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-medium">Satuan <span class="text-danger">*</span></label>
                                <select name="satuan_id" class="form-select @error('satuan_id') is-invalid @enderror">
                                    <option value="">— Pilih Satuan —</option>
                                    @foreach ($satuans as $s)
                                        <option value="{{ $s->id }}"
                                            {{ old('satuan_id') == $s->id ? 'selected' : '' }}>
                                            {{ $s->nama_satuan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('satuan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">Pemasok Utama</label>
                                <select name="pemasok_id" class="form-select @error('pemasok_id') is-invalid @enderror">
                                    <option value="">— Pilih Pemasok —</option>
                                    @foreach ($pemasoks as $p)
                                        <option value="{{ $p->id }}"
                                            {{ old('pemasok_id') == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama_pemasok }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pemasok_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Harga & Stok --}}
                            <div class="col-12 mt-2">
                                <h6 class="text-muted fw-semibold small text-uppercase mb-0">Harga & Stok</h6>
                                <hr class="mt-1">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-medium">Harga Beli <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="harga_beli"
                                        class="form-control @error('harga_beli') is-invalid @enderror"
                                        value="{{ old('harga_beli', 0) }}" min="0">
                                    @error('harga_beli')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-medium">Harga Jual <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="harga_jual"
                                        class="form-control @error('harga_jual') is-invalid @enderror"
                                        value="{{ old('harga_jual', 0) }}" min="0">
                                    @error('harga_jual')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-medium">Stok Awal <span class="text-danger">*</span></label>
                                <input type="number" name="stok"
                                    class="form-control @error('stok') is-invalid @enderror" value="{{ old('stok', 0) }}"
                                    min="0">
                                @error('stok')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-medium">Stok Minimum <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="stok_minimum"
                                    class="form-control @error('stok_minimum') is-invalid @enderror"
                                    value="{{ old('stok_minimum', 5) }}" min="0">
                                @error('stok_minimum')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Info Tambahan --}}
                            <div class="col-12 mt-2">
                                <h6 class="text-muted fw-semibold small text-uppercase mb-0">Info Tambahan</h6>
                                <hr class="mt-1">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-medium">Lokasi Rak</label>
                                <input type="text" name="lokasi_rak"
                                    class="form-control @error('lokasi_rak') is-invalid @enderror"
                                    value="{{ old('lokasi_rak') }}" placeholder="cth: A1-01">
                                @error('lokasi_rak')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-8">
                                <label class="form-label fw-medium">Deskripsi</label>
                                <textarea name="deskripsi" rows="2" class="form-control @error('deskripsi') is-invalid @enderror"
                                    placeholder="Deskripsi barang (opsional)">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i> Simpan
                            </button>
                            <a href="{{ route('admin.barang.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ============ TRIGGER SAAT ADA FIELD ERROR (dari validasi server) ============
            const firstInvalid = document.querySelector('#formBarang .is-invalid');
            if (firstInvalid) {
                // Scroll halus ke ringkasan error di atas
                const alertBox = document.getElementById('alertErrors');
                if (alertBox) {
                    alertBox.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
                // Fokuskan field pertama yang error, sedikit delay biar scroll selesai dulu
                setTimeout(() => {
                    firstInvalid.focus({
                        preventScroll: true
                    });
                }, 400);

                // Highlight ringan biar makin kelihatan
                firstInvalid.classList.add('shake-error');
                setTimeout(() => firstInvalid.classList.remove('shake-error'), 600);
            }

            // ============ TRIGGER SAAT USER MULAI PERBAIKI FIELD ============
            // Begitu user mengetik/isi ulang field yang error, hilangkan warna merahnya
            document.querySelectorAll('#formBarang .is-invalid').forEach(function(el) {
                el.addEventListener('input', function() {
                    el.classList.remove('is-invalid');
                });
                el.addEventListener('change', function() {
                    el.classList.remove('is-invalid');
                });
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        @keyframes shakeError {

            0%,
            100% {
                transform: translateX(0);
            }

            20%,
            60% {
                transform: translateX(-6px);
            }

            40%,
            80% {
                transform: translateX(6px);
            }
        }

        .shake-error {
            animation: shakeError 0.4s ease-in-out;
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 .2rem rgba(220, 53, 69, .25) !important;
        }
    </style>
@endpush
