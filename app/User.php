<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'given_name', 'middle_name', 'last_name', 'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function role() {        return $this->belongsTo('App\Role'); } 
    public function logs() {        return $this->hasMany('App\UserLog'); } 
    public function photos() {      return $this->hasMany('App\UserPhoto'); } 
    public function discounts() {   return $this->hasMany('App\Discount'); } 
    public function inventories() { return $this->hasMany('App\Inventory'); } 
}
