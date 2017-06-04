<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemStatus extends Model
{

	protected $table = 'item_status';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description',
    ];

    public function inventories() { return $this->hasMany('App\Inventory'); } 
}
