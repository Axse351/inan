@extends('admin.layouts.app')

@section('title', 'Tambah Pemasok')
@section('page_title', 'Tambah Pemasok')
@section('breadcrumb', 'Master Data / Pemasok / Tambah')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-truck me-2"></i>Form Tambah Pemasok</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.pemasok.store') }}">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Kode Pemasok <span class="text-danger">*</span></label>
                                <input type="text" name="kode_pemasok"
                                    class="form-control @error('kode_pemasok') is-invalid @enderror"
                                    value="{{ old('kode_pemasok') }}" placeholder="cth: PMS-001">
                                @error('kode_pemasok')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-8">
                                <label class="form-label fw-medium">Nama Pemasok <span class="text-danger">*</span></label>
                                <input type="text" name="nama_pemasok"
                                    class="form-control @error('nama_pemasok') is-invalid @enderror"
                                    value="{{ old('nama_pemasok') }}" placeholder="Nama perusahaan / toko">
                                @error('nama_pemasok')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">Nama Kontak</label>
                                <input type="text" name="nama_kontak"
                                    class="form-control @error('nama_kontak') is-invalid @enderror"
                                    value="{{ old('nama_kontak') }}" placeholder="Nama PIC">
                                @error('nama_kontak')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium">Telepon</label>
                                <input type="text" name="telepon"
                                    class="form-control @error('telepon') is-invalid @enderror" value="{{ old('telepon') }}"
                                    placeholder="08xx-xxxx-xxxx">
                                @error('telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-medium">Email</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                    placeholder="email@pemasok.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-medium">Alamat</label>
                                <textarea name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror"
                                    placeholder="Alamat lengkap pemasok">{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i> Simpan
                            </button>
                            <a href="{{ route('admin.pemasok.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
