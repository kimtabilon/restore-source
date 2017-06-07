<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SecondaryContact extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'information',
    ];

    public function profile() {         return $this->belongsTo('App\Profile'); }
    public function contactType() {     return $this->belongsTo('App\ContactType'); }
}
