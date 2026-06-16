@extends('layouts.app')

@section('title', 'Laporan Posisi Stok - Sistem Inventaris')

@section('content')
<div class="row mb-4 d-print-none">
    <div class="col-sm-6 mb-3 mb-sm-0">
        <h2 class="font-weight-bold text-dark mb-0">Laporan Posisi Stok</h2>
        <p class="text-muted mt-1 mb-0">Pantau pergerakan masuk, keluar, dan sisa stok barang secara real-time.</p>
    </div>
    <div class="col-sm-6 text-sm-right d-flex flex-column flex-sm-row justify-content-sm-end align-items-sm-start align-items-lg-center">
        
        <span class="badge bg-white border text-dark p-2 mr-sm-3 mb-3 mb-sm-0 font-weight-normal shadow-sm" style="font-size: 13px;">
            <i class="icon-clock text-primary mr-1"></i> Per tanggal: <span class="font-weight-bold">{{ date('d M Y H:i') }}</span>
        </span>
        
        <div class="d-none d-sm-flex flex-column flex-lg-row">
            <button onclick="window.print()" class="btn btn-primary font-weight-bold px-3 mb-2 mb-lg-0 mr-lg-2">
                <i class="icon-printer mr-2"></i> Cetak PDF
            </button>
            <a href="{{ route('reports.export_excel') }}" class="btn btn-success font-weight-bold px-3">
                <i class="icon-doc mr-2"></i> Export Excel
            </a>
        </div>
    </div>
</div>

<div class="d-none d-print-block mb-4 text-center">
    <h2 class="font-weight-bold text-dark m-0">Laporan Posisi Stok Inventaris</h2>
    <p class="text-muted mt-1 mb-3">Dicetak pada: {{ date('d F Y - H:i') }}</p>
    <hr style="border-top: 2px solid #333;">
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 print-no-border">
            <div class="card-body print-no-padding">
                <div class="table-responsive">
                    <table class="table table-striped table-hover verticle-middle mb-0 print-table">
                        <thead class="bg-light">
                            <tr>
                                <th scope="col" class="text-dark font-weight-bold text-center" style="width: 60px;">No</th>
                                <th scope="col" class="text-dark font-weight-bold">Nama Komoditas</th>
                                <th scope="col" class="text-dark font-weight-bold text-center">Total Masuk</th>
                                <th scope="col" class="text-dark font-weight-bold text-center">Total Keluar</th>
                                <th scope="col" class="text-dark font-weight-bold">Sisa Stok (Dasar)</th>
                                <th scope="col" class="text-dark font-weight-bold">Estimasi Kemasan</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @forelse($reportData as $index => $data)
                                <tr>
                                    <td class="text-muted text-center">{{ $index + 1 }}</td>
                                    <td class="font-weight-bold text-dark">{{ $data['nama'] }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-success px-2 py-1 print-text-success">+{{ $data['total_in'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-danger px-2 py-1 print-text-danger">-{{ $data['total_out'] }}</span>
                                    </td>
                                    <td>
                                        <strong class="text-dark" style="font-size: 15px;">{{ $data['sisa_stok_base'] }}</strong> 
                                        <span class="text-muted" style="font-size: 13px;">{{ $data['base_unit'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary px-3 py-2 print-badge-outline" style="font-size: 12px;">
                                            {{ $data['sisa_stok_format'] }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 d-print-none">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <i class="icon-social-dropbox display-4 text-muted mb-3"></i>
                                            <h5 class="text-dark font-weight-bold">Belum ada data stok</h5>
                                            <p class="text-muted mt-1">Data posisi stok akan muncul setelah Anda mencatat pemasukan/pengeluaran barang.</p>
                                        </div>
                                    </td>
                                    <td colspan="6" class="text-center py-3 d-none d-print-table-cell text-muted">
                                        Belum ada data posisi stok.
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

<div class="row mt-3 d-block d-sm-none d-print-none">
    <div class="col-12 d-flex flex-column">
        <button onclick="window.print()" class="btn btn-primary btn-block font-weight-bold mb-3">
            <i class="icon-printer mr-2"></i> Cetak Laporan PDF
        </button>
        <a href="{{ route('reports.export_excel') }}" class="btn btn-success btn-block font-weight-bold">
            <i class="icon-doc mr-2"></i> Export Excel
        </a>
    </div>
</div>

@endsection

@stack('styles')
<style>
    /* Kustomisasi CSS Khusus Mode Print (Cetak Kertas/PDF) */
    @media print {
        /* Reset background menjadi putih bersih */
        body { background-color: #fff !important; color: #000 !important; }
        
        /* Sembunyikan navigasi template Quixlab */
        .nav-header, .header, .nk-sidebar, .footer { display: none !important; }
        
        /* Ambil alih area layar penuh */
        .content-body {
            margin-left: 0 !important;
            padding: 0 !important;
            background-color: #fff !important;
        }

        /* Hilangkan bayangan dan border card agar rapi di kertas */
        .print-no-border { box-shadow: none !important; border: none !important; }
        .print-no-padding { padding: 0 !important; }

        /* Rapikan Tabel untuk dicetak */
        .print-table { border-collapse: collapse !important; width: 100% !important; }
        .print-table th, .print-table td { 
            border: 1px solid #ddd !important; 
            padding: 8px !important; 
        }
        .print-table th { background-color: #f3f4f6 !important; -webkit-print-color-adjust: exact; color: #000 !important; }
        
        /* Sesuaikan Badge agar tidak menjadi blok hitam pekat saat diprint B&W */
        .badge { border: 1px solid #ccc !important; color: #000 !important; }
        .print-text-success { background-color: transparent !important; color: #28a745 !important; font-weight: bold; border: none !important; }
        .print-text-danger { background-color: transparent !important; color: #dc3545 !important; font-weight: bold; border: none !important; }
    }
</style>