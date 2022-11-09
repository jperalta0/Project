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

class EmployeeallowanceController extends Controller
{
	public function __construct(){
	    $this->middleware('auth');
	}
	
    public function index(){
	    if (Request::ajax()) {
	        $data = \App\Employee_allowance::latest()->get();
	        return Datatables::of($data)
	        ->editColumn('employee_id', function($data){
	        	return $data->employee->FullName;
	        })
	        ->editColumn('allowance_id', function($data){
	        	return $data->allowance->name;
	        })
	        ->editColumn('amount', function($data){
	        	return number_format($data->amount,2);
	        })
	        ->editColumn('isMonthly', function($data){
	        	if($data->isMonthly == 1){
	        		$isMonthly = 'MONTHLY';
	        	}else{
	        		$isMonthly = '';
	        	}
	        	return $isMonthly;
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
    	$allowances = \App\Allowance::orderBy('name')->get()->pluck('name','id');
    	$employee_allowances = \App\Employee_allowance::get();
    	return view('admin.employee-allowances.index',compact('employees','allowances','employee_allowances'));
	}

	public function store(){
    	$validator = Validator::make(Request::all(), [
		    'employee_id'				=>	'required',
		    'allowance_id'				=>	'required',
		    'amount'					=>	'required|numeric|between:0,9999999.99',
		],
		[
		    'employee_id.required'     	=>	'Please Select Employee',
		    'allowance_id.required'		=>	'Please Select Allowance Type',
		    'amount.required'     		=>	'Amount Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		if(Request::get('isMonthly') == 1){
			$isMonthly = 1;
		}else{
			$isMonthly = 0;
		}

		\App\Employee_allowance::create([
			'employee_id'		=>		Request::get('employee_id'),
			'allowance_id'		=>		Request::get('allowance_id'),
			'amount'			=>		Request::get('amount'),
			'isMonthly'			=>		$isMonthly,
		]);
		toastr()->success('Employee Allowance Created Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function update($id){
    	$employee_allowance = \App\Employee_allowance::find($id);
    	$validator = Validator::make(Request::all(), [
		    'employee_id'				=>	'required',
		    'allowance_id'				=>	'required',
		    'amount'					=>	'required|numeric|between:0,9999999.99',
		],
		[
		    'employee_id.required'     	=>	'Please Select Employee',
		    'allowance_id.required'		=>	'Please Select Allowance Type',
		    'amount.required'     		=>	'Amount Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		if(Request::get('isMonthly') == 1){
			$isMonthly = 1;
		}else{
			$isMonthly = 0;
		}

		$employee_allowance->update([
			'employee_id'		=>		Request::get('employee_id'),
			'allowance_id'		=>		Request::get('allowance_id'),
			'amount'			=>		Request::get('amount'),
			'isMonthly'			=>		$isMonthly,
		]);
		toastr()->success('Employee Allowance Updated Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function delete($id){
    	$employee_allowance = \App\Employee_allowance::find($id);
    	$employee_allowance->delete();
    	toastr()->success('Employee Allowance Deleted Successfully', config('global.system_name'));
    	return redirect()->back();
    }

}
