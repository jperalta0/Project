<?php

namespace App\Http\Controllers;

use App\Violation;

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

class ViolationController extends Controller
{
	public function __construct(){
	    $this->middleware('auth');
	}
	
    public function index(){
    	if (Request::ajax()) {
	        $data = Violation::latest()->get();
	        return Datatables::of($data)
            ->addColumn('action', function($data){
				$btn = '<center>
            		<a href="#edit'.$data->id.'" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit"></i></a>
            		<a href="'.action('ViolationController@delete',$data->id).'" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
            	</center>';
				return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
	    }
	    $violations = Violation::orderBy('name')->get();
    	return view('admin.violations.index',compact('violations'));
    }

    public function store(){
    	$validator = Validator::make(Request::all(), [
		    'name'						=>	'required|unique:violations',
		],
		[
		    'name.required'     		=>	'Violation Name Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		Violation::create([
			'name'			=>		Request::get('name'),
		]);

		toastr()->success('Violation Created Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function update($id){
		$violation = Violation::find($id);
		$validator = Validator::make(Request::all(), [
		    'name'						=>	"required|unique:violations,name,$violation->id,id",
		],
		[
		    'name.required'     		=>	'Violation Name Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		$violation->update([
			'name'			=>		Request::get('name'),
		]);

		toastr()->success('Violation Updated Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function delete($id){
    	$violation = \App\Violation::find($id);
    	$check = \App\Employee_offense::where('violation_id',$violation->id)->count();
    	if($check > 0){
    		toastr()->error('Violation Cannot be Deleted', config('global.system_name'));
    		return redirect()->back();
    	}else{
    		$violation->delete();
    		toastr()->success('Violation Deleted Successfully', config('global.system_name'));
    		return redirect()->back();
    	}
    }
}
