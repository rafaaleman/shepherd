<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class discussion extends Model
{
    protected $fillable = ['id','loveone_id','name','last_message','status','owner_id','users'];

    public function messages(){
        return $this->hasMany('App\Models\message_discussion','discussion_id','id')->with('creator');
    }

    public function owner(){
        return $this->belongsTo('App\User', 'owner_id', 'id');
    }
}
