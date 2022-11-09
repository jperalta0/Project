<?php

namespace App\Http\Controllers;

use App\Area;

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

class AreaController extends Controller
{
    /*public function __construct(){
	    $this->middleware('auth');
	}*/
	
    public function index(){
    	if (Request::ajax()) {
	        $data = Area::latest()->get();
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
	    $areas = Area::orderBy('name')->get();
    	return view('admin.areas.index',compact('areas'));
    }

    public function store(){
    	$validator = Validator::make(Request::all(), [
		    'name'						=>	'required|unique:areas',
		],
		[
		    'name.required'     		=>	'Area Name Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		Area::create([
			'name'			=>		Request::get('name'),
		]);

		toastr()->success('Area Created Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function update($id){
		$area = Area::find($id);
		$validator = Validator::make(Request::all(), [
		    'name'						=>	"required|unique:areas,name,$area->id,id",
		],
		[
		    'name.required'     		=>	'Area Name Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		$area->update([
			'name'			=>		Request::get('name'),
		]);

		toastr()->success('Area Updated Successfully', config('global.system_name'));
    	return redirect()->back();
    }
}
