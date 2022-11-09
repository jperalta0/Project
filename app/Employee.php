<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $guarded = [];
    
    public function getFullNameAttribute(){
        return $this->lastname.', '.$this->firstname.' '.$this->middlename.' '.$this->extname;
    }

    public function geninfo(){
    	return $this->hasOne('App\Employee_general_info', 'employee_id', 'id');
    }

    public function salinfo(){
    	return $this->hasOne('App\Employee_salary_info', 'employee_id', 'id');
    }

    public function schedule(){
    	return $this->hasOne('App\Employee_schedule', 'employee_id', 'id');
    }

    public function restdays(){
    	return $this->hasMany('App\Employee_restday', 'employee_id', 'id');
    }

    public function dependents(){
        return $this->hasMany('App\Employee_dependent', 'employee_id', 'id');
    }

    public function educations(){
        return $this->hasMany('App\Employee_education', 'employee_id', 'id');
    }

    public function works(){
        return $this->hasMany('App\Employee_work', 'employee_id', 'id');
    }

    public function seminars(){
        return $this->hasMany('App\Employee_seminar', 'employee_id', 'id');
    }

    public function leavecredits(){
        return $this->hasMany('App\Employee_leave_credit', 'employee_id', 'id');
    }
}
