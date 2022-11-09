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

class EmployeeleavecreditController extends Controller
{
	public function __construct(){
	    $this->middleware('auth');
	}
	
    public function index(){
    	if (Request::ajax()) {
	        $data = \App\Employee_leave_credit::latest()->get();
	        return Datatables::of($data)
	        ->editColumn('employee_id', function($data){
	        	return $data->employee->FullName;
	        })
	        ->editColumn('leave_id', function($data){
	        	return $data->leave->name;
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
    	$leaves = \App\Leave::orderBy('name')->get()->pluck('name','id');
    	$employee_leave_credits = \App\Employee_leave_credit::get();
    	return view('admin.employee-leave-credits.index',compact('employees','leaves','employee_leave_credits'));
    }

    public function store(){
    	$validator = Validator::make(Request::all(), [
		    'employee_id'				=>	'required',
		    'leave_id'					=>	'required',
		    'credit'					=>	'required',
		],
		[
		    'employee_id.required'     	=>	'Please Select Employee',
		    'leave_id.required'			=>	'Please Select Leave Type',
		    'credit.required'			=>	'Leave Credit Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		\App\Employee_leave_credit::create(Request::all());

        toastr()->success('Employee Leave Credit Created Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function update($id){
    	$employee_leave_credit = \App\Employee_leave_credit::find($id);
    	$validator = Validator::make(Request::all(), [
		    'employee_id'				=>	'required',
		    'leave_id'					=>	'required',
		    'credit'					=>	'required',
		],
		[
		    'employee_id.required'     	=>	'Please Select Employee',
		    'leave_id.required'			=>	'Please Select Leave Type',
		    'credit.required'			=>	'Leave Credit Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		$employee_leave_credit->update(Request::all());

        toastr()->success('Employee Leave Credit Updated Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function delete($id){
    	$employee_leave_credit = \App\Employee_leave_credit::find($id);
    	$employee_leave_credit->delete();

        toastr()->success('Employee Leave Credit Deleted Successfully', config('global.system_name'));
    	return redirect()->back();
    }
}
