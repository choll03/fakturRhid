<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faktur extends Model
{
	protected $fillable = [
       'alamat', 'tujuan',
    ];

    public function detail() {
    	return $this->hasMany('App\Detail');
    }
}
