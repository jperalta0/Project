<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee_work extends Model
{
    protected $guarded = [];

    public function status(){
        return $this->belongsTo('App\Status','status_id','id');
    }
}
