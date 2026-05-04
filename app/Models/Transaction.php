<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'item_id', 'type', 'qty_base', 'tanggal_fisik', 'reference', 'keterangan', 'asal_tujuan', 'plat_nomor'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}