@extends('layouts.app')

@section('title', 'Daftar Master Barang - Sistem Inventaris')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
        <div class="mb-3 mb-sm-0">
            <h2 class="font-weight-bold text-dark mb-1">Master Barang</h2>
            <p class="text-muted mb-0">Kelola daftar barang dan aturan konversi kemasannya.</p>
        </div>
        <div>
            <a href="{{ route('items.create') }}" class="btn btn-primary font-weight-bold px-4 py-2 shadow-sm d-flex align-items-center">
                <i class="icon-plus mr-2"></i> Tambah Barang
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 card-hover">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover verticle-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 60px;" class="text-center">No</th>
                                <th scope="col">Nama Barang</th>
                                <th scope="col">Satuan Dasar</th>
                                <th scope="col">Aturan Konversi</th>
                                <th scope="col" class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @forelse($items as $index => $item)
                                <tr>
                                    <td class="text-muted text-center font-weight-bold">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="font-weight-bold text-dark" style="font-size: 14px;">{{ $item->nama }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-light text-primary px-3 py-2 border-0" style="font-size: 12px; font-weight: 600; letter-spacing: 0.5px;">
                                            {{ $item->base_unit }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($item->conversions->count() > 0)
                                            <ul class="list-unstyled mb-0">
                                                @foreach($item->conversions as $conv)
                                                    <li class="text-muted mb-1 d-flex align-items-center" style="font-size: 13px;">
                                                        <div class="bg-primary-light rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 20px; height: 20px;">
                                                            <i class="icon-arrow-right text-primary" style="font-size: 9px;"></i>
                                                        </div>
                                                        1 <strong class="text-dark mx-1">{{ $conv->nama_kemasan }}</strong> = {{ $conv->multiplier }} {{ $item->base_unit }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="badge bg-light text-muted px-2 py-1 font-weight-normal border border-light" style="font-size: 12px;">Tidak ada konversi</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-info text-white mr-1 shadow-sm px-3" title="Edit">
                                            <i class="icon-pencil mr-1"></i> Edit
                                        </a>
                                        
                                        <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline-block m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data master barang ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger shadow-sm px-3" title="Hapus">
                                                <i class="icon-trash mr-1"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                                <i class="icon-social-dropbox text-muted" style="font-size: 24px;"></i>
                                            </div>
                                            <h6 class="text-dark font-weight-bold mb-1">Belum ada data barang</h6>
                                            <p class="text-muted small mb-0">Silakan tambah barang baru untuk mulai mencatat inventaris.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection