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

class AdjustmentController extends Controller
{
	public function __construct(){
	    $this->middleware('auth');
	}
	
    public function index(){
    	if (Request::ajax()) {
	        $data = \App\Adjustment::latest()->get();
	        return Datatables::of($data)
	        ->editColumn('date', function($data){
	        	return Carbon::parse($data->date)->toFormattedDateString();
	        })
	        ->editColumn('employee_id', function($data){
	        	return $data->employee->FullName;
	        })
	        ->editColumn('payment_type', function($data){
	        	if($data->payment_type == 1){
	        		$pt = 'INCOME';
	        	}else{
	        		$pt = 'DEDUCTION';
	        	}
	        	return $pt;
	        })
	        ->editColumn('amount', function($data){
	        	return number_format($data->amount,2);
	        })
            ->addColumn('action', function($data){
				$btn = '<center>
            		<a href="#edit'.$data->id.'" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit"></i></a>
            		<a href="#delete'.$data->id.'" class="btn btn-danger btn-sm" data-toggle="modal"><i class="fa fa-trash"></i></a>
            	</center>';
				return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
	    }
    	$employees = \App\Employee::orderBy('lastname')->get()->pluck('FullName','id');
    	$adjustments = \App\Adjustment::get();
    	return view('admin.adjustments.index',compact('employees','adjustments'));
    }

    public function store(){
    	$validator = Validator::make(Request::all(), [
    		'date'						=>	'required',
		    'employee_id'				=>	'required',
		    'payment_type'				=>	'required',
		    'description'				=>	'required',
		    'amount'					=>	'required|numeric|between:0,9999999.99',
		    
		],
		[
			'date.required'     		=>	'Date Required',
		    'employee_id.required'     	=>	'Please Select Employee',
		    'payment_type.required'		=>	'Please Select Payment Type',
		    'description.required'		=>	'Description Required',
		    'amount.required'			=>	'Amount Required',
		    
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		\App\Adjustment::create(Request::all());
		toastr()->success('Employee Adjustment Created Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function update($id){
    	$adjustment = \App\Adjustment::find($id);
    	$validator = Validator::make(Request::all(), [
    		'date'						=>	'required',
		    'employee_id'				=>	'required',
		    'payment_type'				=>	'required',
		    'description'				=>	'required',
		    'amount'					=>	'required|numeric|between:0,9999999.99',
		    
		],
		[
			'date.required'     		=>	'Date Required',
		    'employee_id.required'     	=>	'Please Select Employee',
		    'payment_type.required'		=>	'Please Select Payment Type',
		    'description.required'		=>	'Description Required',
		    'amount.required'			=>	'Amount Required',
		    
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		$adjustment->update(Request::all());
		toastr()->success('Employee Adjustment Updated Successfully', config('global.system_name'));
    	return redirect()->back();

    }

    public function delete($id){
    	$adjustment = \App\Adjustment::find($id);
    	$adjustment->delete();
    	toastr()->success('Employee Adjustment Deleted Successfully', config('global.system_name'));
    	return redirect()->back();
    }
}
