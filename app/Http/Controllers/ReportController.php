<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Exports\StockReportExport;         // [PEMBARUAN] Import class Export yang sudah kita buat
use Maatwebsite\Excel\Facades\Excel;       // [PEMBARUAN] Import Facade Excel dari Maatwebsite

class ReportController extends Controller
{
    /**
     * Menampilkan halaman tabel laporan sisa stok secara real-time
     */
    public function index()
    {
        $items = Item::with('conversions')->get();

        // Menghitung stok untuk setiap barang
        $reportData = $items->map(function ($item) {
            $totalIn = Transaction::where('item_id', $item->id)->where('type', 'in')->sum('qty_base');
            $totalOut = Transaction::where('item_id', $item->id)->where('type', 'out')->sum('qty_base');
            
            $currentStockBase = $totalIn - $totalOut;

            return [
                'nama' => $item->nama,
                'base_unit' => $item->base_unit,
                'total_in' => $item->formatQuantity($totalIn),
                'total_out' => $item->formatQuantity($totalOut),
                'sisa_stok_base' => $currentStockBase,
                'sisa_stok_format' => $item->formatQuantity($currentStockBase),
            ];
        });

        return view('reports.stock', compact('reportData'));
    }

    /**
     * Menampilkan Laporan Pergerakan Barang (Kartu Stok)
     * Menampilkan seluruh riwayat transaksi (masuk & keluar) tanpa filter tipe
     */
    public function movement(Request $request)
    {
        $items = Item::all();
        $transactions = collect();
        $selectedItem = null;

        // Jika user memilih barang untuk difilter
        if ($request->has('item_id') && $request->item_id != '') {
            $selectedItem = Item::with('conversions')->find($request->item_id);
            
            // Ambil semua riwayat transaksi untuk item tersebut urut dari yang terbaru
            $transactions = Transaction::where('item_id', $request->item_id)
                                  ->orderBy('tanggal_fisik', 'desc')
                                  ->orderBy('created_at', 'desc')
                                  ->get();
        }

        return view('reports.movement', compact('items', 'transactions', 'selectedItem'));
    }

    /**
     * [PEMBARUAN] Fungsi untuk memproses dan mengunduh data stok menjadi file Excel
     */
    public function exportExcel()
    {
        // Membuat nama file otomatis berdasarkan tanggal hari ini (Contoh: Laporan_Stok_2026-06-15.xlsx)
        $namaFile = 'Laporan_Stok_' . date('Y-m-d') . '.xlsx';
        
        // Memanggil library Excel untuk mengunduh menggunakan format dari StockReportExport
        return Excel::download(new StockReportExport, $namaFile);
    }
}