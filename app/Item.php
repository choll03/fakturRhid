<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'nama_item', 'harga_item', 'stok', 'ukuran',
    ];
}
