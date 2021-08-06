<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class lockbox_permissions extends Model
{
    protected $table = 'lockbox_permissions';
    protected $fillable = ['id','user_id','lockbox_id','r','u','d'];

    public function lockbox(){
        return $this->hasMany('App\Models\lockbox','lockbox_id','id');
    }
}
