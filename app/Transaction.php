<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'da_number', 'dt_number',
    ];

    public function inventories() {     return $this->hasMany('App\Inventory'); }
    public function paymentType() {     return $this->belongsTo('App\PaymentType'); }
}
