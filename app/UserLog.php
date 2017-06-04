<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'log', 'remarks',
    ];

    public function user() { return $this->belongsTo('App\User'); } 
}
