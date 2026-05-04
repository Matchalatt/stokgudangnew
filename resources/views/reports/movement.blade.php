@extends('layouts.app')

@section('title', 'Laporan Pergerakan Barang - Sistem Inventaris')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4 print:hidden">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Kartu Stok (Riwayat)</h1>
        <p class="text-sm text-gray-500 mt-1">Lacak rincian pergerakan barang masuk dan keluar secara mendetail.</p>
    </div>
    
    @if($selectedItem && $transactions->count() > 0)
    <button onclick="window.print()" class="inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm focus:ring-4 focus:ring-indigo-100 whitespace-nowrap">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        Cetak Kartu Stok
    </button>
    @endif
</div>

<div class="bg-white p-5 sm:p-6 rounded-xl border border-gray-100 shadow-sm mb-8 print:hidden">
    <form action="{{ route('reports.movement') }}" method="GET" class="flex flex-col sm:flex-row items-end gap-4">
        <div class="flex-1 w-full">
            <label for="item_id" class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Master Barang</label>
            <select name="item_id" id="item_id" class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-2.5 outline-none transition-colors bg-white" required>
                <option value="">-- Pilih Barang yang Ingin Dilacak --</option>
                @foreach($items as $item)
                    <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>
                        {{ $item->nama }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-md hover:shadow-lg focus:ring-4 focus:ring-blue-300 flex items-center justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            Lihat Riwayat
        </button>
    </form>
</div>

@if($selectedItem)
<div class="hidden print:block mb-6 text-center">
    <h2 class="text-2xl font-bold text-gray-900 m-0">Kartu Stok (Riwayat Pergerakan)</h2>
    <p class="text-gray-600 mt-1">Nama Barang: <span class="font-bold text-gray-900">{{ $selectedItem->nama }}</span></p>
    <p class="text-sm text-gray-500">Dicetak pada: {{ date('d F Y - H:i') }}</p>
    <hr class="mt-4 border-gray-400">
</div>

<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden print:shadow-none print:border-none print:rounded-none">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 print:hidden">
        <h3 class="font-semibold text-gray-800">Riwayat Transaksi: <span class="text-blue-600">{{ $selectedItem->nama }}</span></h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 print:bg-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold text-center w-24">Tanggal</th>
                    <th scope="col" class="px-6 py-4 font-semibold text-center w-24">Tipe</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Keterangan / Tujuan</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Referensi (Plat)</th>
                    <th scope="col" class="px-6 py-4 font-semibold text-right">Jumlah (Dasar)</th>
                    <th scope="col" class="px-6 py-4 font-semibold text-right">Estimasi Kemasan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 print:divide-gray-300">
                @forelse($transactions as $trx)
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="px-6 py-4 text-center text-gray-600">
                            {{ \Carbon\Carbon::parse($trx->tanggal_fisik)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($trx->type == 'in')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 print:border-none print:bg-transparent print:p-0">
                                    <svg class="w-3 h-3 mr-1 print:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                    MASUK
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-red-50 text-red-700 border border-red-100 print:border-none print:bg-transparent print:p-0">
                                    <svg class="w-3 h-3 mr-1 print:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                    KELUAR
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($trx->type == 'in')
                                <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider block mb-0.5">Dari:</span>
                            @else
                                <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider block mb-0.5">Ke:</span>
                            @endif
                            <span class="text-gray-900">{{ $trx->asal_tujuan ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 font-mono text-gray-600 text-sm">
                            {{ $trx->plat_nomor ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-bold {{ $trx->type == 'in' ? 'text-emerald-600' : 'text-red-600' }} text-base">
                                {{ $trx->type == 'in' ? '+' : '-' }}{{ $trx->qty_base }}
                            </span>
                            <span class="text-gray-500 ml-1">{{ $selectedItem->base_unit }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 print:bg-transparent print:p-0 print:text-sm">
                                {{ $selectedItem->formatQuantity($trx->qty_base) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-gray-500 print:py-6">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3 print:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                <span class="text-base font-medium text-gray-900">Belum ada riwayat pergerakan</span>
                                <p class="text-sm text-gray-500 mt-1">Transaksi masuk dan keluar untuk barang ini akan muncul di sini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif

<style>
    @media print {
        body { background-color: white !important; color: black !important; }
        #sidebar, header, .print\:hidden { display: none !important; }
        
        main {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            background-color: white !important;
        }

        table { border-collapse: collapse !important; width: 100% !important; }
        th, td { 
            border-bottom: 1px solid #ddd !important; 
            padding: 8px 4px !important; 
        }
        th { background-color: #f3f4f6 !important; -webkit-print-color-adjust: exact; }
    }
</style>
@endsection