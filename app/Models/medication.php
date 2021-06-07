<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class medication extends Model
{
    protected $fillable = ['id','loveone_id','ini_date','medicine','dosage','end_date','time','frequency','notes','days'];

    public function medlist(){
        return $this->hasMany('App\Models\medlist','medication_id','id');
    }
}
