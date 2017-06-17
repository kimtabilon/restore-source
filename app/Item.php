<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description',
    ];

    public function inventories() {     return $this->hasMany('App\Inventory'); } 
    public function category() {        return $this->belongsTo('App\Category'); } 
    public function itemCodes() {       return $this->hasMany('App\ItemCode'); } 
    public function itemDiscounts() {   return $this->hasMany('App\ItemDiscount'); } 
    public function itemPrices() {      return $this->hasMany('App\ItemPrice'); } 
    public function itemImages() {      return $this->hasMany('App\ItemImage'); } 
}
