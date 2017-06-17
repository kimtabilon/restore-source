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
    protected $fillable = [
        'quantity', 'remarks'
    ];

    public function user() {        return $this->belongsTo('App\User'); } 
    public function donor() {       return $this->belongsTo('App\Donor'); } 
    public function item() {        return $this->belongsTo('App\Item'); } 
    public function itemDiscount() {return $this->belongsTo('App\ItemDiscount'); } 
    public function itemPrice() {   return $this->belongsTo('App\ItemPrice'); } 
    public function itemStatus() {  return $this->belongsTo('App\ItemStatus'); } 
    public function itemImage() {   return $this->belongsTo('App\ItemImage'); } 
    public function transaction() { return $this->belongsTo('App\Transaction'); } 
}
