<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class lockbox extends Model
{
    protected $table = 'lockbox';
    protected $with = ['permissions'];
    //
    protected $fillable = ['id','user_id','loveones_id','lockbox_types_id','name','description','file','status'];

    public function permissions(){
        return $this->hasMany('App\Models\lockbox_permissions','lockbox_id','id');
    }
}
