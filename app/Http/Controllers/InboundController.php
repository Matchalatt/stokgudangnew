<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemConversion;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // [PEMBARUAN] Import Facade PDF dari DomPDF

class InboundController extends Controller
{
    /**
     * [PEMBARUAN] Menampilkan daftar riwayat barang masuk (Inbound) beserta filter tanggal
     */
    public function index(Request $request)
    {
        // Inisialisasi query untuk transaksi tipe 'in' (masuk) beserta relasi itemnya
        $query = Transaction::with('item')->where('type', 'in');

        // [PEMBARUAN] Fitur Filter Tanggal Masuk
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_fisik', [$request->start_date, $request->end_date]);
        }

        // Eksekusi query dan urutkan dari yang terbaru
        $inbounds = $query->orderBy('tanggal_fisik', 'desc')
                          ->orderBy('created_at', 'desc')
                          ->get(); // Gunakan ->paginate(10) alih-alih ->get() jika data sudah sangat banyak

        return view('inbounds.index', compact('inbounds'));
    }

    /**
     * [PEMBARUAN] Fungsi untuk mengekspor data yang sudah difilter ke format PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Transaction::with('item')->where('type', 'in');

        // Terapkan filter yang sama jika ada request tanggal dari user
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_fisik', [$request->start_date, $request->end_date]);
        }

        $inbounds = $query->orderBy('tanggal_fisik', 'desc')
                          ->orderBy('created_at', 'desc')
                          ->get();

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Load view khusus PDF dan set ukuran kertas A4 Portrait
        $pdf = Pdf::loadView('inbounds.pdf', compact('inbounds', 'startDate', 'endDate'))
                  ->setPaper('a4', 'portrait');

        // Penamaan file dinamis berdasarkan rentang tanggal
        $namaFile = 'Laporan_Barang_Masuk_' . ($startDate && $endDate ? $startDate . '_sd_' . $endDate : date('Y-m-d')) . '.pdf';
        
        // Unduh file PDF
        return $pdf->download($namaFile);
    }

    public function create()
    {
        // Ambil semua item beserta aturan konversinya untuk dikirim ke Javascript
        $items = Item::with('conversions')->latest()->get();
        return view('inbounds.create', compact('items'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'conversion_id' => 'nullable|exists:item_conversions,id',
            'qty' => 'required|numeric|min:1',
            'tanggal_fisik' => 'required|date',
            'reference' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        // 2. Logika Konversi ke Satuan Dasar (Base Unit)
        $multiplier = 1; // Default jika user memasukkan satuan dasar langsung
        
        if ($request->conversion_id) {
            $conversion = ItemConversion::find($request->conversion_id);
            if ($conversion) {
                $multiplier = $conversion->multiplier;
            }
        }

        // Contoh: Input Qty = 10 (Dus), Multiplier Dus = 12. Maka Qty Base = 120 (Pouch)
        $qty_base = $request->qty * $multiplier;

        // 3. Simpan Transaksi Masuk
        Transaction::create([
            'item_id' => $request->item_id,
            'type' => 'in', // Penanda bahwa ini adalah barang masuk
            'qty_base' => $qty_base,
            'tanggal_fisik' => $request->tanggal_fisik,
            'reference' => $request->reference,
            'keterangan' => $request->keterangan,
        ]);

        // [PERBAIKAN]: Redirect ke halaman index barang masuk agar notifikasi muncul di tabel riwayat
        return redirect()->route('inbounds.index')->with('success', 'Barang masuk berhasil dicatat! Total ' . $qty_base . ' satuan dasar telah ditambahkan.');
    }
}