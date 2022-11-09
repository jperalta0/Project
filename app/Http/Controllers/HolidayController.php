<?php

namespace App\Http\Controllers;

use App\Holiday;

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

class HolidayController extends Controller
{
	public function __construct(){
	    $this->middleware('auth');
	}
	
    public function index(){
    	if (Request::ajax()) {
	        $data = Holiday::latest()->get();
	        return Datatables::of($data)
	        ->editColumn('date', function($data){
	        	return Carbon::parse($data->date)->toFormattedDateString();
	        })
            ->addColumn('action', function($data){
				$btn = '<center>
            		<a href="#edit'.$data->id.'" class="btn btn-primary btn-sm" data-toggle="modal"><i class="fa fa-edit"></i></a>
            		<a href="#delete'.$data->id.'" class="btn btn-danger btn-sm" data-toggle="modal"><i class="fa fa-trash"></i></a>
            	</center>';
				return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
	    }
	    $holidays = Holiday::orderBy('name')->get();
    	return view('admin.holidays.index',compact('holidays'));
    }

    public function store(){
    	$validator = Validator::make(Request::all(), [
    		'date'						=>	'required',
		    'name'						=>	'required|unique:holidays',
		    'holiday_type'				=>	'required',
		],
		[
			'date.required'     		=>	'Holiday Date Required',
		    'name.required'     		=>	'Holiday Name Required',
		    'holiday_type.required'		=>	'Holiday Type Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		Holiday::create([
			'name'					=>		Request::get('name'),
			'date'					=>		Request::get('date'),
			'holiday_type'			=>		Request::get('holiday_type'),
		]);

		toastr()->success('Holiday Created Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function update($id){
		$holiday = Holiday::find($id);
		$validator = Validator::make(Request::all(), [
			'date'						=>	'required',
		    'name'						=>	"required|unique:holidays,name,$holiday->id,id",
		    'holiday_type'				=>	'required',
		],
		[
		    'date.required'     		=>	'Holiday Date Required',
		    'name.required'     		=>	'Holiday Name Required',
		    'holiday_type.required'		=>	'Holiday Type Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		$holiday->update([
			'name'					=>		Request::get('name'),
			'date'					=>		Request::get('date'),
			'holiday_type'			=>		Request::get('holiday_type'),
		]);

		toastr()->success('Holiday Updated Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function delete($id){
		$holiday = Holiday::find($id);
		$holiday->delete();
		toastr()->success('Holiday Deleted Successfully', config('global.system_name'));
    	return redirect()->back();
	}
}
