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
    protected $appends  = ['created', 'modified'];

    public function inventories() { return $this->hasMany('App\Inventory'); } 
    public function category() {    return $this->belongsTo('App\Category'); } 
    // public function itemCodes() {       return $this->hasMany('App\ItemCode'); } 
    // public function itemDiscounts() {   return $this->hasMany('App\ItemDiscount'); } 
    // public function itemPrices() {      return $this->hasMany('App\ItemPrice'); } 
    // public function itemImages() {      return $this->hasMany('App\ItemImage'); } 

    public function getCreatedAttribute()
    {
        return \Carbon\Carbon::parse($this->created_at)->diffForHumans();
    }

    public function getModifiedAttribute()
    {
        return \Carbon\Carbon::parse($this->updated_at)->diffForHumans();
    }
}
