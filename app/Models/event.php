<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class event extends Model
{
    protected $guarded = [];
    protected $fillable = ['id','loveone_id','creator_id','name','location','date','time','assigned_ids','notes','status'];

    public function messages(){
        return $this->hasMany('App\Models\message','event_id','id')->with('creator');
    }

    public function creator(){
        return $this->belongsTo('App\User', 'creator_id', 'id');
    }

}
