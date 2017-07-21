<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemCode extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
    ];

    public function inventories() { return $this->belongsTo('App\Inventory'); } 
    public function itemCodeType() { return $this->belongsTo('App\ItemCodeType'); } 
}
