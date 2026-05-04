@extends('layouts.app')

@section('title', 'Dashboard Utama - Sistem Inventaris')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
    <p class="text-sm text-gray-500 mt-1">Ringkasan aktivitas inventaris, komoditas, dan pergerakan logistik</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    
    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center transition-shadow hover:shadow-md">
        <div class="p-4 bg-blue-50 text-blue-600 rounded-xl mr-5">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium mb-1">Total Komoditas</p>
            <p class="text-3xl font-bold text-gray-900">{{ $totalItems }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center transition-shadow hover:shadow-md">
        <div class="p-4 bg-emerald-50 text-emerald-600 rounded-xl mr-5">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium mb-1">Masuk (Bulan Ini)</p>
            <p class="text-3xl font-bold text-gray-900">{{ $inboundThisMonth }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center transition-shadow hover:shadow-md">
        <div class="p-4 bg-red-50 text-red-600 rounded-xl mr-5">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium mb-1">Keluar (Bulan Ini)</p>
            <p class="text-3xl font-bold text-gray-900">{{ $outboundThisMonth }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
    
    <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex flex-col">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Tren Logistik (7 Hari Terakhir)</h2>
        </div>
        <div class="relative flex-1 min-h-[300px]">
            <canvas id="logisticChart"></canvas>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex flex-col h-full max-h-[400px]">
        <div class="flex items-center justify-between mb-5 border-b border-gray-100 pb-3">
            <h2 class="text-lg font-semibold text-gray-900">Aktivitas Terkini</h2>
            <a href="{{ route('reports.movement') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Lihat Semua</a>
        </div>
        
        <div class="flex-1 overflow-y-auto pr-2 space-y-5">
            @forelse($recentTransactions as $trx)
                <div class="flex items-start group">
                    <div class="mt-1.5 mr-4 flex-shrink-0">
                        @if($trx->type == 'in')
                            <span class="block w-2.5 h-2.5 rounded-full bg-emerald-500 ring-4 ring-emerald-50 group-hover:ring-emerald-100 transition-all"></span>
                        @else
                            <span class="block w-2.5 h-2.5 rounded-full bg-red-500 ring-4 ring-red-50 group-hover:ring-red-100 transition-all"></span>
                        @endif
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">
                            {{ $trx->item ? $trx->item->nama : 'Barang Terhapus' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-0.5 truncate">
                            Ref/Plat: {{ $trx->reference ?? ($trx->plat_nomor ?? '-') }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ \Carbon\Carbon::parse($trx->tanggal_fisik)->diffForHumans() }}
                        </p>
                    </div>
                    
                    <div class="ml-3 flex-shrink-0 text-right">
                        <span class="inline-flex items-center text-sm font-bold {{ $trx->type == 'in' ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ $trx->type == 'in' ? '+' : '-' }}{{ $trx->qty_base }}
                        </span>
                        <span class="block text-[10px] text-gray-400 font-medium mt-0.5 uppercase">Dasar</span>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-8 text-gray-500">
                    <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm">Belum ada aktivitas.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<div id="chart-data" 
     data-labels="{{ json_encode($chartLabels) }}" 
     data-in="{{ json_encode($chartDataIn) }}" 
     data-out="{{ json_encode($chartDataOut) }}" 
     class="hidden">
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('logisticChart').getContext('2d');
        const dataContainer = document.getElementById('chart-data');
        
        // Parsing data dengan aman dari atribut HTML
        const labels = JSON.parse(dataContainer.getAttribute('data-labels'));
        const dataIn = JSON.parse(dataContainer.getAttribute('data-in'));
        const dataOut = JSON.parse(dataContainer.getAttribute('data-out'));

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Barang Masuk',
                        data: dataIn,
                        backgroundColor: '#10b981', // emerald-500
                        hoverBackgroundColor: '#059669', // emerald-600
                        borderRadius: 6,
                        barPercentage: 0.6
                    },
                    {
                        label: 'Barang Keluar',
                        data: dataOut,
                        backgroundColor: '#ef4444', // red-500
                        hoverBackgroundColor: '#dc2626', // red-600
                        borderRadius: 6,
                        barPercentage: 0.6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: { family: "'Inter', sans-serif", size: 13 }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.9)',
                        padding: 12,
                        titleFont: { family: "'Inter', sans-serif", size: 14 },
                        bodyFont: { family: "'Inter', sans-serif", size: 13 },
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        ticks: { stepSize: 1, font: { family: "'Inter', sans-serif" } },
                        grid: { borderDash: [4, 4], color: '#f3f4f6' },
                        border: { display: false }
                    },
                    x: {
                        ticks: { font: { family: "'Inter', sans-serif" } },
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
@endsection