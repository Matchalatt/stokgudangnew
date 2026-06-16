<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemConversion;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // [PEMBARUAN] Import Facade PDF dari DomPDF

class OutboundController extends Controller
{
    /**
     * [PEMBARUAN] Menampilkan daftar riwayat barang keluar (Outbound) beserta filter tanggal
     */
    public function index(Request $request)
    {
        // Inisialisasi query untuk transaksi khusus tipe 'out' (keluar) beserta relasi itemnya
        $query = Transaction::with('item')->where('type', 'out');

        // [PEMBARUAN] Fitur Filter Tanggal Keluar
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_fisik', [$request->start_date, $request->end_date]);
        }

        // Eksekusi query dan urutkan dari yang terbaru
        $outbounds = $query->orderBy('tanggal_fisik', 'desc')
                           ->orderBy('created_at', 'desc')
                           ->get(); // Gunakan ->paginate(10) jika data sudah sangat banyak

        return view('outbounds.index', compact('outbounds'));
    }

    /**
     * [PEMBARUAN] Fungsi untuk mengekspor data yang sudah difilter ke format PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Transaction::with('item')->where('type', 'out');

        // Terapkan filter yang sama jika ada request tanggal dari user
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_fisik', [$request->start_date, $request->end_date]);
        }

        $outbounds = $query->orderBy('tanggal_fisik', 'desc')
                           ->orderBy('created_at', 'desc')
                           ->get();

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Load view khusus PDF dan set ukuran kertas A4 Portrait
        $pdf = Pdf::loadView('outbounds.pdf', compact('outbounds', 'startDate', 'endDate'))
                  ->setPaper('a4', 'portrait');

        // Penamaan file dinamis berdasarkan rentang tanggal
        $namaFile = 'Laporan_Barang_Keluar_' . ($startDate && $endDate ? $startDate . '_sd_' . $endDate : date('Y-m-d')) . '.pdf';
        
        // Unduh file PDF
        return $pdf->download($namaFile);
    }

    public function create()
    {
        // Ambil item beserta konversi, LALU hitung sisa stoknya secara real-time
        $items = Item::with('conversions')->get()->map(function ($item) {
            $in = Transaction::where('item_id', $item->id)->where('type', 'in')->sum('qty_base');
            $out = Transaction::where('item_id', $item->id)->where('type', 'out')->sum('qty_base');
            
            // Tambahkan properti 'current_stock' secara dinamis ke object item
            $item->current_stock = $in - $out;
            
            return $item;
        })->filter(function ($item) {
            // Opsional: Hanya tampilkan barang yang stoknya lebih dari 0
            return $item->current_stock > 0;
        })->values();

        return view('outbounds.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'conversion_id' => 'nullable|exists:item_conversions,id',
            'qty' => 'required|numeric|min:1',
            'tanggal_fisik' => 'required|date',
            'reference' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $multiplier = 1;
        if ($request->conversion_id) {
            $conversion = ItemConversion::find($request->conversion_id);
            if ($conversion) {
                $multiplier = $conversion->multiplier;
            }
        }

        $qty_base_requested = $request->qty * $multiplier;

        // --- VALIDASI BACK-END: CEK KETERSEDIAAN STOK ---
        $stock_in = Transaction::where('item_id', $request->item_id)->where('type', 'in')->sum('qty_base');
        $stock_out = Transaction::where('item_id', $request->item_id)->where('type', 'out')->sum('qty_base');
        $current_stock = $stock_in - $stock_out;

        if ($qty_base_requested > $current_stock) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['qty' => 'Gagal! Stok tidak mencukupi. Sisa stok saat ini hanya ' . $current_stock . ' satuan dasar.']);
        }
        // ------------------------------------------------

        Transaction::create([
            'item_id' => $request->item_id,
            'type' => 'out', // Penanda barang keluar
            'qty_base' => $qty_base_requested,
            'tanggal_fisik' => $request->tanggal_fisik,
            'reference' => $request->reference,
            'keterangan' => $request->keterangan,
        ]);

        // [PERBAIKAN]: Redirect ke halaman index barang keluar agar notifikasi muncul di tabel riwayat
        return redirect()->route('outbounds.index')->with('success', 'Barang keluar berhasil dicatat! Total ' . $qty_base_requested . ' satuan dasar telah dikurangi dari stok.');
    }
}