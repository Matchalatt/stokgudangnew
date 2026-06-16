@extends('layouts.app')

@section('title', 'Dashboard Utama - Sistem Inventaris')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="font-weight-bold text-dark mb-1">Dashboard Utama</h2>
        <p class="text-muted">Ringkasan aktivitas inventaris, komoditas, dan pergerakan logistik</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-sm-6 mb-4">
        <div class="card h-100 card-hover">
            <div class="card-body d-flex flex-column justify-content-center">
                <div class="media align-items-center">
                    <div class="mr-3 bg-primary-light text-primary d-flex align-items-center justify-content-center" style="border-radius: 14px; width: 64px; height: 64px;">
                        <i class="icon-grid" style="font-size: 28px;"></i>
                    </div>
                    <div class="media-body text-right">
                        <p class="text-muted mb-1 font-weight-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Total Komoditas</p>
                        <h2 class="mb-0 text-dark font-weight-bold" style="font-size: 2.2rem;">{{ $totalItems }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-sm-6 mb-4">
        <div class="card h-100 card-hover">
            <div class="card-body d-flex flex-column justify-content-center">
                <div class="media align-items-center">
                    <div class="mr-3 bg-success-light text-success d-flex align-items-center justify-content-center" style="border-radius: 14px; width: 64px; height: 64px;">
                        <i class="icon-login" style="font-size: 28px;"></i>
                    </div>
                    <div class="media-body text-right">
                        <p class="text-muted mb-1 font-weight-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Masuk (Bulan Ini)</p>
                        <h2 class="mb-0 text-dark font-weight-bold" style="font-size: 2.2rem;">{{ $inboundThisMonth }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-sm-6 mb-4">
        <div class="card h-100 card-hover">
            <div class="card-body d-flex flex-column justify-content-center">
                <div class="media align-items-center">
                    <div class="mr-3 bg-danger-light text-danger d-flex align-items-center justify-content-center" style="border-radius: 14px; width: 64px; height: 64px;">
                        <i class="icon-logout" style="font-size: 28px;"></i>
                    </div>
                    <div class="media-body text-right">
                        <p class="text-muted mb-1 font-weight-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Keluar (Bulan Ini)</p>
                        <h2 class="mb-0 text-dark font-weight-bold" style="font-size: 2.2rem;">{{ $outboundThisMonth }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="card-title mb-0 text-dark font-weight-bold">Tren Logistik</h4>
                        <small class="text-muted">Statistik pergerakan barang 7 hari terakhir</small>
                    </div>
                </div>
                <div style="height: 330px; position: relative;">
                    <canvas id="logisticChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-body p-4 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h4 class="card-title mb-0 text-dark font-weight-bold">Aktivitas Terkini</h4>
                    </div>
                
                <div class="recent-activity flex-grow-1" style="max-height: 330px; overflow-y: auto; padding-right: 15px;">
                    @forelse($recentTransactions as $trx)
                        <div class="media mb-3 pb-3 border-bottom align-items-center">
                            <div class="mr-3">
                                @if($trx->type == 'in')
                                    <div class="d-flex align-items-center justify-content-center bg-success-light text-success rounded-circle" style="width: 36px; height: 36px;">
                                        <i class="icon-arrow-down" style="font-size: 14px;"></i>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-danger-light text-danger rounded-circle" style="width: 36px; height: 36px;">
                                        <i class="icon-arrow-up" style="font-size: 14px;"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="media-body">
                                <h6 class="mt-0 mb-1 text-dark font-weight-bold text-truncate" style="max-width: 160px; font-size: 14px;">
                                    {{ $trx->item ? $trx->item->nama : 'Barang Terhapus' }}
                                </h6>
                                <p class="text-muted mb-0" style="font-size: 12px;">Ref: {{ $trx->reference ?? ($trx->plat_nomor ?? '-') }}</p>
                                <span class="text-muted" style="font-size: 11px;"><i class="icon-clock mr-1"></i>{{ \Carbon\Carbon::parse($trx->tanggal_fisik)->diffForHumans() }}</span>
                            </div>
                            
                            <div class="text-right ml-2">
                                <h6 class="{{ $trx->type == 'in' ? 'text-success' : 'text-danger' }} font-weight-bold mb-0" style="font-size: 15px;">
                                    {{ $trx->type == 'in' ? '+' : '-' }}{{ $trx->qty_base }}
                                </h6>
                                <small class="text-muted text-uppercase font-weight-bold" style="font-size: 10px;">Dasar</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 h-100 d-flex flex-column justify-content-center align-items-center">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="icon-info text-muted" style="font-size: 24px;"></i>
                            </div>
                            <h6 class="text-dark font-weight-bold">Belum ada aktivitas</h6>
                            <p class="text-muted small">Transaksi masuk/keluar akan muncul di sini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div id="chart-data" 
     data-labels="{{ json_encode($chartLabels) }}" 
     data-in="{{ json_encode($chartDataIn) }}" 
     data-out="{{ json_encode($chartDataOut) }}" 
     class="d-none">
</div>

@endsection

@stack('styles')
<style>
    /* Mempercantik scrollbar pada kotak aktivitas terkini */
    .recent-activity::-webkit-scrollbar {
        width: 6px;
    }
    .recent-activity::-webkit-scrollbar-track {
        background: #f8fafc; 
        border-radius: 10px;
    }
    .recent-activity::-webkit-scrollbar-thumb {
        background: #cbd5e1; 
        border-radius: 10px;
    }
    .recent-activity::-webkit-scrollbar-thumb:hover {
        background: #94a3b8; 
    }
    
    /* Hilangkan border-bottom untuk elemen terakhir di list aktivitas */
    .recent-activity .media:last-child {
        border-bottom: none !important;
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('logisticChart').getContext('2d');
        const dataContainer = document.getElementById('chart-data');
        
        // Parsing data
        const labels = JSON.parse(dataContainer.getAttribute('data-labels'));
        const dataIn = JSON.parse(dataContainer.getAttribute('data-in'));
        const dataOut = JSON.parse(dataContainer.getAttribute('data-out'));

        // Chart Font Configuration
        Chart.defaults.font.family = "'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif";
        Chart.defaults.color = '#718096';

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Barang Masuk',
                        data: dataIn,
                        backgroundColor: '#10b981', /* Warna modern emerald (hijau) */
                        hoverBackgroundColor: '#059669',
                        borderRadius: 6, /* Melengkungkan ujung grafik */
                        barPercentage: 0.5,
                        categoryPercentage: 0.8
                    },
                    {
                        label: 'Barang Keluar',
                        data: dataOut,
                        backgroundColor: '#ef4444', /* Warna modern rose (merah) */
                        hoverBackgroundColor: '#dc2626',
                        borderRadius: 6,
                        barPercentage: 0.5,
                        categoryPercentage: 0.8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 20,
                            font: { size: 12, weight: '600' }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleFont: { size: 13 },
                        bodyFont: { size: 13 },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: true,
                        usePointStyle: true
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        ticks: { 
                            stepSize: 1,
                            font: { size: 11 }
                        },
                        grid: { 
                            borderDash: [5, 5], 
                            color: '#e2e8f0',
                            drawBorder: false
                        },
                        border: { display: false }
                    },
                    x: {
                        ticks: { font: { size: 11 } },
                        grid: { display: false },
                        border: { display: false }
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false,
                }
            }
        });
    });
</script>
@endpush