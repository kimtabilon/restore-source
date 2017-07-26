<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemSellingPrice extends Model
{
    /**
     *	Shared table with item_prices. IMPORTANT NOTE!
     *
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'market_price',
    ];

    protected $table = 'item_prices';

    public function inventories() { return $this->belongsToMany('App\Inventory')->withTimestamps(); } 
}
