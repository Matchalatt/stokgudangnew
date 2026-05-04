@extends('layouts.app')

@section('title', 'Daftar Master Barang - Sistem Inventaris')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Master Barang</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola daftar barang dan aturan konversi kemasannya.</p>
    </div>
    <a href="{{ route('items.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm focus:ring-4 focus:ring-blue-100 whitespace-nowrap">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Barang
    </a>
</div>

@if(session('success'))
    <div class="bg-green-50 text-green-700 p-4 rounded-lg flex items-center border border-green-200 mb-6 shadow-sm">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-gray-50 border-b border-gray-200 text-gray-600">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold w-16">No</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Nama Barang</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Satuan Dasar</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Aturan Konversi</th>
                    <th scope="col" class="px-6 py-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            
            <tbody class="divide-y divide-gray-100">
                @forelse($items as $index => $item)
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="px-6 py-4 text-gray-500">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ $item->nama }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                {{ $item->base_unit }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            @if($item->conversions->count() > 0)
                                <ul class="space-y-1.5">
                                    @foreach($item->conversions as $conv)
                                        <li class="flex items-center text-sm">
                                            <svg class="w-3.5 h-3.5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            1 <span class="font-medium text-gray-800 mx-1">{{ $conv->nama_kemasan }}</span> = {{ $conv->multiplier }} {{ $item->base_unit }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-gray-400 italic text-sm">Tidak ada konversi</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right font-medium">
                            <a href="{{ route('items.edit', $item->id) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 mr-4 transition-colors">
                                Edit
                            </a>
                            
                            <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="inline-block m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data master barang ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center text-red-600 hover:text-red-900 transition-colors bg-transparent border-none p-0 cursor-pointer font-medium">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <span class="text-base font-medium text-gray-900">Belum ada data barang</span>
                                <p class="text-sm text-gray-500 mt-1">Silakan tambah barang baru untuk mulai mencatat inventaris.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection