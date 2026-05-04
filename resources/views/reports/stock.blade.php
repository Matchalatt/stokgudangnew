@extends('layouts.app')

@section('title', 'Laporan Posisi Stok - Sistem Inventaris')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4 print:hidden">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Laporan Posisi Stok</h1>
        <p class="text-sm text-gray-500 mt-1">Pantau pergerakan masuk, keluar, dan sisa stok barang secara real-time.</p>
    </div>
    
    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
        <div class="text-sm text-gray-600 bg-white border border-gray-200 px-4 py-2.5 rounded-lg shadow-sm flex items-center">
            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span class="font-medium mr-1">Per tanggal:</span> {{ date('d M Y H:i') }}
        </div>
        
        <!-- Tombol Cetak PDF -->
        <button onclick="window.print()" class="hidden sm:inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm focus:ring-4 focus:ring-indigo-100 whitespace-nowrap">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak PDF
        </button>

        <!-- [PEMBARUAN] Tombol Export Excel -->
        <a href="{{ route('reports.export_excel') }}" class="hidden sm:inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition-colors shadow-sm focus:ring-4 focus:ring-emerald-100 whitespace-nowrap">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Export Excel
        </a>
    </div>
</div>

<div class="hidden print:block mb-6 text-center">
    <h2 class="text-2xl font-bold text-gray-900 m-0">Laporan Posisi Stok Inventaris</h2>
    <p class="text-gray-600 mt-1">Dicetak pada: {{ date('d F Y - H:i') }}</p>
    <hr class="mt-4 border-gray-400">
</div>

<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden print:shadow-none print:border-none print:rounded-none">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 print:bg-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold w-16 text-center">No</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Nama Komoditas</th>
                    <th scope="col" class="px-6 py-4 font-semibold text-center">Total Masuk</th>
                    <th scope="col" class="px-6 py-4 font-semibold text-center">Total Keluar</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Sisa Stok (Dasar)</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Estimasi Kemasan</th>
                </tr>
            </thead>
            
            <tbody class="divide-y divide-gray-100 print:divide-gray-300">
                @forelse($reportData as $index => $data)
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="px-6 py-4 text-gray-500 text-center">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ $data['nama'] }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-emerald-600 font-medium bg-emerald-50 px-2 py-1 rounded-md print:bg-transparent">+{{ $data['total_in'] }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-red-600 font-medium bg-red-50 px-2 py-1 rounded-md print:bg-transparent">-{{ $data['total_out'] }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-gray-800 text-base">{{ $data['sisa_stok_base'] }}</span> 
                            <span class="text-gray-500">{{ $data['base_unit'] }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100 print:border-none print:bg-transparent print:p-0 print:text-sm">
                                {{ $data['sisa_stok_format'] }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 print:py-4">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3 print:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <span class="text-base font-medium text-gray-900">Belum ada data stok</span>
                                <p class="text-sm text-gray-500 mt-1">Data posisi stok akan muncul setelah Anda mencatat pemasukan/pengeluaran barang.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6 sm:hidden print:hidden flex flex-col gap-3">
    <button onclick="window.print()" class="w-full flex items-center justify-center px-4 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm focus:ring-4 focus:ring-indigo-100">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        Cetak Laporan PDF
    </button>
    
    <!-- [PEMBARUAN] Tombol Export Excel untuk Tampilan Mobile -->
    <a href="{{ route('reports.export_excel') }}" class="w-full flex items-center justify-center px-4 py-2.5 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition-colors shadow-sm focus:ring-4 focus:ring-emerald-100">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
        Export Excel
    </a>
</div>

<style>
    @media print {
        /* Sembunyikan elemen yang tidak perlu dicetak */
        body { background-color: white !important; color: black !important; }
        #sidebar, header, .print\:hidden { display: none !important; }
        
        /* Ambil alih area layar penuh */
        main {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            background-color: white !important;
        }

        /* Rapikan Tabel untuk dicetak */
        table { border-collapse: collapse !important; width: 100% !important; }
        th, td { 
            border-bottom: 1px solid #ddd !important; 
            padding: 8px 4px !important; 
        }
        th { background-color: #f3f4f6 !important; -webkit-print-color-adjust: exact; }
    }
</style>
@endsection