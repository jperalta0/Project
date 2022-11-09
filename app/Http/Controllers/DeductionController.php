<?php

namespace App\Http\Controllers;

use App\Deduction;

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

class DeductionController extends Controller
{
	public function __construct(){
	    $this->middleware('auth');
	}
	
    public function index(){
    	if (Request::ajax()) {
	        $data = Deduction::latest()->get();
	        return Datatables::of($data)
            ->addColumn('action', function($data){
				$btn = '<center>
            		<a href="#edit'.$data->id.'" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit"></i></a>
            		<a href="'.action('DeductionController@delete',$data->id).'" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
            	</center>';
				return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
	    }
	    $deductions = Deduction::orderBy('name')->get();
    	return view('admin.deductions.index',compact('deductions'));
    }

    public function store(){
    	$validator = Validator::make(Request::all(), [
		    'name'						=>	'required|unique:deductions',
		],
		[
		    'name.required'     		=>	'Deduction Name Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		Deduction::create([
			'name'			=>		Request::get('name'),
		]);

		toastr()->success('Deduction Created Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function update($id){
		$deduction = Deduction::find($id);
		$validator = Validator::make(Request::all(), [
		    'name'						=>	"required|unique:deductions,name,$deduction->id,id",
		],
		[
		    'name.required'     		=>	'Deduction Name Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		$deduction->update([
			'name'			=>		Request::get('name'),
		]);

		toastr()->success('Deduction Updated Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function delete($id){
    	$deduction = Deduction::find($id);
		$deduction->delete();
		toastr()->success('Deduction Deleted Successfully', config('global.system_name'));
		return redirect()->back();
    	
    }
}
