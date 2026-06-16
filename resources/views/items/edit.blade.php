@extends('layouts.app')

@section('title', 'Edit Master Barang - Sistem Inventaris')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="font-weight-bold text-dark mb-1">Edit Master Barang</h2>
        <p class="text-muted mb-0">Perbarui informasi detail barang atau ubah aturan konversi kemasannya.</p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                
                <form action="{{ route('items.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 10px;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h6 class="text-danger font-weight-bold mb-2">
                                <i class="icon-info mr-2"></i> Terjadi kesalahan input:
                            </h6>
                            <ul class="mb-0 pl-4 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
                        <div class="bg-primary-light text-primary rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 36px; height: 36px;">
                            <i class="icon-grid" style="font-size: 16px;"></i>
                        </div>
                        <h4 class="card-title text-dark font-weight-bold mb-0">Data Barang Utama</h4>
                    </div>
                    
                    <div class="form-row mb-4">
                        <div class="form-group col-12 mb-3">
                            <label for="nama" class="text-dark font-weight-bold" style="font-size: 13px;">
                                Nama Barang <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama', $item->nama) }}" required 
                                class="form-control" style="border-radius: 8px;" placeholder="Contoh: Daging Sapi Paha Belakang">
                        </div>

                        <div class="form-group col-12">
                            <label for="base_unit" class="text-dark font-weight-bold" style="font-size: 13px;">
                                Satuan Dasar (Base Unit) <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="base_unit" name="base_unit" value="{{ old('base_unit', $item->base_unit) }}" required 
                                class="form-control" style="border-radius: 8px;" placeholder="Contoh: KG">
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-2 mt-5 pb-2 border-bottom">
                        <div class="bg-warning-light text-warning rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 36px; height: 36px;">
                            <i class="icon-layers" style="font-size: 16px;"></i>
                        </div>
                        <div>
                            <h4 class="card-title text-dark font-weight-bold mb-0">Aturan Kemasan</h4>
                        </div>
                    </div>
                    <p class="text-muted mb-4" style="font-size: 13px;">Tentukan satuan yang lebih besar beserta isi di dalamnya (multiplier) terhadap satuan dasar. <span class="font-italic text-info">(Opsional)</span></p>

                    <div class="p-4 rounded mb-3 border-0 shadow-sm" style="background-color: #f8fafc;">
                        <div class="form-row">
                            <div class="form-group col-12 mb-3">
                                <label class="text-uppercase text-muted font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Nama Kemasan 1</label>
                                <input type="text" name="konversi[0][nama_kemasan]" value="{{ old('konversi.0.nama_kemasan', $item->conversions[0]->nama_kemasan ?? '') }}"
                                    class="form-control" style="border-radius: 8px;" placeholder="Contoh: Karton">
                            </div>
                            <div class="form-group col-12 mb-0">
                                <label class="text-uppercase text-muted font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Isi (Multiplier)</label>
                                <input type="number" name="konversi[0][multiplier]" value="{{ old('konversi.0.multiplier', $item->conversions[0]->multiplier ?? '') }}"
                                    class="form-control" style="border-radius: 8px;" placeholder="Contoh: 20">
                            </div>
                        </div>
                    </div>

                    <div class="p-4 rounded mb-4 border-0 shadow-sm" style="background-color: #f8fafc;">
                        <div class="form-row">
                            <div class="form-group col-12 mb-3">
                                <label class="text-uppercase text-muted font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Nama Kemasan 2</label>
                                <input type="text" name="konversi[1][nama_kemasan]" value="{{ old('konversi.1.nama_kemasan', $item->conversions[1]->nama_kemasan ?? '') }}"
                                    class="form-control" style="border-radius: 8px;" placeholder="Contoh: Box">
                            </div>
                            <div class="form-group col-12 mb-0">
                                <label class="text-uppercase text-muted font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">Isi (Multiplier)</label>
                                <input type="number" name="konversi[1][multiplier]" value="{{ old('konversi.1.multiplier', $item->conversions[1]->multiplier ?? '') }}"
                                    class="form-control" style="border-radius: 8px;" placeholder="Contoh: 100">
                            </div>
                        </div>
                    </div>

                    <div class="border-top pt-4 text-right">
                        <a href="{{ route('items.index') }}" class="btn btn-light px-4 mr-2 font-weight-bold text-muted shadow-sm" style="border-radius: 8px;">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 font-weight-bold shadow-sm" style="border-radius: 8px;">
                            <i class="icon-check mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>
@endsection