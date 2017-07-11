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
        'percent', 'type', 'remarks', 'start_date', 'end_date',
    ];

    protected $appends  = ['created', 'modified'];

    public function user() {        return $this->belongsTo('App\User'); } 
    public function inventories() { return $this->belongsToMany('App\Inventory')->withTimestamps(); } 

    public function setStartDateAttribute($date)
    {
        $this->attributes['start_date'] = \Carbon\Carbon::parse($date);
    }

    public function setEndDateAttribute($date)
    {
        $this->attributes['end_date'] = \Carbon\Carbon::parse($date);
    }

    public function getCreatedAttribute()
    {
        return \Carbon\Carbon::parse($this->created_at)->diffForHumans();
    }

    public function getModifiedAttribute()
    {
        return \Carbon\Carbon::parse($this->updated_at)->diffForHumans();
    }

    // public function getStartDateAttribute($date)
    // {
    //     return \Carbon\Carbon::parse($date);
    // }

    // public function getEndDateAttribute($date)
    // {
    //     return \Carbon\Carbon::parse($date);
    // }
}
