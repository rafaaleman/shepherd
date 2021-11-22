<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class discussion extends Model
{
    protected $fillable = ['id','loveone_id','name','notes','status','creator_id'];

    public function messages(){
        return $this->hasMany('App\Models\message_discussion','discussion_id','id')->with('creator');
    }

    public function creator(){
        return $this->belongsTo('App\User', 'creator_id', 'id');
    }
}
