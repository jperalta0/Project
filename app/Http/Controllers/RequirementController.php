<?php

namespace App\Http\Controllers;

use App\Requirement;

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

class RequirementController extends Controller
{
	public function __construct(){
	    $this->middleware('auth');
	}
	
    public function index(){
    	if (Request::ajax()) {
	        $data = Requirement::latest()->get();
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
	    $requirements = Requirement::orderBy('name')->get();
    	return view('admin.requirements.index',compact('requirements'));
    }

    public function store(){
    	$validator = Validator::make(Request::all(), [
		    'name'						=>	'required|unique:requirements',
		],
		[
		    'name.required'     		=>	'Requirement Name Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		Requirement::create([
			'name'			=>		Request::get('name'),
		]);

		toastr()->success('Requirement Created Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function update($id){
		$requirement = Requirement::find($id);
		$validator = Validator::make(Request::all(), [
		    'name'						=>	"required|unique:requirements,name,$requirement->id,id",
		],
		[
		    'name.required'     		=>	'Requirement Name Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		$requirement->update([
			'name'			=>		Request::get('name'),
		]);

		toastr()->success('Requirement Updated Successfully', config('global.system_name'));
    	return redirect()->back();
    }
}
