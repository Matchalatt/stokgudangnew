@extends('layouts.app')

@section('title', 'Catat Barang Masuk - Sistem Inventaris')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-semibold text-gray-900">Catat Barang Masuk</h1>
    <p class="text-sm text-gray-500 mt-1">Formulir untuk mencatat penambahan stok fisik barang ke dalam sistem inventaris.</p>
</div>

<div class="max-w-4xl bg-white p-6 md:p-8 rounded-xl border border-gray-100 shadow-sm">
    
    <form action="{{ route('inbounds.store') }}" method="POST" class="space-y-8">
        @csrf
        
        @if(session('success'))
            <div class="bg-green-50 text-green-700 p-4 rounded-lg flex items-center border border-green-200">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

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
            <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-5">Informasi Transaksi</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="tanggal_fisik" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Tanggal Masuk <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="tanggal_fisik" name="tanggal_fisik" value="{{ old('tanggal_fisik', date('Y-m-d')) }}" required 
                        class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-2.5 outline-none transition-colors">
                </div>

                <div>
                    <label for="reference" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Referensi / Plat Nomor <span class="text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <input type="text" id="reference" name="reference" value="{{ old('reference') }}" 
                        class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-2.5 outline-none transition-colors" 
                        placeholder="Contoh: B/L No. 12345 / H 6789 SS">
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-5">Detail Barang</h3>
            
            <div class="space-y-6 bg-gray-50 p-5 rounded-xl border border-gray-200">
                
                <div>
                    <label for="item_id" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Pilih Barang <span class="text-red-500">*</span>
                    </label>
                    <select id="item_id" name="item_id" required 
                        class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-2.5 outline-none transition-colors bg-white">
                        <option value="">-- Pilih Master Barang --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama }} (Satuan Dasar: {{ $item->base_unit }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="conversion_id" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Satuan / Kemasan <span class="text-red-500">*</span>
                        </label>
                        <select id="conversion_id" name="conversion_id" required
                            class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-2.5 outline-none transition-colors bg-white disabled:bg-gray-100 disabled:cursor-not-allowed">
                            <option value="">-- Pilih Barang Terlebih Dahulu --</option>
                        </select>
                    </div>

                    <div>
                        <label for="qty" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Jumlah (Qty) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="qty" name="qty" value="{{ old('qty') }}" required min="1" step="0.01"
                            class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-2.5 outline-none transition-colors bg-white" 
                            placeholder="0">
                    </div>
                </div>

                <div id="kalkulasi_info" class="hidden bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg flex items-start mt-2 shadow-sm">
                    <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <span class="text-sm block text-blue-700 mb-1">Total stok yang akan disimpan ke database (Satuan Dasar):</span>
                        <div class="flex items-baseline">
                            <span class="font-bold text-2xl text-blue-900" id="preview_total">0</span> 
                            <span class="font-medium text-blue-800 ml-1.5" id="preview_unit"></span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div>
            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1.5">
                Keterangan Tambahan <span class="text-gray-400 font-normal">(Opsional)</span>
            </label>
            <textarea id="keterangan" name="keterangan" rows="3" 
                class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-2.5 outline-none transition-colors" 
                placeholder="Tuliskan catatan tambahan jika ada..."></textarea>
        </div>

        <div class="pt-4 flex items-center justify-end space-x-3 border-t border-gray-100 pt-6">
            <a href="{{ route('dashboard') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors focus:ring-4 focus:ring-gray-200 text-center">
                Kembali
            </a>
            
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-md hover:shadow-lg focus:ring-4 focus:ring-blue-300 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                Simpan Barang Masuk
            </button>
        </div>

    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const itemsData = JSON.parse('{!! json_encode($items) !!}');
        
        const itemSelect = document.getElementById('item_id');
        const conversionSelect = document.getElementById('conversion_id');
        const qtyInput = document.getElementById('qty');
        
        const infoBox = document.getElementById('kalkulasi_info');
        const previewTotal = document.getElementById('preview_total');
        const previewUnit = document.getElementById('preview_unit');

        function updateConversionOptions() {
            const selectedId = itemSelect.value;
            const selectedItem = itemsData.find(item => item.id == selectedId);

            // Reset options
            conversionSelect.innerHTML = '<option value="">-- Satuan Dasar --</option>';
            conversionSelect.disabled = !selectedItem;

            if (selectedItem) {
                previewUnit.innerText = selectedItem.base_unit;
                
                // Tambahkan Satuan Dasar sebagai default (multiplier = 1)
                const defaultOption = document.createElement('option');
                defaultOption.value = ""; 
                defaultOption.setAttribute('data-multiplier', "1");
                defaultOption.text = selectedItem.base_unit + ' (Satuan Dasar)';
                defaultOption.selected = true; // Set Default
                conversionSelect.innerHTML = ''; // Kosongkan dulu
                conversionSelect.appendChild(defaultOption);
                
                // Tambahkan opsi kemasan lain jika ada
                if (selectedItem.conversions.length > 0) {
                    selectedItem.conversions.forEach(conv => {
                        const option = document.createElement('option');
                        option.value = conv.id;
                        option.setAttribute('data-multiplier', conv.multiplier);
                        option.text = conv.nama_kemasan + ' (Isi: ' + conv.multiplier + ' ' + selectedItem.base_unit + ')';
                        conversionSelect.appendChild(option);
                    });
                }
            } else {
                previewUnit.innerText = '';
                conversionSelect.innerHTML = '<option value="">-- Pilih Barang Terlebih Dahulu --</option>';
            }
            
            calculateTotal();
        }

        function calculateTotal() {
            const selectedItemId = itemSelect.value;
            const qty = parseFloat(qtyInput.value) || 0;
            let multiplier = 1;

            if (!selectedItemId || qty <= 0) {
                infoBox.classList.add('hidden');
                return;
            }

            const selectedOption = conversionSelect.options[conversionSelect.selectedIndex];
            if (selectedOption && selectedOption.hasAttribute('data-multiplier')) {
                multiplier = parseFloat(selectedOption.getAttribute('data-multiplier'));
            }

            const total = qty * multiplier;
            
            // Format angka agar rapi (misal: 1000 jadi 1.000)
            previewTotal.innerText = new Intl.NumberFormat('id-ID').format(total);
            
            // Tampilkan kotak info
            infoBox.classList.remove('hidden');
        }

        // Pasang Event Listeners
        itemSelect.addEventListener('change', updateConversionOptions);
        conversionSelect.addEventListener('change', calculateTotal);
        qtyInput.addEventListener('input', calculateTotal);

        // Jalankan sekali saat halaman diload (untuk antisipasi jika ada old value dari error validasi)
        if (itemSelect.value) {
            updateConversionOptions();
        }
    });
</script>
@endsection