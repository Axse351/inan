@extends('layouts.app')

@section('title', 'Edit Satuan')
@section('page_title', 'Edit Satuan')
@section('breadcrumb', 'Master Data / Satuan / Edit')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-rulers me-2"></i>Edit Satuan — {{ $satuan->nama_satuan }}
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.satuan.update', $satuan) }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-medium">Nama Satuan <span class="text-danger">*</span></label>
                                <input type="text" name="nama_satuan"
                                    class="form-control @error('nama_satuan') is-invalid @enderror"
                                    value="{{ old('nama_satuan', $satuan->nama_satuan) }}">
                                @error('nama_satuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-medium">Keterangan</label>
                                <textarea name="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $satuan->keterangan) }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i> Perbarui
                            </button>
                            <a href="{{ route('admin.satuan.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

