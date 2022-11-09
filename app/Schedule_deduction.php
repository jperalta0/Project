<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule_deduction extends Model
{
    protected $guarded = [];

    public function employee(){
        return $this->belongsTo('App\Employee','employee_id','id');
    }

    public function deduction(){
        return $this->belongsTo('App\Deduction','deduction_id','id');
    }
}
