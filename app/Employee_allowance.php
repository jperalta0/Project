<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee_allowance extends Model
{
    protected $guarded = [];

    public function employee(){
        return $this->belongsTo('App\Employee','employee_id','id');
    }

    public function allowance(){
        return $this->belongsTo('App\Allowance','allowance_id','id');
    }
}
