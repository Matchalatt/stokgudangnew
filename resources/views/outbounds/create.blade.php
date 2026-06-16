@extends('layouts.app')

@section('title', 'Catat Barang Keluar - Sistem Inventaris')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="font-weight-bold text-dark mb-1">Catat Barang Keluar</h2>
        <p class="text-muted mb-0">Formulir untuk mencatat pengeluaran stok fisik barang dari gudang.</p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                
                <form action="{{ route('outbounds.store') }}" method="POST" id="outboundForm">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 10px;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h6 class="text-danger font-weight-bold mb-2">
                                <i class="icon-info mr-2"></i> Terjadi kesalahan input:
                            </h6>
                            <ul class="mb-0 pl-4 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
                        <div class="bg-primary-light text-primary rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 36px; height: 36px;">
                            <i class="icon-doc" style="font-size: 16px;"></i>
                        </div>
                        <h4 class="card-title text-dark font-weight-bold mb-0">Informasi Transaksi</h4>
                    </div>
                    
                    <div class="form-row mb-4">
                        <div class="form-group col-12 mb-3">
                            <label for="tanggal_fisik" class="text-dark font-weight-bold" style="font-size: 13px;">
                                Tanggal Keluar <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="tanggal_fisik" name="tanggal_fisik" value="{{ old('tanggal_fisik', date('Y-m-d')) }}" required 
                                class="form-control" style="border-radius: 8px;">
                        </div>

                        <div class="form-group col-12">
                            <label for="reference" class="text-dark font-weight-bold" style="font-size: 13px;">
                                Tujuan / Referensi <span class="text-muted font-weight-normal" style="font-size: 11px;">(Opsional)</span>
                            </label>
                            <input type="text" id="reference" name="reference" value="{{ old('reference') }}" 
                                class="form-control" style="border-radius: 8px;" placeholder="Contoh: Dikirim ke Toko Cabang A">
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-4 mt-5 pb-2 border-bottom">
                        <div class="bg-danger-light text-danger rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 36px; height: 36px;">
                            <i class="icon-logout" style="font-size: 16px;"></i>
                        </div>
                        <h4 class="card-title text-dark font-weight-bold mb-0">Detail Barang</h4>
                    </div>
                    
                    <div class="p-4 rounded mb-4 border-0 shadow-sm" style="background-color: #f8fafc;">
                        <div class="form-group mb-3">
                            <label for="item_id" class="text-dark font-weight-bold" style="font-size: 13px;">
                                Pilih Barang <span class="text-danger">*</span>
                            </label>
                            <select id="item_id" name="item_id" required class="form-control" style="border-radius: 8px;">
                                <option value="">-- Pilih Barang --</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama }} (Sisa Stok: {{ $item->current_stock }} {{ $item->base_unit }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-12 mb-3">
                                <label for="conversion_id" class="text-dark font-weight-bold" style="font-size: 13px;">
                                    Satuan / Kemasan <span class="text-danger">*</span>
                                </label>
                                <select id="conversion_id" name="conversion_id" required class="form-control" style="border-radius: 8px;" disabled>
                                    <option value="">-- Pilih Barang Terlebih Dahulu --</option>
                                </select>
                            </div>

                            <div class="form-group col-12">
                                <label for="qty" class="text-dark font-weight-bold" style="font-size: 13px;">
                                    Jumlah (Qty) <span class="text-danger">*</span>
                                </label>
                                <input type="number" id="qty" name="qty" value="{{ old('qty') }}" required min="1" step="0.01"
                                    class="form-control" style="border-radius: 8px;" placeholder="0">
                            </div>
                        </div>

                        <div id="kalkulasi_info" class="alert d-none mt-3 mb-0 shadow-sm border-0" style="border-radius: 10px;">
                            <div class="media align-items-start">
                                <i id="info_icon" class="icon-info mr-3 mt-1" style="font-size: 24px;"></i>
                                <div class="media-body">
                                    <span class="d-block mb-1" id="info_text" style="font-size: 12px; font-weight: 600; letter-spacing: 0.3px;">Total pengeluaran (Satuan Dasar):</span>
                                    <h3 class="font-weight-bold mb-0" style="font-size: 1.8rem;" id="info_total_wrapper">
                                        <span id="preview_total">0</span> 
                                        <small class="font-weight-bold ml-1" id="preview_unit" style="font-size: 1rem;"></small>
                                        <small class="ml-2 font-weight-bold" id="max_stock_label" style="font-size: 12px;"></small>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-2">
                        <label for="keterangan" class="text-dark font-weight-bold" style="font-size: 13px;">
                            Keterangan Tambahan <span class="text-muted font-weight-normal" style="font-size: 11px;">(Opsional)</span>
                        </label>
                        <textarea id="keterangan" name="keterangan" rows="3" 
                            class="form-control" style="border-radius: 8px;" placeholder="Tuliskan catatan tambahan jika ada..."></textarea>
                    </div>

                    <div class="border-top pt-4 mt-4 text-right">
                        <a href="{{ route('outbounds.index') }}" class="btn btn-light px-4 mr-2 font-weight-bold text-muted shadow-sm" style="border-radius: 8px;">Batal</a>
                        <button type="submit" id="btn_submit" class="btn btn-primary px-4 font-weight-bold shadow-sm" style="border-radius: 8px;">
                            <i class="icon-logout mr-2"></i> Simpan Barang Keluar
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div id="items-data" data-items="{{ json_encode($items) }}" class="d-none"></div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mengambil data dari HTML
        const rawData = document.getElementById('items-data').getAttribute('data-items');
        const itemsData = JSON.parse(rawData);
        
        const itemSelect = document.getElementById('item_id');
        const conversionSelect = document.getElementById('conversion_id');
        const qtyInput = document.getElementById('qty');
        
        const infoBox = document.getElementById('kalkulasi_info');
        const previewTotal = document.getElementById('preview_total');
        const previewUnit = document.getElementById('preview_unit');
        const maxStockLabel = document.getElementById('max_stock_label');
        const infoTotalWrapper = document.getElementById('info_total_wrapper');
        
        const btnSubmit = document.getElementById('btn_submit');
        const infoText = document.getElementById('info_text');
        const infoIcon = document.getElementById('info_icon');

        function updateConversionOptions() {
            const selectedId = itemSelect.value;
            const selectedItem = itemsData.find(item => item.id == selectedId);

            conversionSelect.innerHTML = '<option value="">-- Satuan Dasar --</option>';
            conversionSelect.disabled = !selectedItem;

            if (selectedItem) {
                previewUnit.innerText = selectedItem.base_unit;
                maxStockLabel.innerText = `(Maksimal Stok: ${selectedItem.current_stock})`;
                
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
                infoBox.classList.add('d-none');
                btnSubmit.disabled = false;
                return;
            }

            const selectedItem = itemsData.find(item => item.id == selectedItemId);
            const selectedOption = conversionSelect.options[conversionSelect.selectedIndex];
            
            if (selectedOption && selectedOption.hasAttribute('data-multiplier')) {
                multiplier = parseFloat(selectedOption.getAttribute('data-multiplier'));
            }

            const total = qty * multiplier;
            previewTotal.innerText = new Intl.NumberFormat('id-ID').format(total);
            infoBox.classList.remove('d-none');

            // LOGIKA VALIDASI STOK (Menggunakan kelas desain pastel modern)
            if (total > selectedItem.current_stock) {
                // Tampilan Error / Stok Kurang
                infoBox.className = 'alert mt-3 mb-0 shadow-sm border-0 bg-danger-light text-danger';
                infoText.innerText = 'Peringatan: Stok tidak mencukupi!';
                infoIcon.className = 'icon-close mr-3 mt-1';
                infoTotalWrapper.className = 'font-weight-bold text-danger mb-0';
                maxStockLabel.classList.remove('text-muted');
                maxStockLabel.classList.add('text-danger');
                
                // Matikan tombol submit
                btnSubmit.disabled = true;
                btnSubmit.style.cursor = 'not-allowed';
                btnSubmit.classList.add('btn-secondary');
                btnSubmit.classList.remove('btn-primary');
            } else {
                // Tampilan Normal (Aman)
                infoBox.className = 'alert mt-3 mb-0 shadow-sm border-0 bg-warning-light text-warning';
                infoText.innerText = 'Total pengeluaran (Satuan Dasar):';
                infoIcon.className = 'icon-info mr-3 mt-1';
                infoTotalWrapper.className = 'font-weight-bold text-warning mb-0';
                maxStockLabel.classList.add('text-muted');
                maxStockLabel.classList.remove('text-danger');
                
                // Nyalakan tombol submit
                btnSubmit.disabled = false;
                btnSubmit.style.cursor = 'pointer';
                btnSubmit.classList.add('btn-primary');
                btnSubmit.classList.remove('btn-secondary');
            }
        }

        // Pasang Event Listeners
        itemSelect.addEventListener('change', updateConversionOptions);
        conversionSelect.addEventListener('change', calculateTotal);
        qtyInput.addEventListener('input', calculateTotal);

        // Render awal jika ada old value
        if (itemSelect.value) updateConversionOptions();
    });
</script>
@endpush
@endsection