<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemQuantity extends Model
{
    protected $fillable = [
    	'number', 'remarks'
    ];

    public function inventory() { return $this->belongsTo('App\Inventory'); }
}
