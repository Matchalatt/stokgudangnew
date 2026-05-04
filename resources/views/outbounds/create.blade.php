@extends('layouts.app')

@section('title', 'Catat Barang Keluar - Sistem Inventaris')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-semibold text-gray-900">Catat Barang Keluar</h1>
    <p class="text-sm text-gray-500 mt-1">Formulir untuk mencatat pengeluaran stok fisik barang dari gudang.</p>
</div>

<div class="max-w-4xl bg-white p-6 md:p-8 rounded-xl border border-gray-100 shadow-sm">
    
    <form action="{{ route('outbounds.store') }}" method="POST" class="space-y-8" id="outboundForm">
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
                    <label for="tanggal_fisik" class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Keluar <span class="text-red-500">*</span></label>
                    <input type="date" id="tanggal_fisik" name="tanggal_fisik" value="{{ old('tanggal_fisik', date('Y-m-d')) }}" required class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 px-4 py-2.5 outline-none transition-colors">
                </div>
                <div>
                    <label for="reference" class="block text-sm font-medium text-gray-700 mb-1.5">Tujuan / Referensi <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <input type="text" id="reference" name="reference" value="{{ old('reference') }}" class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 px-4 py-2.5 outline-none transition-colors" placeholder="Contoh: Dikirim ke Toko Cabang A">
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2 mb-5">Detail Barang</h3>
            <div class="space-y-6 bg-gray-50 p-5 rounded-xl border border-gray-200">
                
                <div>
                    <label for="item_id" class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Barang <span class="text-red-500">*</span></label>
                    <select id="item_id" name="item_id" required class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 px-4 py-2.5 outline-none transition-colors bg-white">
                        <option value="">-- Pilih Barang --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama }} (Sisa Stok: {{ $item->current_stock }} {{ $item->base_unit }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="conversion_id" class="block text-sm font-medium text-gray-700 mb-1.5">Satuan / Kemasan <span class="text-red-500">*</span></label>
                        <select id="conversion_id" name="conversion_id" required class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 px-4 py-2.5 outline-none transition-colors bg-white disabled:bg-gray-100 disabled:cursor-not-allowed">
                            <option value="">-- Pilih Barang Terlebih Dahulu --</option>
                        </select>
                    </div>
                    <div>
                        <label for="qty" class="block text-sm font-medium text-gray-700 mb-1.5">Jumlah (Qty) <span class="text-red-500">*</span></label>
                        <input type="number" id="qty" name="qty" value="{{ old('qty') }}" required min="1" step="0.01" class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 px-4 py-2.5 outline-none transition-colors bg-white" placeholder="0">
                    </div>
                </div>

                <div id="kalkulasi_info" class="hidden px-4 py-3 rounded-lg border flex items-start mt-2 shadow-sm transition-colors">
                    <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" id="info_icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <span class="text-sm block mb-1 font-medium" id="info_text">Total pengeluaran (Satuan Dasar):</span>
                        <div class="flex items-baseline">
                            <span class="font-bold text-2xl" id="preview_total">0</span> 
                            <span class="font-medium ml-1.5" id="preview_unit"></span>
                            <span class="ml-2 text-sm text-gray-500" id="max_stock_label"></span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div>
            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan Tambahan <span class="text-gray-400 font-normal">(Opsional)</span></label>
            <textarea id="keterangan" name="keterangan" rows="3" class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 px-4 py-2.5 outline-none transition-colors" placeholder="Tuliskan catatan tambahan jika ada..."></textarea>
        </div>

        <div class="pt-4 flex items-center justify-end space-x-3 border-t border-gray-100 pt-6">
            <a href="{{ route('dashboard') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors focus:ring-4 focus:ring-gray-200 text-center">Kembali</a>
            <button type="submit" id="btn_submit" class="px-6 py-2.5 bg-orange-600 text-white font-medium rounded-lg hover:bg-orange-700 transition-colors shadow-md hover:shadow-lg focus:ring-4 focus:ring-orange-300 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                Simpan Barang Keluar
            </button>
        </div>
    </form>
</div>

<div id="items-data" data-items="{{ json_encode($items) }}" class="hidden"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mengambil data dari HTML secara aman untuk dibaca oleh JavaScript
        const rawData = document.getElementById('items-data').getAttribute('data-items');
        const itemsData = JSON.parse(rawData);
        
        const itemSelect = document.getElementById('item_id');
        const conversionSelect = document.getElementById('conversion_id');
        const qtyInput = document.getElementById('qty');
        const infoBox = document.getElementById('kalkulasi_info');
        const previewTotal = document.getElementById('preview_total');
        const previewUnit = document.getElementById('preview_unit');
        const maxStockLabel = document.getElementById('max_stock_label');
        const btnSubmit = document.getElementById('btn_submit');
        const infoIcon = document.getElementById('info_icon');
        const infoText = document.getElementById('info_text');

        function updateConversionOptions() {
            const selectedId = itemSelect.value;
            const selectedItem = itemsData.find(item => item.id == selectedId);

            conversionSelect.innerHTML = '<option value="">-- Satuan Dasar --</option>';
            conversionSelect.disabled = !selectedItem;

            if (selectedItem) {
                previewUnit.innerText = selectedItem.base_unit;
                maxStockLabel.innerText = `/ Max: ${selectedItem.current_stock}`;
                
                const defaultOption = document.createElement('option');
                defaultOption.value = ""; 
                defaultOption.setAttribute('data-multiplier', "1");
                defaultOption.text = selectedItem.base_unit + ' (Satuan Dasar)';
                defaultOption.selected = true;
                conversionSelect.innerHTML = '';
                conversionSelect.appendChild(defaultOption);
                
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
                maxStockLabel.innerText = '';
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
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
                return;
            }

            const selectedItem = itemsData.find(item => item.id == selectedItemId);
            const selectedOption = conversionSelect.options[conversionSelect.selectedIndex];
            if (selectedOption && selectedOption.hasAttribute('data-multiplier')) {
                multiplier = parseFloat(selectedOption.getAttribute('data-multiplier'));
            }

            const total = qty * multiplier;
            previewTotal.innerText = new Intl.NumberFormat('id-ID').format(total);
            infoBox.classList.remove('hidden');

            // LOGIKA VALIDASI STOK (FRONT-END)
            if (total > selectedItem.current_stock) {
                // Tampilan Error / Stok Kurang
                infoBox.className = 'px-4 py-3 rounded-lg border flex items-start mt-2 shadow-sm transition-colors bg-red-50 border-red-200 text-red-800';
                infoIcon.classList.replace('text-orange-600', 'text-red-600');
                infoText.innerText = 'Peringatan: Stok tidak mencukupi!';
                previewTotal.classList.replace('text-orange-900', 'text-red-900');
                
                // Matikan tombol submit
                btnSubmit.disabled = true;
                btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                // Tampilan Normal (Aman)
                infoBox.className = 'px-4 py-3 rounded-lg border flex items-start mt-2 shadow-sm transition-colors bg-orange-50 border-orange-200 text-orange-800';
                infoIcon.classList.replace('text-red-600', 'text-orange-600');
                infoText.innerText = 'Total pengeluaran (Satuan Dasar):';
                previewTotal.classList.replace('text-red-900', 'text-orange-900');
                
                // Nyalakan tombol submit
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        itemSelect.addEventListener('change', updateConversionOptions);
        conversionSelect.addEventListener('change', calculateTotal);
        qtyInput.addEventListener('input', calculateTotal);

        if (itemSelect.value) updateConversionOptions();
    });
</script>
@endsection