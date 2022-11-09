<?php

namespace App\Http\Controllers;

use Excel;
use Validator;
use Auth;
use PDF;
use DB;
use Session;
use Input;
use Request;
use DateTime;
use Hash;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;

class ScheduledeductionController extends Controller
{
	public function __construct(){
	    $this->middleware('auth');
	}
	
    public function index(){
    	if (Request::ajax()) {
	        $data = \App\Schedule_deduction::latest()->get();
	        return Datatables::of($data)
	        ->editColumn('employee_id', function($data){
	        	return $data->employee->FullName;
	        })
	        ->editColumn('deduction_id', function($data){
	        	return $data->deduction->name;
	        })
	        ->editColumn('quincena_id', function($data){
	        	if($data->quincena_id == 1){
	        		$q = 'FIRST';
	        	}else{
	        		$q = 'SECOND';
	        	}
	        	return $q;
	        })
	        ->editColumn('amount', function($data){
	        	return number_format($data->amount,2);
	        })
            ->addColumn('action', function($data){
				$btn = 
				'<center>
            		<a href="#edit'.$data->id.'" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit"></i></a>
            		<a href="#delete'.$data->id.'" class="btn btn-danger btn-sm" data-toggle="modal"><i class="fa fa-trash"></i></a>
            	</center>';
				return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
	    }
	    $employees = \App\Employee::orderBy('lastname')->get()->pluck('FullName','id');
	    $deductions = \App\Deduction::orderBy('name')->get()->pluck('name','id');
	    $schedule_deductions = \App\Schedule_deduction::get();
    	return view('admin.schedule-deductions.index',compact('schedule_deductions','employees','deductions'));
    }

    public function store(){
    	$validator = Validator::make(Request::all(), [
		    'employee_id'				=>	'required',
		    'deduction_id'				=>	'required',
		    'quincena_id'				=>	'required',
		    'amount'					=>	'required|numeric|between:0,9999999.99',
		],
		[
		    'employee_id.required'     	=>	'Please Select Employee',
	    	'deduction_id.required'		=>	'Please Select Deduction',
		    'quincena_id.required'		=>	'Please Select Quincena',
		    'amount.required'			=>	'Amount Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		\App\Schedule_deduction::create(Request::all());
        toastr()->success('Employee Schedule Deduction Created Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function update($id){
    	$schedule_deduction = \App\Schedule_deduction::find($id);
    	$validator = Validator::make(Request::all(), [
		    'employee_id'				=>	'required',
		    'deduction_id'				=>	'required',
		    'quincena_id'				=>	'required',
		    'amount'					=>	'required|numeric|between:0,9999999.99',
		],
		[
		    'employee_id.required'     	=>	'Please Select Employee',
	    	'deduction_id.required'		=>	'Please Select Deduction',
		    'quincena_id.required'		=>	'Please Select Quincena',
		    'amount.required'			=>	'Amount Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		$schedule_deduction->update(Request::all());
        toastr()->success('Employee Schedule Deduction Updated Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function delete($id){
    	$schedule_deduction = \App\Schedule_deduction::find($id);
    	$schedule_deduction->delete();
    	toastr()->success('Employee Schedule Deduction Deleted Successfully', config('global.system_name'));
    	return redirect()->back();
    }
}
