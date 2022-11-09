<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee_offense extends Model
{
    protected $guarded = [];

    public function employee(){
        return $this->belongsTo('App\Employee','employee_id','id');
    }

    public function violation(){
        return $this->belongsTo('App\Violation','violation_id','id');
    }

    public function sanction(){
        return $this->belongsTo('App\Sanction','sanction_id','id');
    }
}
