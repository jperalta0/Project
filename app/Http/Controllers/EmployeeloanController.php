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

class EmployeeloanController extends Controller
{
	public function __construct(){
	    $this->middleware('auth');
	}
	
    public function index(){
    	if (Request::ajax()) {
    		if(Auth::user()->usertype == 1){
    			$data = \App\Employee_loan::latest()->get();
    		}else{
    			$data = \App\Employee_loan::where('employee_id',Auth::user()->employee_id)->latest()->get();
    		}
	        
	        return Datatables::of($data)
	        ->editColumn('date', function($data){
	        	return Carbon::parse($data->date)->toFormattedDateString();
	        })
	        ->editColumn('employee_id', function($data){
	        	return $data->employee->FullName;
	        })
	        ->editColumn('loan_id', function($data){
	        	return $data->loan->name;
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
		            		<a href="'.action('EmployeeloanController@approve',$data->id).'" class="btn btn-success btn-sm"><i class="fa fa-thumbs-up"></i></a>
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
    	$loans = \App\Loan::orderBy('name')->get()->pluck('name','id');
    	$employee_loans = \App\Employee_loan::get();
    	return view('admin.employee-loans.index',compact('employees','loans','employee_loans'));
    }

    public function store(){
    	if(Auth::user()->usertype == 1){
    		$validator = Validator::make(Request::all(), [
	    		'date'						=>	'required',
			    'employee_id'				=>	'required',
			    'loan_id'					=>	'required',
			    'amount'					=>	'required|numeric|between:0,9999999.99',
			    'deducted_amount'			=>	'required|numeric|between:0,9999999.99',
			    'date_started'				=>	'required',
			    'date_ended'				=>	'required',
			],
			[
				'date.required'     		=>	'Date Required',
			    'employee_id.required'     	=>	'Please Select Employee',
			    'loan_id.required'			=>	'Please Select Loan Type',
			    'amount.required'     		=>	'Loan Amount Required',
			    'deducted_amount.required'  =>	'Deducted Amount Required',
			    'date_started.required'     =>	'Deduction Date Started Required',
			    'date_ended.required'     	=>	'Deduction Date End Required',
			]);
    	}else{
    		$validator = Validator::make(Request::all(), [
	    		'date'						=>	'required',
			    //'employee_id'				=>	'required',
			    'loan_id'					=>	'required',
			    'amount'					=>	'required|numeric|between:0,9999999.99',
			    'deducted_amount'			=>	'required|numeric|between:0,9999999.99',
			    'date_started'				=>	'required',
			    'date_ended'				=>	'required',
			],
			[
				'date.required'     		=>	'Date Required',
			    //'employee_id.required'     	=>	'Please Select Employee',
			    'loan_id.required'			=>	'Please Select Loan Type',
			    'amount.required'     		=>	'Loan Amount Required',
			    'deducted_amount.required'  =>	'Deducted Amount Required',
			    'date_started.required'     =>	'Deduction Date Started Required',
			    'date_ended.required'     	=>	'Deduction Date End Required',
			]);
    	}
    	

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		if(Auth::user()->usertype == 1){
			$approved = 1;
			$employee_id = Request::get('employee_id');
		}else{
			$approved = 0;
			$employee_id = Auth::user()->employee_id;
		}

		\App\Employee_loan::create([
			'date'						=>	Request::get('date'),
		    'employee_id'				=>	$employee_id,
		    'loan_id'					=>	Request::get('loan_id'),
		    'amount'					=>	Request::get('amount'),
		    'deducted_amount'			=>	Request::get('deducted_amount'),
		    'date_started'				=>	Request::get('date_started'),
		    'date_ended'				=>	Request::get('date_ended'),			
		    'remarks'					=>	Request::get('remarks'),
		    'approved'					=>	$approved,	
		]);

		toastr()->success('Employee Loan Created Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function update($id){
    	$employee_loan = \App\Employee_loan::find($id);
    	if(Auth::user()->usertype == 1){
    		$validator = Validator::make(Request::all(), [
	    		'date'						=>	'required',
			    'employee_id'				=>	'required',
			    'loan_id'					=>	'required',
			    'amount'					=>	'required|numeric|between:0,9999999.99',
			    'deducted_amount'			=>	'required|numeric|between:0,9999999.99',
			    'date_started'				=>	'required',
			    'date_ended'				=>	'required',
			],
			[
				'date.required'     		=>	'Date Required',
			    'employee_id.required'     	=>	'Please Select Employee',
			    'loan_id.required'			=>	'Please Select Loan Type',
			    'amount.required'     		=>	'Loan Amount Required',
			    'deducted_amount.required'  =>	'Deducted Amount Required',
			    'date_started.required'     =>	'Deduction Date Started Required',
			    'date_ended.required'     	=>	'Deduction Date End Required',
			]);
    	}else{
    		$validator = Validator::make(Request::all(), [
	    		'date'						=>	'required',
			    //'employee_id'				=>	'required',
			    'loan_id'					=>	'required',
			    'amount'					=>	'required|numeric|between:0,9999999.99',
			    'deducted_amount'			=>	'required|numeric|between:0,9999999.99',
			    'date_started'				=>	'required',
			    'date_ended'				=>	'required',
			],
			[
				'date.required'     		=>	'Date Required',
			    //'employee_id.required'     	=>	'Please Select Employee',
			    'loan_id.required'			=>	'Please Select Loan Type',
			    'amount.required'     		=>	'Loan Amount Required',
			    'deducted_amount.required'  =>	'Deducted Amount Required',
			    'date_started.required'     =>	'Deduction Date Started Required',
			    'date_ended.required'     	=>	'Deduction Date End Required',
			]);
    	}

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		if(Auth::user()->usertype == 1){
			$employee_id = Request::get('employee_id');
		}else{
			$employee_id = Auth::user()->employee_id;
		}

		$employee_loan->update([
			'date'						=>	Request::get('date'),
		    'employee_id'				=>	$employee_id,
		    'loan_id'					=>	Request::get('loan_id'),
		    'amount'					=>	Request::get('amount'),
		    'deducted_amount'			=>	Request::get('deducted_amount'),
		    'date_started'				=>	Request::get('date_started'),
		    'date_ended'				=>	Request::get('date_ended'),			
		    'remarks'					=>	Request::get('remarks'),	
		]);
		
		toastr()->success('Employee Loan Updated Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function delete($id){
    	$employee_loan = \App\Employee_loan::find($id);
    	$employee_loan->delete();
    	toastr()->success('Employee Loan Deleted Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function approve($id){
    	$employee_loan = \App\Employee_loan::find($id);
    	$employee_loan->update([
    		'approved'	=>	1,
    	]);

    	toastr()->success('Employee Loan Approved Successfully', config('global.system_name'));
    	return redirect()->back();
    }

}
