@extends('layouts.app')

@section('title', 'Edit Barang')
@section('page_title', 'Edit Barang')
@section('breadcrumb', 'Master Data / Barang / Edit')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-box-seam me-2"></i>Edit Barang — {{ $barang->nama_barang }}
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.barang.update', $barang) }}">
                        @csrf
                        @method('PUT')

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
                                    value="{{ old('kode_barang', $barang->kode_barang) }}">
                                @error('kode_barang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-medium">Barcode</label>
                                <input type="text" name="barcode"
                                    class="form-control @error('barcode') is-invalid @enderror"
                                    value="{{ old('barcode', $barang->barcode) }}">
                                @error('barcode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">Nama Barang <span class="text-danger">*</span></label>
                                <input type="text" name="nama_barang"
                                    class="form-control @error('nama_barang') is-invalid @enderror"
                                    value="{{ old('nama_barang', $barang->nama_barang) }}">
                                @error('nama_barang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-medium">Kategori <span class="text-danger">*</span></label>
                                <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror">
                                    <option value="">— Pilih Kategori —</option>
                                    @foreach ($kategoris as $k)
                                        <option value="{{ $k->id }}"
                                            {{ old('kategori_id', $barang->kategori_id) == $k->id ? 'selected' : '' }}>
                                            {{ $k->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-medium">Satuan <span class="text-danger">*</span></label>
                                <select name="satuan_id" class="form-select @error('satuan_id') is-invalid @enderror">
                                    <option value="">— Pilih Satuan —</option>
                                    @foreach ($satuans as $s)
                                        <option value="{{ $s->id }}"
                                            {{ old('satuan_id', $barang->satuan_id) == $s->id ? 'selected' : '' }}>
                                            {{ $s->nama_satuan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('satuan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-medium">Pemasok Utama</label>
                                <select name="pemasok_id" class="form-select @error('pemasok_id') is-invalid @enderror">
                                    <option value="">— Pilih Pemasok —</option>
                                    @foreach ($pemasoks as $p)
                                        <option value="{{ $p->id }}"
                                            {{ old('pemasok_id', $barang->pemasok_id) == $p->id ? 'selected' : '' }}>
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
                                        value="{{ old('harga_beli', $barang->harga_beli) }}" min="0">
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
                                        value="{{ old('harga_jual', $barang->harga_jual) }}" min="0">
                                    @error('harga_jual')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-medium">Stok Saat Ini</label>
                                <input type="text" class="form-control bg-light" value="{{ $barang->stok }}" disabled>
                                <div class="form-text">Stok diubah via transaksi masuk/keluar.</div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-medium">Stok Minimum <span class="text-danger">*</span></label>
                                <input type="number" name="stok_minimum"
                                    class="form-control @error('stok_minimum') is-invalid @enderror"
                                    value="{{ old('stok_minimum', $barang->stok_minimum) }}" min="0">
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
                                    value="{{ old('lokasi_rak', $barang->lokasi_rak) }}" placeholder="cth: A1-01">
                                @error('lokasi_rak')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-8">
                                <label class="form-label fw-medium">Deskripsi</label>
                                <textarea name="deskripsi" rows="2" class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i> Perbarui
                            </button>
                            <a href="{{ route('admin.barang.show', $barang) }}"
                                class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

