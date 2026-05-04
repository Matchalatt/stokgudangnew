<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    // Mengizinkan mass assignment untuk kolom nama dan base_unit
    protected $fillable = ['nama', 'base_unit'];

    /**
     * Relasi ke tabel item_conversions
     * Satu barang (Item) bisa memiliki banyak aturan kemasan (ItemConversion)
     */
    public function conversions()
    {
        return $this->hasMany(ItemConversion::class);
    }

    /**
     * Helper Function: Mengubah total stok satuan dasar menjadi format kemasan
     * Contoh: 1996 Pouch -> "166 Dus + 4 Pouch"
     * * @param int $totalBaseQty Jumlah total stok dalam satuan terkecil
     * @return string
     */
    public function formatQuantity($totalBaseQty)
    {
        // Ambil konversi dengan multiplier terbesar (misal: Dus yang isinya 12)
        $conversion = $this->conversions()->orderBy('multiplier', 'desc')->first();

        // Jika barang ini tidak punya aturan kemasan, ATAU stoknya kurang dari 1 kemasan utuh,
        // langsung kembalikan angka beserta satuan dasarnya (Contoh: "4 Pouch")
        if (!$conversion || $totalBaseQty < $conversion->multiplier) {
            return $totalBaseQty . ' ' . $this->base_unit;
        }

        // Hitung berapa banyak kemasan utuh yang didapat (Contoh: 1996 / 12 = 166 Dus)
        $mainUnitQty = floor($totalBaseQty / $conversion->multiplier);
        
        // Hitung sisa satuan dasar menggunakan modulo (Contoh: 1996 % 12 = 4 Pouch)
        $restQty = $totalBaseQty % $conversion->multiplier;

        // Jika ada sisa eceran, tampilkan format kombinasi
        if ($restQty > 0) {
            return "{$mainUnitQty} {$conversion->nama_kemasan} + {$restQty} {$this->base_unit}";
        }

        // Jika pas tanpa sisa, cukup tampilkan kemasan utuhnya saja
        return "{$mainUnitQty} {$conversion->nama_kemasan}";
    }
}