<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemConversion;
use App\Models\Transaction;
use Illuminate\Http\Request;

class InboundController extends Controller
{
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

        return redirect()->back()->with('success', 'Barang masuk berhasil dicatat! Total ' . $qty_base . ' satuan dasar telah ditambahkan.');
    }
}