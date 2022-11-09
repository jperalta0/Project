<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee_leave extends Model
{
    protected $guarded = [];

    public function employee(){
        return $this->belongsTo('App\Employee','employee_id','id');
    }

    public function leave(){
        return $this->belongsTo('App\Leave','leave_id','id');
    }

    public function leavedates(){
    	return $this->hasMany('App\Employee_leave_detail', 'employee_leave_id', 'id')->orderBy('leave_date');
    }
}
