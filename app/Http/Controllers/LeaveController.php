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

class LeaveController extends Controller
{
	public function __construct(){
	    $this->middleware('auth');
	}
	
    public function index(){
    	if (Request::ajax()) {
	        $data = \App\Leave::latest()->get();
	        return Datatables::of($data)
            ->addColumn('action', function($data){
				$btn = '<center>
            		<a href="#edit'.$data->id.'" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit"></i></a>
            		<a href="'.action('LeaveController@delete',$data->id).'" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
            	</center>';
				return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
	    }
	    $leaves = \App\Leave::orderBy('name')->get();
    	return view('admin.leaves.index',compact('leaves'));
    }

    public function store(){
    	$validator = Validator::make(Request::all(), [
		    'name'						=>	'required|unique:leaves',
		],
		[
		    'name.required'     		=>	'Leave Name Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		\App\Leave::create(Request::all());
		toastr()->success('Leave Created Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function update($id){
    	$leave = \App\Leave::find($id);
		$validator = Validator::make(Request::all(), [
		    'name'						=>	"required|unique:leaves,name,$leave->id,id",
		],
		[
		    'name.required'     		=>	'Leave Name Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		$leave->update(Request::all());

		toastr()->success('Leave Updated Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function delete($id){
    	$leave = \App\Leave::find($id);
    	$check = \App\Employee_leave::where('leave_id',$leave->id)->count();
    	if($check > 0){
    		toastr()->error('Leave Cannot be Deleted', config('global.system_name'));
    		return redirect()->back();
    	}else{
    		$leave->delete();
    		toastr()->success('Leave Deleted Successfully', config('global.system_name'));
    		return redirect()->back();
    	}
    }
}
