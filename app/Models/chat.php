<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class chat extends Model
{
    protected $guarded = [];

    public function messages(){
//        return $this->hasMany('App\Models\message','event_id','id')->with('creator');
    }

}
