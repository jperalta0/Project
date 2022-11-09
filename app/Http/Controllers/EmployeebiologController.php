<?php

namespace App\Http\Controllers;
use App\Imports\BiologsImport;
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

class EmployeebiologController extends Controller
{
    public function __construct(){
	    $this->middleware('auth');
	}

    public function index(){
    	if (Request::ajax()) {
	        $data = \App\Employee_biolog::orderBy('date','DESC')->get();
	        return Datatables::of($data)
	        ->addColumn('biodate', function ($data) {
                $biodate = Carbon::parse($data->date)->toFormattedDateString();
                return $biodate;
            })
    
            ->addColumn('biotime', function ($data) {
                $biotime = Carbon::parse($data->date)->toTimeString();
                return $biotime;
            })
    
            ->addColumn('employee', function ($data) {
                $employee = $data->employee->FullName;
                return $employee;
            })

            ->addColumn('action', function($data){
				$btn = '<center>
            		<a href="#" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
            		<a href="#" class="btn btn-success btn-sm"><i class="fa fa-list"></i></a>
            	</center>';
				return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
	    }
    	return view('admin.biologs.index');
    }

    public function store(){
        $validator = Validator::make(Request::all(), [
		    'file'						=>	'required|file|mimes:xlsx,xls,csv',
		],
		[
		    'file.required'     		=>	'Please upload a valid file',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

        Excel::import(new BiologsImport,Request::file('file'));
        
        toastr()->success('Employee Biolog Uploaded Successfully', config('global.system_name'));
    	return redirect()->back();
    }
}
