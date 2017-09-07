<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
		/**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['quantity', 'remarks', 'unit'];
    protected $appends  = ['created', 'modified'];

    public function user() {                return $this->belongsTo('App\User'); } 
    public function item() {                return $this->belongsTo('App\Item'); } 
    public function itemStatus() {          return $this->belongsTo('App\ItemStatus'); }  
    public function itemDiscounts() {       return $this->belongsToMany('App\ItemDiscount')     ->withTimestamps(); } 
    public function itemPrices() {          return $this->belongsToMany('App\ItemPrice')        ->withTimestamps(); } 
    public function itemSellingPrices() {   return $this->belongsToMany('App\ItemSellingPrice') ->withTimestamps(); } 
    public function itemRestorePrices() {   return $this->belongsToMany('App\ItemRestorePrice') ->withTimestamps(); } 
    public function itemImages() {          return $this->belongsToMany('App\ItemImage')        ->withTimestamps(); }
    public function itemRefImages() {       return $this->belongsToMany('App\ItemRefImage')     ->withTimestamps(); }
    public function itemCodes() {           return $this->belongsToMany('App\ItemCode')         ->withTimestamps(); }
    public function donors() {              return $this->belongsToMany('App\Donor')            ->withTimestamps(); }
    public function transactions() {        return $this->belongsToMany('App\Transaction')      ->withTimestamps(); }
    
    public function getCreatedAttribute()
    {
        return \Carbon\Carbon::parse($this->created_at)->diffForHumans();
    }

    public function getModifiedAttribute()
    {
        return \Carbon\Carbon::parse($this->updated_at)->diffForHumans();
    }
}
