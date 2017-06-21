<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemDiscount extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'percent', 'remarks', 'start_date', 'end_date',
    ];

    public function user() {        return $this->belongsTo('App\User'); } 
    public function inventories() { return $this->belongsToMany('App\Inventory')->withTimestamps(); } 
}
