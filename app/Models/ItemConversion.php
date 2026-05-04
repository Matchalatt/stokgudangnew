<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemConversion extends Model
{
    protected $fillable = ['item_id', 'nama_kemasan', 'multiplier'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
