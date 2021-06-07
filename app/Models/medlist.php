<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class medlist extends Model
{
    protected $table ="medlist";

    public function medication()
    {
        return $this->belongsTo('App\Models\medication','medication_id','id');
    }
}
