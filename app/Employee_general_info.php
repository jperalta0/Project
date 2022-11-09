<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee_general_info extends Model
{
    protected $guarded = [];

    public function employee(){
        return $this->belongsTo('App\Employee','employee_id','id');
    }

    public function campus(){
        return $this->belongsTo('App\Campus','campus_id','id');
    }
}
