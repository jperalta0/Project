<?php

//namespace App\Http\Controllers;

//use App\Campus;

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

class CampusController extends Controller
{
	public function __construct(){
	    $this->middleware('auth');
	}
	
    public function index(){
    	if (Request::ajax()) {
	        $data = Campus::latest()->get();
	        return Datatables::of($data)
            ->addColumn('action', function($data){
				$btn = '<center>
            		<a href="#edit'.$data->id.'" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit"></i></a>
            		<a href="'.action('CampusController@delete',$data->id).'" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
            	</center>';
				return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
	    }
	    $campuses = Campus::orderBy('name')->get();
    	return view('admin.campuses.index',compact('campuses'));
    }

    public function store(){
    	$validator = Validator::make(Request::all(), [
		    'name'						=>	'required|unique:campuses',
		],
		[
		    'name.required'     		=>	'Campus Name Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		Campus::create([
			'name'			=>		Request::get('name'),
		]);

		toastr()->success('Department Created Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function update($id){
		$campus = Campus::find($id);
		$validator = Validator::make(Request::all(), [
		    'name'						=>	"required|unique:campuses,name,$campus->id,id",
		],
		[
		    'name.required'     		=>	'Campus Name Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		$campus->update([
			'name'			=>		Request::get('name'),
		]);

		toastr()->success('department Updated Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function delete($id){
    	$campus = Campus::find($id);
    	$check = \App\Employee_general_info::where('campus_id',$campus->id)->count();
    	if($check > 0){
    		toastr()->error('department Cannot be Deleted', config('global.system_name'));
    		return redirect()->back();
    	}else{
    		$campus->delete();
    		toastr()->success('department Deleted Successfully', config('global.system_name'));
    		return redirect()->back();
    	}
    }
}
