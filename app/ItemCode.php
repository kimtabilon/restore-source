<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Milon\Barcode\DNS1D;

class ItemCode extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['code',];
    protected $appends  = ['barcode'];

    public function inventories() { return $this->belongsTo('App\Inventory'); } 
    public function itemCodeType() { return $this->belongsTo('App\ItemCodeType'); } 

    public function getBarcodeAttribute()
    {
        return DNS1D::getBarcodePNG($this->code, "C39+", 1, 33);
    }
}
