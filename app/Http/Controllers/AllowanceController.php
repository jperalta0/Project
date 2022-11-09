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

class AllowanceController extends Controller
{
	public function __construct(){
	    $this->middleware('auth');
	}
	
    public function index(){
    	if (Request::ajax()) {
	        $data = \App\Allowance::latest()->get();
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
	    $allowances = \App\Allowance::orderBy('name')->get();
    	return view('admin.allowances.index',compact('allowances'));
    }

    public function store(){
    	$validator = Validator::make(Request::all(), [
		    'name'						=>	'required|unique:allowances',
		],
		[
		    'name.required'     		=>	'Allowance Name Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		\App\Allowance::create([
			'name'			=>		Request::get('name'),
		]);

		toastr()->success('Allowance Created Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function update($id){
		$allowance = \App\Allowance::find($id);
		$validator = Validator::make(Request::all(), [
		    'name'						=>	"required|unique:allowances,name,$allowance->id,id",
		],
		[
		    'name.required'     		=>	'Allowance Name Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		$allowance->update([
			'name'			=>		Request::get('name'),
		]);

		toastr()->success('Allowance Updated Successfully', config('global.system_name'));
    	return redirect()->back();
    }
}
