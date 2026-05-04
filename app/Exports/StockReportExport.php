<?php

namespace App\Exports;

use App\Models\Item;
use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Opsional: agar lebar kolom Excel rapi otomatis

class StockReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * 1. Mengambil data barang
    */
    public function collection()
    {
        return Item::with('conversions')->get();
    }

    /**
    * 2. Memetakan data ke dalam baris Excel (Sama seperti di Controller sebelumnya)
    */
    public function map($item): array
    {
        $totalIn = Transaction::where('item_id', $item->id)->where('type', 'in')->sum('qty_base');
        $totalOut = Transaction::where('item_id', $item->id)->where('type', 'out')->sum('qty_base');
        $currentStockBase = $totalIn - $totalOut;

        return [
            $item->nama,
            $item->formatQuantity($totalIn),
            $item->formatQuantity($totalOut),
            $currentStockBase . ' ' . $item->base_unit,
            $item->formatQuantity($currentStockBase), // Ini akan otomatis menjadi "166 Dus + 4 Pouch"
        ];
    }

    /**
    * 3. Membuat Judul Kolom di baris paling atas Excel
    */
    public function headings(): array
    {
        return [
            'Nama Komoditas',
            'Total Masuk',
            'Total Keluar',
            'Sisa Stok (Satuan Dasar)',
            'Status Stok Akhir',
        ];
    }
}