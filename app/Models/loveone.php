<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class loveone extends Model
{
    protected $guarded = [];

    /**
     * Get the careteam members
     */
    public function careteam() {
        return $this->hasMany('App\Models\careteam', 'loveone_id', 'id');
    }
}
