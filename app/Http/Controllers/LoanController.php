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

class LoanController extends Controller
{
	public function __construct(){
	    $this->middleware('auth');
	}
	
    public function index(){
    	if (Request::ajax()) {
	        $data = \App\Loan::latest()->get();
	        return Datatables::of($data)
            ->addColumn('action', function($data){
				$btn = '<center>
            		<a href="#edit'.$data->id.'" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit"></i></a>
            	</center>';
				return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
	    }
	    $loans = \App\Loan::orderBy('name')->get();
    	return view('admin.loans.index',compact('loans'));
    }

    public function store(){
    	$validator = Validator::make(Request::all(), [
		    'name'						=>	'required|unique:loans',
		],
		[
		    'name.required'     		=>	'Loan Name Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		\App\Loan::create(Request::all());
		toastr()->success('Loan Created Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function update($id){
    	$loan = \App\Loan::find($id);
		$validator = Validator::make(Request::all(), [
		    'name'						=>	"required|unique:leaves,name,$loan->id,id",
		],
		[
		    'name.required'     		=>	'Loan Name Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		$loan->update(Request::all());

		toastr()->success('Loan Updated Successfully', config('global.system_name'));
    	return redirect()->back();
    }
}
