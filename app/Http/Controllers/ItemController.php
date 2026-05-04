<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('conversions')->latest()->get();
        return view('items.index', compact('items'));
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request)
    {
        $konversiData = [];
        if ($request->has('konversi')) {
            $konversiData = collect($request->konversi)->filter(function ($value) {
                return !empty($value['nama_kemasan']) || !empty($value['multiplier']);
            })->toArray();
        }

        $request->merge(['konversi' => $konversiData]);

        $request->validate([
            'nama' => 'required|string|max:255',
            'base_unit' => 'required|string|max:50',
            'konversi.*.nama_kemasan' => 'required_with:konversi.*.multiplier|string|max:100',
            'konversi.*.multiplier' => 'required_with:konversi.*.nama_kemasan|numeric|min:1',
        ], [
            'konversi.*.nama_kemasan.required_with' => 'Nama kemasan wajib diisi jika nilai isi ada.',
            'konversi.*.multiplier.required_with' => 'Nilai isi wajib diisi jika nama kemasan ada.',
        ]);

        DB::transaction(function () use ($request, $konversiData) {
            $item = Item::create([
                'nama' => $request->nama,
                'base_unit' => $request->base_unit,
            ]);

            if (!empty($konversiData)) {
                foreach ($konversiData as $data) {
                    $item->conversions()->create([
                        'nama_kemasan' => $data['nama_kemasan'],
                        'multiplier' => $data['multiplier'],
                    ]);
                }
            }
        });

        return redirect()->route('items.index')->with('success', 'Master Barang berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit barang.
     */
    public function edit(Item $item)
    {
        // Panggil relasi konversinya juga
        $item->load('conversions');
        return view('items.edit', compact('item'));
    }

    /**
     * Memperbarui data barang dan aturan konversinya.
     */
    public function update(Request $request, Item $item)
    {
        $konversiData = [];
        if ($request->has('konversi')) {
            $konversiData = collect($request->konversi)->filter(function ($value) {
                return !empty($value['nama_kemasan']) || !empty($value['multiplier']);
            })->toArray();
        }

        $request->merge(['konversi' => $konversiData]);

        $request->validate([
            'nama' => 'required|string|max:255',
            'base_unit' => 'required|string|max:50',
            'konversi.*.nama_kemasan' => 'required_with:konversi.*.multiplier|string|max:100',
            'konversi.*.multiplier' => 'required_with:konversi.*.nama_kemasan|numeric|min:1',
        ]);

        DB::transaction(function () use ($request, $item, $konversiData) {
            // Update data master
            $item->update([
                'nama' => $request->nama,
                'base_unit' => $request->base_unit,
            ]);

            // Hapus data konversi lama
            $item->conversions()->delete();

            // Masukkan data konversi baru (jika ada)
            if (!empty($konversiData)) {
                foreach ($konversiData as $data) {
                    $item->conversions()->create([
                        'nama_kemasan' => $data['nama_kemasan'],
                        'multiplier' => $data['multiplier'],
                    ]);
                }
            }
        });

        return redirect()->route('items.index')->with('success', 'Master Barang berhasil diperbarui!');
    }

    /**
     * Menghapus data master barang beserta aturan konversinya dari database.
     */
    public function destroy(Item $item)
    {
        // Gunakan transaksi database untuk memastikan penghapusan yang aman
        DB::transaction(function () use ($item) {
            // Hapus data konversi terkait terlebih dahulu
            $item->conversions()->delete();
            
            // Hapus data master barang
            $item->delete();
        });

        // Redirect kembali dengan pesan sukses
        return redirect()->route('items.index')->with('success', 'Data master barang berhasil dihapus!');
    }
}