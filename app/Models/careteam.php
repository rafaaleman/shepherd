<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class careteam extends Model
{
    protected $guarded = [];

    /**
     * Get the careteam row
     */
    // public function getLoveones() {
    //     return $this->hasOne('App\Models\loveone', 'id', 'loveone_id');
    // }
    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
