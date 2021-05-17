<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class event extends Model
{
    //protected $guarded = [];

    public function comments(){
        $this->hasMany('App\Models\comment', 'event_id', 'id');
    }
    //
}
