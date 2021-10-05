<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class medication extends Model
{
    protected $fillable = ['id','loveone_id','ini_date','medicine','route','dosage','end_date','time','frequency','notes','days','drugbank_pcid','prescribing'];

    public function medlist(){
        return $this->hasMany('App\Models\medlist','medication_id','id');
    }
}
