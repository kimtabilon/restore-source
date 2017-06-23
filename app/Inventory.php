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
    protected $fillable = [];

    public function user() {            return $this->belongsTo('App\User'); } 
    public function donor() {           return $this->belongsTo('App\Donor'); } 
    public function item() {            return $this->belongsTo('App\Item'); } 
    public function itemStatus() {      return $this->belongsTo('App\ItemStatus'); } 
    public function transaction() {     return $this->belongsTo('App\Transaction'); } 

    public function itemDiscounts() {   return $this->belongsToMany('App\ItemDiscount')  ->withTimestamps(); } 
    public function itemPrices() {      return $this->belongsToMany('App\ItemPrice')     ->withTimestamps(); } 
    public function itemImages() {      return $this->belongsToMany('App\ItemImage')     ->withTimestamps(); }

}
