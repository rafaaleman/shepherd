<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class message extends Model
{
    protected $fillable = ['event_id','message','creator_id','status','date','time'];

    public function creator(){
        return $this->belongsTo('App\Models\careteam', 'creator_id', 'id')->with('user');
    }
}
