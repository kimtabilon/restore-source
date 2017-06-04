<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemPrice extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'market_price', 'percent_discount',
    ];

    public function inventories() { return $this->hasMany('App\Inventory'); } 
}
