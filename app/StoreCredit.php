<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreCredit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
    ];

    public function donor() { return $this->belongsTo('App\Donor'); }
}
