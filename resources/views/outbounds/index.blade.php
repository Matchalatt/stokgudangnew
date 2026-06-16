@extends('layouts.app')

@section('title', 'Data Barang Keluar - Sistem Inventaris')

@section('content')
<div class="row mb-4">
    <div class="col-sm-6 mb-3 mb-sm-0">
        <h2 class="font-weight-bold text-dark mb-1">Riwayat Barang Keluar</h2>
        <p class="text-muted mb-0">Daftar seluruh transaksi pengeluaran stok fisik (Outbound).</p>
    </div>
    <div class="col-sm-6 text-sm-right d-flex flex-column flex-sm-row justify-content-sm-end align-items-sm-start align-items-lg-center">
        <a href="{{ route('outbounds.create') }}" class="btn btn-primary font-weight-bold px-4 py-2 shadow-sm" style="border-radius: 8px;">
            <i class="icon-minus mr-2"></i> Catat Barang Keluar
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 card-hover">
            <div class="card-body p-4">
                
                <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                    <div class="bg-danger-light text-danger rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 36px; height: 36px;">
                        <i class="icon-arrow-up-circle" style="font-size: 16px;"></i>
                    </div>
                    <h4 class="card-title text-dark font-weight-bold mb-0">Daftar Transaksi Outbound</h4>
                </div>

                <div class="mb-4 bg-light p-3 rounded border-0 shadow-sm">
                    <form action="{{ route('outbounds.index') }}" method="GET">
                        <div class="row align-items-end">
                            <div class="col-md-3 mb-3 mb-md-0">
                                <label for="start_date" class="text-dark font-weight-bold" style="font-size: 13px;">Tanggal Awal</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" style="border-radius: 8px;" value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-3 mb-3 mb-md-0">
                                <label for="end_date" class="text-dark font-weight-bold" style="font-size: 13px;">Tanggal Akhir</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" style="border-radius: 8px;" value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-6 d-flex justify-content-md-end align-items-center">
                                <button type="submit" class="btn btn-secondary font-weight-bold shadow-sm mr-2" style="border-radius: 8px;">
                                    <i class="icon-magnifier mr-1"></i> Filter
                                </button>
                                <a href="{{ route('outbounds.index') }}" class="btn btn-light font-weight-bold shadow-sm mr-2" style="border-radius: 8px;">
                                    Reset
                                </a>
                                <button type="submit" formaction="{{ route('outbounds.export_pdf') }}" class="btn btn-danger font-weight-bold shadow-sm" style="border-radius: 8px;">
                                    <i class="icon-doc mr-1"></i> Unduh PDF
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover verticle-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th scope="col" class="text-dark font-weight-bold text-center" style="width: 60px;">No</th>
                                <th scope="col" class="text-dark font-weight-bold text-center" style="width: 140px;">Tanggal Keluar</th>
                                <th scope="col" class="text-dark font-weight-bold">Tujuan / Referensi</th>
                                <th scope="col" class="text-dark font-weight-bold">Nama Barang</th>
                                <th scope="col" class="text-dark font-weight-bold text-right">Jumlah Dikeluarkan</th>
                                <th scope="col" class="text-dark font-weight-bold">Keterangan</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @forelse($outbounds as $index => $outbound)
                                <tr>
                                    <td class="text-center text-muted font-weight-bold">{{ $index + 1 }}</td>
                                    
                                    <td class="text-center text-muted font-weight-bold" style="font-size: 13px;">
                                        {{ \Carbon\Carbon::parse($outbound->tanggal_fisik)->format('d M Y') }}
                                    </td>
                                    
                                    <td class="text-muted" style="font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace; font-size: 13px;">
                                        {{ $outbound->reference ?? '-' }}
                                    </td>
                                    
                                    <td>
                                        <span class="text-dark font-weight-bold" style="font-size: 14px;">
                                            {{ $outbound->item->nama ?? 'Barang Tidak Ditemukan' }}
                                        </span>
                                    </td>
                                    
                                    <td class="text-right">
                                        <span class="badge bg-danger-light text-danger px-3 py-2 border-0" style="font-size: 14px; font-weight: 600; letter-spacing: 0.5px;">
                                            -{{ $outbound->qty_base }}
                                        </span>
                                        <span class="text-muted ml-1 font-weight-bold" style="font-size: 11px;">
                                            {{ $outbound->item->base_unit ?? '' }}
                                        </span>
                                    </td>
                                    
                                    <td>
                                        @if($outbound->keterangan)
                                            <span class="text-muted small" style="display: block; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $outbound->keterangan }}">
                                                {{ $outbound->keterangan }}
                                            </span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                                <i class="icon-social-dropbox text-muted" style="font-size: 24px;"></i>
                                            </div>
                                            <h6 class="text-dark font-weight-bold mb-1">Belum ada data barang keluar</h6>
                                            <p class="text-muted small mb-0">Klik tombol "Catat Barang Keluar" di kanan atas untuk mengurangi stok.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($outbounds, 'links'))
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $outbounds->links() }}
                    </div>
                @endif
                
            </div>
        </div>
    </div>
</div>
@endsection