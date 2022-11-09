<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee_leave_detail extends Model
{
    protected $guarded = [];

    public function employee_leave(){
        return $this->belongsTo('App\Employee_leave','employee_leave_id','id');
    }
}
