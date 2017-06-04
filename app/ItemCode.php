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

    public function item() { return $this->belongsTo('App\Item'); } 
    public function type() { return $this->belongsTo('App\ItemCodeType'); } 
}
