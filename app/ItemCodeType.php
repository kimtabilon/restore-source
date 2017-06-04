<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemCodeType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description',
    ];

    public function itemCodes() { return $this->hasMany('App\ItemCode'); } 
}
