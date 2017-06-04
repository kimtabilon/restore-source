<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfilePhoto extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'primary', 'cover',
    ];

    public function profile() { return $this->belongsTo('App\Profile'); } 
}
