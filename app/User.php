<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'lastname', 'email', 'password', 'phone', 'dob', 'photo', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function permission($permission,$loveones){
        $care = $this->belongsTo('App\Models\careteam','id', 'user_id')->where('loveone_id',$loveones)->first();
        $permissions =  \unserialize($care->permissions);
        
        if($permissions[$permission]){
            return true;
        }else{
            return false;
        }
        

    }

}
