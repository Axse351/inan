@extends('admin.layouts.app')

@section('title', isset($barang) ? 'Edit Barang' : 'Tambah Barang')
@section('page_title', isset($barang) ? 'Edit Barang' : 'Tambah Barang')
@section('breadcrumb', 'Master Data / Barang / ' . (isset($barang) ? 'Edit' : 'Tambah'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-bold">{{ isset($barang) ? 'Edit Barang' : 'Tambah Barang Baru' }}</h6>
                </div>
                <div class="card-body">
                    <form method="POST"
                        action="{{ isset($barang) ? route('admin.barang.update', $barang) : route('admin.barang.store') }}">
                        @csrf
                        @isset($barang)
                            @method('PUT')
                        @endisset

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kode Barang <span class="text-danger">*</span></label>
                                <input type="text" name="kode_barang"
                                    class="form-control @error('kode_barang') is-invalid @enderror"
                                    value="{{ old('kode_barang', $barang->kode_barang ?? '') }}" required>
                                @error('kode_barang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Barcode</label>
                                <input type="text" name="barcode"
                                    class="form-control @error('barcode') is-invalid @enderror"
                                    value="{{ old('barcode', $barang->barcode ?? '') }}">
                                @error('barcode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                                <input type="text" name="nama_barang"
                                    class="form-control @error('nama_barang') is-invalid @enderror"
                                    value="{{ old('nama_barang', $barang->nama_barang ?? '') }}" required>
                                @error('nama_barang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                                <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror"
                                    required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($kategoris as $kat)
                                        <option value="{{ $kat->id }}" @selected(old('kategori_id', $barang->kategori_id ?? '') == $kat->id)>
                                            {{ $kat->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Satuan <span class="text-danger">*</span></label>
                                <select name="satuan_id" class="form-select @error('satuan_id') is-invalid @enderror"
                                    required>
                                    <option value="">-- Pilih Satuan --</option>
                                    @foreach ($satuans as $sat)
                                        <option value="{{ $sat->id }}" @selected(old('satuan_id', $barang->satuan_id ?? '') == $sat->id)>
                                            {{ $sat->nama_satuan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('satuan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Pemasok Default</label>
                                <select name="pemasok_id" class="form-select @error('pemasok_id') is-invalid @enderror">
                                    <option value="">-- Pilih Pemasok --</option>
                                    @foreach ($pemasoks as $p)
                                        <option value="{{ $p->id }}" @selected(old('pemasok_id', $barang->pemasok_id ?? '') == $p->id)>
                                            {{ $p->nama_pemasok }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pemasok_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Harga Beli <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="harga_beli"
                                        class="form-control @error('harga_beli') is-invalid @enderror"
                                        value="{{ old('harga_beli', $barang->harga_beli ?? 0) }}" min="0"
                                        step="100" required>
                                    @error('harga_beli')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Harga Jual <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="harga_jual"
                                        class="form-control @error('harga_jual') is-invalid @enderror"
                                        value="{{ old('harga_jual', $barang->harga_jual ?? 0) }}" min="0"
                                        step="100" required>
                                    @error('harga_jual')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            @if (!isset($barang))
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Stok Awal <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="stok"
                                        class="form-control @error('stok') is-invalid @enderror"
                                        value="{{ old('stok', 0) }}" min="0" required>
                                    @error('stok')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                            <div class="col-md-{{ isset($barang) ? '6' : '6' }}">
                                <label class="form-label fw-semibold">Stok Minimum <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="stok_minimum"
                                    class="form-control @error('stok_minimum') is-invalid @enderror"
                                    value="{{ old('stok_minimum', $barang->stok_minimum ?? 5) }}" min="0" required>
                                @error('stok_minimum')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Lokasi Rak</label>
                                <input type="text" name="lokasi_rak"
                                    class="form-control @error('lokasi_rak') is-invalid @enderror"
                                    value="{{ old('lokasi_rak', $barang->lokasi_rak ?? '') }}"
                                    placeholder="Contoh: A1-03">
                                @error('lokasi_rak')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3">{{ old('deskripsi', $barang->deskripsi ?? '') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i> {{ isset($barang) ? 'Perbarui' : 'Simpan' }}
                            </button>
                            <a href="{{ route('admin.barang.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
