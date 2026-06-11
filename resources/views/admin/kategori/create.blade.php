@extends('admin.layouts.app')

@section('title', 'Tambah Kategori')
@section('page_title', 'Tambah Kategori')
@section('breadcrumb', 'Master Data / Kategori / Tambah')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-tags me-2"></i>Form Tambah Kategori</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.kategori.store') }}">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Kode Kategori <span class="text-danger">*</span></label>
                                <input type="text" name="kode_kategori"
                                    class="form-control @error('kode_kategori') is-invalid @enderror"
                                    value="{{ old('kode_kategori') }}" placeholder="cth: KAT-001">
                                @error('kode_kategori')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-8">
                                <label class="form-label fw-medium">Nama Kategori <span class="text-danger">*</span></label>
                                <input type="text" name="nama_kategori"
                                    class="form-control @error('nama_kategori') is-invalid @enderror"
                                    value="{{ old('nama_kategori') }}" placeholder="Nama kategori barang">
                                @error('nama_kategori')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-medium">Keterangan</label>
                                <textarea name="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror"
                                    placeholder="Deskripsi singkat kategori (opsional)">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i> Simpan
                            </button>
                            <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
