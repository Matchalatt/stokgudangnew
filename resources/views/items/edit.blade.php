@extends('layouts.app')

@section('title', 'Edit Master Barang - Sistem Inventaris')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-semibold text-gray-900">Edit Master Barang</h1>
    <p class="text-sm text-gray-500 mt-1">Perbarui informasi detail barang atau ubah aturan konversi kemasannya.</p>
</div>

<div class="max-w-4xl bg-white p-6 md:p-8 rounded-xl border border-gray-100 shadow-sm">
    
    <form action="{{ route('items.update', $item->id) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')
        
        @if ($errors->any())
            <div class="bg-red-50 text-red-700 p-4 rounded-lg border border-red-200">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-semibold">Terjadi kesalahan input:</span>
                </div>
                <ul class="list-disc pl-7 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-5">Data Barang Utama</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Nama Barang <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $item->nama) }}" required 
                        class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-2.5 outline-none transition-colors" >
                </div>

                <div>
                    <label for="base_unit" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Satuan Dasar (Base Unit) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="base_unit" name="base_unit" value="{{ old('base_unit', $item->base_unit) }}" required 
                        class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-2.5 outline-none transition-colors" >
                </div>
            </div>
        </div>

        <div>
            <div class="mb-5">
                <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Aturan Kemasan (Opsional)</h3>
                <p class="text-sm text-gray-500 mt-1">Tentukan satuan yang lebih besar beserta isi di dalamnya (multiplier) terhadap satuan dasar.</p>
            </div>

            <div class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 p-5 bg-gray-50 rounded-xl border border-gray-200">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Kemasan 1</label>
                        <input type="text" name="konversi[0][nama_kemasan]" value="{{ old('konversi.0.nama_kemasan', $item->conversions[0]->nama_kemasan ?? '') }}"
                            class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-2.5 outline-none transition-colors bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Isi (Multiplier)</label>
                        <input type="number" name="konversi[0][multiplier]" value="{{ old('konversi.0.multiplier', $item->conversions[0]->multiplier ?? '') }}"
                            class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-2.5 outline-none transition-colors bg-white">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 p-5 bg-gray-50 rounded-xl border border-gray-200">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Kemasan 2</label>
                        <input type="text" name="konversi[1][nama_kemasan]" value="{{ old('konversi.1.nama_kemasan', $item->conversions[1]->nama_kemasan ?? '') }}"
                            class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-2.5 outline-none transition-colors bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Isi (Multiplier)</label>
                        <input type="number" name="konversi[1][multiplier]" value="{{ old('konversi.1.multiplier', $item->conversions[1]->multiplier ?? '') }}"
                            class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-2.5 outline-none transition-colors bg-white">
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-4 flex items-center justify-end space-x-3 border-t border-gray-100 pt-6">
            <a href="{{ route('items.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors focus:ring-4 focus:ring-gray-200 text-center">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-md hover:shadow-lg focus:ring-4 focus:ring-indigo-300">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection