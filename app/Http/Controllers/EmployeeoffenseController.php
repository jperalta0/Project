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

class EmployeeoffenseController extends Controller
{
	public function __construct(){
	    $this->middleware('auth');
	}
	
    public function index(){
    	if (Request::ajax()) {
    		if(Auth::user()->usertype == 1){
    			$data = \App\Employee_offense::latest()->get();
    		}else{
    			$data = \App\Employee_offense::where('employee_id',Auth::user()->employee_id)->latest()->get();
    		}

	        $data = \App\Employee_offense::latest()->get();
	        return Datatables::of($data)
	        ->editColumn('date', function($data){
	        	return Carbon::parse($data->date)->toFormattedDateString();
	        })
	        ->editColumn('employee_id', function($data){
	        	return $data->employee->FullName;
	        })
	        ->editColumn('violation_id', function($data){
	        	return $data->violation->name;
	        })
	        ->editColumn('sanction_id', function($data){
	        	return $data->sanction->name;
	        })
            ->addColumn('action', function($data){
            	if(Auth::user()->usertype == 1){
					$btn = '<center>
	            		<a href="#edit'.$data->id.'" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit"></i></a>
	            		<a href="#delete'.$data->id.'" class="btn btn-danger btn-sm" data-toggle="modal"><i class="fa fa-trash"></i></a>
	            	</center>';
	            }else{
	            	$btn = '<center>LOCKED</center>';
	            }
				return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
	    }
    	$employees = \App\Employee::orderBy('lastname')->get()->pluck('FullName','id');
    	$violations = \App\Violation::orderBy('name')->get()->pluck('name','id');
    	$sanctions = \App\Sanction::orderBy('name')->get()->pluck('name','id');
    	$employee_offenses = \App\Employee_offense::get();
    	return view('admin.employee-offenses.index',compact('employees','violations','sanctions','employee_offenses'));
    }

    public function store(){
    	$validator = Validator::make(Request::all(), [
    		'date'						=>	'required',
		    'employee_id'				=>	'required',
		    'violation_id'				=>	'required',
		    'sanction_id'				=>	'required',
		],
		[
			'date.required'     		=>	'Date Required',
		    'employee_id.required'     	=>	'Please Select Employee',
		    'violation_id.required'		=>	'Please Select Violation',
		    'sanction_id.required'     	=>	'Please Select Sanction',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		\App\Employee_offense::create(Request::all());
		toastr()->success('Employee Offense Created Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function update($id){
    	$employee_offense = \App\Employee_offense::find($id);
    	$validator = Validator::make(Request::all(), [
    		'date'						=>	'required',
		    'employee_id'				=>	'required',
		    'violation_id'				=>	'required',
		    'sanction_id'				=>	'required',
		],
		[
			'date.required'     		=>	'Date Required',
		    'employee_id.required'     	=>	'Please Select Employee',
		    'violation_id.required'		=>	'Please Select Violation',
		    'sanction_id.required'     	=>	'Please Select Sanction',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		$employee_offense->update(Request::all());
		toastr()->success('Employee Offense Updated Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function delete($id){
    	$employee_offense = \App\Employee_offense::find($id);
    	$employee_offense->delete();
    	toastr()->success('Employee Offense Deleted Successfully', config('global.system_name'));
    	return redirect()->back();
    }

}
