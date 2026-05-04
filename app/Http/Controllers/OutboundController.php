<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemConversion;
use App\Models\Transaction;
use Illuminate\Http\Request;

class OutboundController extends Controller
{
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

        return redirect()->back()->with('success', 'Barang keluar berhasil dicatat! Total ' . $qty_base_requested . ' satuan dasar telah dikurangi dari stok.');
    }
}