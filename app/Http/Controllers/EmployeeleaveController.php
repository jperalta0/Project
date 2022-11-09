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

class EmployeeleaveController extends Controller
{
	public function __construct(){
	    $this->middleware('auth');
	}
	
    public function index(){
    	if (Request::ajax()) {

    		if(Auth::user()->usertype == 1){
    			$data = \App\Employee_leave::latest()->get();
    		}else{
    			$data = \App\Employee_leave::where('employee_id',Auth::user()->employee_id)->latest()->get();
    		}

	        return Datatables::of($data)
	        ->editColumn('date', function($data){
	        	return Carbon::parse($data->date)->toFormattedDateString();
	        })
	        ->editColumn('employee_id', function($data){
	        	return $data->employee->FullName;
	        })
	        ->editColumn('leave_id', function($data){
	        	return $data->leave->name;
	        })
	        ->editColumn('withPay', function($data){
	        	if($data->withPay == 1){
	        		$status = 'WITH PAY';
	        	}else{
	        		$status = 'WITHOUT PAY';
	        	}
	        	return $status;
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
		            		<a href="'.action('EmployeeleaveController@edit',$data->id).'" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
		            		<a href="#delete'.$data->id.'" class="btn btn-danger btn-sm" data-toggle="modal"><i class="fa fa-trash"></i></a>
		            		<a href="'.action('EmployeeleaveController@approve',$data->id).'" class="btn btn-success btn-sm"><i class="fa fa-thumbs-up"></i></a>
		            	</center>';
	            	}elseif($data->approved == 1){
	            		$btn = '<center>
		            		<a href="'.action('EmployeeleaveController@edit',$data->id).'" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
		            		<a href="#delete'.$data->id.'" class="btn btn-danger btn-sm" data-toggle="modal"><i class="fa fa-trash"></i></a>
		            	</center>';
	            	}
            	}else{
            		if($data->approved == 0){
	            		$btn = '<center>
		            		<a href="'.action('EmployeeleaveController@edit',$data->id).'" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
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
    	$leaves = \App\Leave::orderBy('name')->get()->pluck('name','id');
    	$employee_leaves = \App\Employee_leave::get();
    	return view('admin.employee-leaves.index',compact('employees','leaves','employee_leaves'));
    }

    public function store(){
    	if(Auth::user()->usertype == 1){
	    	$validator = Validator::make(Request::all(), [
	    		'date'						=>	'required',
			    'employee_id'				=>	'required',
			    'leave_id'					=>	'required',
			    'inclusiveDates.*'			=>	'required',
			],
			[
				'date.required'     		=>	'Leave Date Required',
			    'employee_id.required'     	=>	'Please Select Employee',
			    'leave_id.required'			=>	'Please Select Leave Type',
			    'inclusiveDates.*.required'	=>	'Please Select Dates',
			]);
		}else{
			$validator = Validator::make(Request::all(), [
	    		'date'						=>	'required',
			    //'employee_id'				=>	'required',
			    'leave_id'					=>	'required',
			    'inclusiveDates.*'			=>	'required',
			],
			[
				'date.required'     		=>	'Leave Date Required',
			    //'employee_id.required'     	=>	'Please Select Employee',
			    'leave_id.required'			=>	'Please Select Leave Type',
			    'inclusiveDates.*.required'	=>	'Please Select Dates',
			]);
		}

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		// if(Request::get('withPay') == 1){
		// 	$withPay = 1;
		// }else{
		// 	$withPay = 0;
		// }

		if(Auth::user()->usertype == 1){
			$employee_id = Request::get('employee_id');
			$approved = 1;
		}else{
			$employee_id = Auth::user()->employee_id;
			$approved = 0;
		}

		foreach(Request::get('inclusiveDates') as $key => $value){
            $c = explode(',',$value);
        }

		$check = \App\Employee_leave_credit::where('employee_id',$employee_id)
			->where('leave_id',Request::get('leave_id'))
			->first();

		//dd($check->credit);

		if($check !=NULL){
			$leave_count = \App\Employee_leave::where('employee_id',$employee_id)
				->where('leave_id',Request::get('leave_id'))
				->count();

			if($check->credit > ($leave_count + count($c))){
				$employee_leave_id = \App\Employee_leave::create([
					'date'					=>			Request::get('date'),
					'employee_id'			=>			$employee_id,
					'leave_id'				=>			Request::get('leave_id'),
					'withPay'				=>			1,
					'remarks'				=>			Request::get('remarks'),
					'approved'				=>			$approved,
				])->id;

				$dates = [];
		        foreach(Request::get('inclusiveDates') as $key => $value){
		            $c = explode(',',$value);
		            foreach($c as $cdate){
		                \App\Employee_leave_detail::create([
		                	'employee_leave_id'	=>		$employee_leave_id,
		                    'leave_date'    	=>      $cdate,
		                    'date'              =>      Request::get('date'),
		                    'employee_id'		=>		$employee_id,
		                ]);
		            }
		        }

		    	toastr()->success('Employee Leave Created Successfully', config('global.system_name'));
    			return redirect()->back();
			}else{
				toastr()->error('Employee Leave Application Already Maximized or Exceeded the alloted leave credits', config('global.system_name'));
    			return redirect()->back();
			}
		}else{
			toastr()->error('You are not allowed to take this leave', config('global.system_name'));
    		return redirect()->back();
		}
    }

    public function edit($id){
    	$employee_leave = \App\Employee_leave::find($id);
    	$employees = \App\Employee::orderBy('lastname')->get()->pluck('FullName','id');
    	$leaves = \App\Leave::orderBy('name')->get()->pluck('name','id');
    	return view('admin.employee-leaves.edit',compact('employees','leaves','employee_leave'));
    }

    public function update($id){
    	$employee_leave = \App\Employee_leave::find($id);
    	$validator = Validator::make(Request::all(), [
    		'date'						=>	'required',
		    'employee_id'				=>	'required',
		    'leave_id'					=>	'required',
		],
		[
			'date.required'     		=>	'Leave Date Required',
		    'employee_id.required'     	=>	'Please Select Employee',
		    'leave_id.required'			=>	'Please Select Leave Type',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		if(Request::get('withPay') == 1){
			$withPay = 1;
		}else{
			$withPay = 0;
		}

		if(Auth::user()->usertype == 1){
			$employee_id = Request::get('employee_id');
		}else{
			$employee_id = Auth::user()->employee_id;
		}

		$employee_leave->update([
			'date'					=>			Request::get('date'),
			'employee_id'			=>			$employee_id,
			'leave_id'				=>			Request::get('leave_id'),
			'withPay'				=>			$withPay,
			'remarks'				=>			Request::get('remarks'),
		]);

		foreach(Request::get('inclusiveDates') as $key => $value){
			if(!empty($value)){
				$c = explode(',',$value);
	            foreach($c as $cdate){
	                \App\Employee_leave_detail::updateOrCreate([
	                	'leave_date'    	=>      $cdate,
	                ],[
	                	'employee_leave_id'	=>		$employee_leave->id,
	                    'date'              =>      Request::get('date'),
	                    'employee_id'		=>		Request::get('employee_id'),
	                ]);
	            }
			}
        }

        toastr()->success('Employee Leave Updated Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function delete_leave_date($id){
    	$leave_date = \App\Employee_leave_detail::find($id);
    	$leave_date->delete();

    	toastr()->success('Employee Leave Date Deleted Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function delete($id){
    	$employee_leave = \App\Employee_leave::find($id);
    	$employee_leave->leavedates()->delete();
    	$employee_leave->delete();
    	toastr()->success('Employee Leave Deleted Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function approve($id){
    	$employee_leave = \App\Employee_leave::find($id);
    	$employee_leave->update([
    		'approved'	=>	1,
    	]);

    	toastr()->success('Employee Leave Approved Successfully', config('global.system_name'));
    	return redirect()->back();
    }
}
