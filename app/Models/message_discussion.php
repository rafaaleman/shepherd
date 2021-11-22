<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class message_discussion extends Model
{
    protected $fillable = ['discussion_id','message','creator_id','status','date','time'];
    protected $table = 'messages_discussion';
    public function creator(){
        return $this->belongsTo('App\Models\careteam', 'creator_id', 'id')->with('user');
    }
}
