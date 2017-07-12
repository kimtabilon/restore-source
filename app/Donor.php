<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'given_name', 'middle_name', 'last_name', 'email',
    ];
    protected $appends = ['name'];

    public function profile() {     return $this->hasOne('App\Profile'); } 
    public function inventories() { return $this->belongsToMany('App\Inventory')->withTimestamps(); } 
    public function donorType() {   return $this->belongsTo('App\DonorType'); } 
    public function storeCredits() {return $this->hasMany('App\StoreCredit'); } 


    public function getNameAttribute()
    {
        return preg_replace('/\s+/', ' ',$this->given_name.' '.$this->middle_name.' '.$this->last_name);
    }
}
