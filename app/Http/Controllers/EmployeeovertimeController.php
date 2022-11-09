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
use Illuminate\Support\Arr;

class EmployeeovertimeController extends Controller
{
	public function __construct(){
	    $this->middleware('auth');
	}
	
    public function index(){
    	if (Request::ajax()) {

    		if(Auth::user()->usertype == 1){
    			$data = \App\Employee_overtime::latest()->get();
    		}else{
    			$data = \App\Employee_overtime::where('employee_id',Auth::user()->employee_id)->latest()->get();
    		}

	        return Datatables::of($data)
	        ->editColumn('date', function($data){
	        	return Carbon::parse($data->date)->toFormattedDateString();
	        })
	        ->editColumn('employee_id', function($data){
	        	return $data->employee->FullName;
	        })
	        ->editColumn('approved', function($data){
	        	if($data->approved == 0){
	        		$app = 'PENDING';
	        	}else{
	        		$app = 'APPROVED';
	        	}
	        	return $app;
	        })
            ->addColumn('action', function($data){
            	if(Auth::user()->usertype == 1){
            		if($data->approved == 0){
						$btn = '<center>
		            		<a href="#edit'.$data->id.'" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit"></i></a>
		            		<a href="#delete'.$data->id.'" class="btn btn-danger btn-sm" data-toggle="modal"><i class="fa fa-trash"></i></a>
		            		<a href="'.action('EmployeeovertimeController@approve',$data->id).'" class="btn btn-success btn-sm"><i class="fa fa-thumbs-up"></i></a>
		            	</center>';
		            }elseif($data->approved == 1){
		            	$btn = '<center>
		            		<a href="#edit'.$data->id.'" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit"></i></a>
		            		<a href="#delete'.$data->id.'" class="btn btn-danger btn-sm" data-toggle="modal"><i class="fa fa-trash"></i></a>
		            	</center>';
		            }
		        }else{
		        	if($data->approved == 0){
	            		$btn = '<center>
		            		<a href="#edit'.$data->id.'" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit"></i></a>
		            		<a href="#delete'.$data->id.'" class="btn btn-danger btn-sm" data-toggle="modal"><i class="fa fa-trash"></i></a>
		            	</center>';
		            }else{
		            	$btn = '<center> - </center>';
		            }
		        }
				return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
	    }
    	$employees = \App\Employee::orderBy('lastname')->get()->pluck('FullName','id');
    	$employee_ot = \App\Employee_overtime::get();
    	return view('admin.employee-overtimes.index',compact('employees','employee_ot'));
    }

    public function store(){
    	if(Auth::user()->usertype == 1){
    		$validator = Validator::make(Request::all(), [
	    		'date'						=>	'required',
			    'employee_id'				=>	'required',
			    'ot_date'					=>	'required',
			    'ot_time_start'				=>	'required',
			    'ot_time_end'				=>	'required',
			    'ot_hours'					=>	'required|numeric',
			    'ot_minutes'				=>	'required|numeric',
			],
			[
				'date.required'     		=>	'Date Required',
			    'employee_id.required'     	=>	'Please Select Employee',
			    'ot_date.required'			=>	'Overtime Date Required',
			    'ot_time_start.required'    =>	'Time Start Required',
			    'ot_time_end.required'  	=>	'Time End Required',
			    'ot_hours.required'     	=>	'OT Hours Required',
			    'ot_minutes.required'     	=>	'OT Minutes Required',
			]);
    	}else{
    		$validator = Validator::make(Request::all(), [
	    		'date'						=>	'required',
			    //'employee_id'				=>	'required',
			    'ot_date'					=>	'required',
			    'ot_time_start'				=>	'required',
			    'ot_time_end'				=>	'required',
			    'ot_hours'					=>	'required|numeric',
			    'ot_minutes'				=>	'required|numeric',
			],
			[
				'date.required'     		=>	'Date Required',
			    //'employee_id.required'     	=>	'Please Select Employee',
			    'ot_date.required'			=>	'Overtime Date Required',
			    'ot_time_start.required'    =>	'Time Start Required',
			    'ot_time_end.required'  	=>	'Time End Required',
			    'ot_hours.required'     	=>	'OT Hours Required',
			    'ot_minutes.required'     	=>	'OT Minutes Required',
			]);
    	}
    	

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		$data = Request::except('ot_time_start','ot_time_end','ot_hours');
		$converted_timein = date("H:i", strtotime(Request::get('ot_time_start')));
		$converted_timeout = date("H:i", strtotime(Request::get('ot_time_end')));

		$converted_othours = Request::get('ot_hours');
		$converted_otminutes = Request::get('ot_minutes');

		$hours = $converted_othours + ($converted_otminutes / 60);

		if(Auth::user()->usertype == 1){
			$approved = 1;
			$employee_id = Request::get('employee_id');
		}else{
			$approved = 0;
			$employee_id = Auth::user()->employee_id;
		}

		$data = Arr::add($data, 'ot_time_start', $converted_timein);
		$data = Arr::add($data, 'ot_time_end', $converted_timeout);
		$data = Arr::add($data, 'ot_hours', $hours);
		$data = Arr::add($data, 'employee_id', $employee_id);
		$data = Arr::add($data, 'approved', $approved);

		\App\Employee_overtime::create($data);
		toastr()->success('Employee Overtime Created Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function update($id){
    	$employee_ot = \App\Employee_overtime::find($id);
    	$validator = Validator::make(Request::all(), [
    		'date'						=>	'required',
		    'employee_id'				=>	'required',
		    'ot_date'					=>	'required',
		    'ot_time_start'				=>	'required',
		    'ot_time_end'				=>	'required',
		    'ot_hours'					=>	'required|numeric',
		    'ot_minutes'				=>	'required|numeric',
		],
		[
			'date.required'     		=>	'Date Required',
		    'employee_id.required'     	=>	'Please Select Employee',
		    'ot_date.required'			=>	'Overtime Date Required',
		    'ot_time_start.required'    =>	'Time Start Required',
		    'ot_time_end.required'  	=>	'Time End Required',
		    'ot_hours.required'     	=>	'OT Hours Required',
		    'ot_minutes.required'     	=>	'OT Minutes Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		$data = Request::except('ot_time_start','ot_time_end','ot_hours');
		$converted_timein = date("H:i", strtotime(Request::get('ot_time_start')));
		$converted_timeout = date("H:i", strtotime(Request::get('ot_time_end')));

		$converted_othours = Request::get('ot_hours');
		$converted_otminutes = Request::get('ot_minutes');

		$hours = $converted_othours + ($converted_otminutes / 60);

		$data = Arr::add($data, 'ot_time_start', $converted_timein);
		$data = Arr::add($data, 'ot_time_end', $converted_timeout);
		$data = Arr::add($data, 'ot_hours', $hours);

		$employee_ot->update($data);
		toastr()->success('Employee Overtime Updated Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function delete($id){
    	$employee_ot = \App\Employee_overtime::find($id);
    	$employee_ot->delete();
		toastr()->success('Employee Overtime Deleted Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function approve($id){
    	$employee_ot = \App\Employee_overtime::find($id);
    	$employee_ot->update([
    		'approved'	=>	1,
    	]);

    	toastr()->success('Employee Overtime Approved Successfully', config('global.system_name'));
    	return redirect()->back();
    }
}
