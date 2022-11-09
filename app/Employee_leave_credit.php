<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee_leave_credit extends Model
{
    protected $guarded = [];

    public function employee(){
        return $this->belongsTo('App\Employee','employee_id','id');
    }

    public function leave(){
        return $this->belongsTo('App\Leave','leave_id','id');
    }
}
