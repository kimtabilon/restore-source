<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description',
    ];

    public function contacts() { return $this->hasMany('App\SecondaryContact'); } 
}
