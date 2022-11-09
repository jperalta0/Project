<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee_loan extends Model
{
    protected $guarded = [];

    public function employee(){
        return $this->belongsTo('App\Employee','employee_id','id');
    }

    public function loan(){
        return $this->belongsTo('App\Loan','loan_id','id');
    }
}
