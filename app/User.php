<?php

namespace App;

use Carbon\Carbon;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

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

    public function role() {            return $this->belongsTo('App\Role'); } 
    public function userLogs() {        return $this->hasMany('App\UserLog'); } 
    public function userPhotos() {      return $this->hasMany('App\UserPhoto'); } 
    public function itemDiscounts() {   return $this->hasMany('App\ItemDiscount'); } 
    public function inventories() {     return $this->hasMany('App\Inventory'); } 

    public function getNameAttribute() {
        return ucfirst($this->given_name) . ' ' . ucfirst($this->last_name);
    }

    public function getDateCreatedAttribute() {
        return Carbon::parse($this->created_at)->diffForHumans();
    }

}
