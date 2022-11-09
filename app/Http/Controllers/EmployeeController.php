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

class EmployeeController extends Controller
{
	public function __construct(){
	    $this->middleware('auth');
	}

    public function index(){
    	if (Request::ajax()) {

    		if(Auth::user()->usertype == 1){
    			$data = \App\Employee::orderBy('lastname')->get();
    		}else{
    			$data = \App\Employee::where('id',Auth::user()->employee_id)->orderBy('lastname')->get();
    		}

	        
	        return Datatables::of($data)
	        ->addColumn('name', function($data){
	        	return $data->FullName;
	        })

	        ->addColumn('campus', function($data){
	        	return $data->geninfo->campus->name;
	        })

            ->addColumn('action', function($data){
				$btn = '<center>
            		<a href="'.action('EmployeeController@edit',$data->id).'" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
            		<a href="'.action('EmployeeController@other_info',$data->id).'" class="btn btn-success btn-sm"><i class="fa fa-list"></i></a>
            		<a href="'.action('EmployeeController@leave_credits',$data->id).'" class="btn btn-info btn-sm"><i class="fa fa-plane"></i></a>
            	</center>';
				return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
	    }
    	return view('admin.employees.index');
    }

    public function create(){
    	$campuses = \App\Campus::pluck('name','id');
    	$banks = \App\Bank::pluck('name','id');
    	$terms = \App\Payroll_term::pluck('name','id');
    	$status = \App\Status::pluck('name','id');
    	$weekdays = \App\Weekday::pluck('name','id');
    	return view('admin.employees.create',compact('campuses','banks','terms','status','weekdays'));
    }

    public function store(){
    	$validator = Validator::make(Request::all(), [
		    'employee_number'			=>	'required|unique:employees',
		    'position'					=>	'required',
		    'lastname'					=>	'required',
		    'firstname'					=>	'required',
		    'address'					=>	'required',
		    'email'						=>	'required|email|unique:employees',
		    'birthdate'					=>	'required|date',
			'civilstatus'				=>	'required',
			'isFulltime'				=>	'required',
		    'gender'					=>	'required',
		    'datehired'					=>	'required|date',
		    'campus_id'					=>	'required',
		    'term_id'					=>	'required',
		    'status_id'					=>	'required',
		    'time_in'					=>	'required',
		    'time_out'					=>	'required',
		    'restday_id'				=>	'required|array|min:1',
		    'salaryrate'				=>	'required|numeric',
		    'bank_id'					=>	'required',
		    'accountnumber'				=>	'required',
		],
		[
		    'employee_number.required'     	=>	'Employee Number Required',
		    'position.required'     	=>	'Employee Position Required',
		    'lastname.required'     	=>	'Employee Last Name Required',
		    'firstname.required'     	=>	'Employee First Name Required',
		    'address.required'     		=>	'Employee Address Required',
		    'email.required'     		=>	'Employee Email Required',
		    'birthdate.required'     	=>	'Employee Birthdate Required',
			'civilstatus.required'     	=>	'Employee Civil Status Required',
			'isFulltime.required'     	=>	'Employee Status Required',
		    'gender.required'     		=>	'Employee Gender Required',
		    'datehired.required'     	=>	'Employee Date Hired Required',
		    'campus_id.required'     	=>	'Employee Campus Required',
		    'term_id.required'     		=>	'Employee Term Required',
		    'status_id.required'     	=>	'Employee Status Required',
		    'time_in.required'     		=>	'Employee Time In Required',
		    'time_out.required'     	=>	'Employee Time Out Required',
		    'restday_id.required'     	=>	'Employee Restday Required',
		    'salaryrate.required'     	=>	'Employee Salary Rate Required',
		    'bank_id.required'     		=>	'Employee Bank Required',
		    'accountnumber.required'	=>	'Employee Account Number Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		//$file = Request::file('pic');

		$file = Request::file('pic');

		if($file !== NULL){
            $logo = file_get_contents($file);
            $base64 = base64_encode($logo);
        	$fileName = $base64;

        }else{
        	$fileName = NULL;
        }


        /*if($file !== NULL){
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::random(40).'.'.$file->extension();
            $file->move(public_path().'/images',$fileName);
        }else{
            if(Request::get('gender') == 1){
            	$fileName = 'male.png';
            }else{
            	$fileName = 'female.png';
            }
		}*/

		$employee_id = \App\Employee::create([
			'employee_number'		=>		Request::get('employee_number'),
			'position'				=>		Request::get('position'),
			'firstname'				=>		Request::get('firstname'),
			'middlename'			=>		Request::get('middlename'),
			'lastname'				=>		Request::get('lastname'),
			'extname'				=>		Request::get('extname'),
			'address'				=>		Request::get('address'),
			'email'					=>		Request::get('email'),
			'birthdate'				=>		Request::get('birthdate'),
			'civilstatus'			=>		Request::get('civilstatus'),
			'gender'				=>		Request::get('gender'),
			'isActive'				=>		1,
			'isFulltime'			=>		Request::get('isFulltime'),
			'img'					=>		$fileName,
		])->id;

		\App\Employee_general_info::create([
			'employee_id'		=>		$employee_id,
			'datehired'			=>		Request::get('datehired'),
			'campus_id'			=>		Request::get('campus_id'),
			'dateseparated'		=>		Request::get('dateseparated'),
			'term_id'			=>		Request::get('term_id'),
			'status_id'			=>		Request::get('status_id'),
		]);

		$converted_timein = date("H:i", strtotime(Request::get('time_in')));
		$converted_timeout = date("H:i", strtotime(Request::get('time_out')));

		\App\Employee_schedule::create([
			'employee_id'		=>		$employee_id,
			'time_in'			=>		$converted_timein,
			'time_out'			=>		$converted_timeout,
		]);

		foreach(Request::get('restday_id') as $key => $value){
			\App\Employee_restday::create([
				'employee_id'		=>		$employee_id,
				'restday_id'		=>		$value,
			]);
		}

		\App\Employee_salary_info::create([
			'employee_id'		=>		$employee_id,
			'salaryrate'		=>		Request::get('salaryrate'),
			'bank_id'			=>		Request::get('bank_id'),
			'accountnumber'		=>		Request::get('accountnumber'),
			'sss_number'		=>		Request::get('sss_number'),
			'hdmf_number'		=>		Request::get('hdmf_number'),
			'phic_number'		=>		Request::get('phic_number'),
			'tin_number'		=>		Request::get('tin_number'),
		]);

		\App\User::create([
			'employee_id'		=>		$employee_id,
			'name'				=>		Request::get('email'),
			'email'				=>		Request::get('email'),
			'password'			=>		\Hash::make(preg_replace('/\s+/', '',strtolower(Request::get('lastname')))),
			'usertype'			=>		2,
		]);
		
		toastr()->success('Employee Created Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function edit($id){
    	$employee = \App\Employee::find($id);
    	$campuses = \App\Campus::pluck('name','id');
    	$banks = \App\Bank::pluck('name','id');
    	$terms = \App\Payroll_term::pluck('name','id');
    	$status = \App\Status::pluck('name','id');
    	$weekdays = \App\Weekday::pluck('name','id');
    	return view('admin.employees.create',compact('employee','campuses','banks','terms','status','weekdays'));
    }

    public function update($id){
    	$employee = \App\Employee::find($id);
    	$validator = Validator::make(Request::all(), [
		    'employee_number'			=>	"required|unique:employees,employee_number,$employee->id,id",
		    'position'					=>	'required',
		    'lastname'					=>	'required',
		    'firstname'					=>	'required',
		    'address'					=>	'required',
		    'email'						=>	"required|email|unique:employees,email,$employee->id,id",
		    'birthdate'					=>	'required|date',
			'civilstatus'				=>	'required',
			'isFulltime'				=>	'required',
		    'gender'					=>	'required',
		    'datehired'					=>	'required|date',
		    'campus_id'					=>	'required',
		    'term_id'					=>	'required',
		    'status_id'					=>	'required',
		    'time_in'					=>	'required',
		    'time_out'					=>	'required',
		    'restday_id'				=>	'required|array|min:1',
		    'salaryrate'				=>	'required|numeric',
		    'bank_id'					=>	'required',
		    'accountnumber'				=>	'required',
		],
		[
		    'employee_number.required'     	=>	'Employee Number Required',
		    'position.required'     	=>	'Employee Position Required',
		    'lastname.required'     	=>	'Employee Last Name Required',
		    'firstname.required'     	=>	'Employee First Name Required',
		    'address.required'     		=>	'Employee Address Required',
		    'email.required'     		=>	'Employee Email Required',
		    'birthdate.required'     	=>	'Employee Birthdate Required',
			'civilstatus.required'     	=>	'Employee Civil Status Required',
			'isFulltime.required'     	=>	'Employee Status Required',
		    'gender.required'     		=>	'Employee Gender Required',
		    'datehired.required'     	=>	'Employee Date Hired Required',
		    'campus_id.required'     	=>	'Employee Campus Required',
		    'term_id.required'     		=>	'Employee Term Required',
		    'status_id.required'     	=>	'Employee Status Required',
		    'time_in.required'     		=>	'Employee Time In Required',
		    'time_out.required'     	=>	'Employee Time Out Required',
		    'restday_id.required'     	=>	'Employee Restday Required',
		    'salaryrate.required'     	=>	'Employee Salary Rate Required',
		    'bank_id.required'     		=>	'Employee Bank Required',
		    'accountnumber.required'	=>	'Employee Account Number Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		/*$file = Request::file('pic');
        if($file !== NULL){
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::random(40).'.'.$file->extension();
            $file->move(public_path().'/images',$fileName);
        }else{
            $fileName = $employee->pic;   
        }*/

        $file = Request::file('pic');

		if($file !== NULL){
            $logo = file_get_contents($file);
            $base64 = base64_encode($logo);
        	$fileName = $base64;

        }else{
        	$fileName = $employee->img;;
        }

        if(Request::get('isActive') == 1){
        	$isActive = 1;
        }else{
        	$isActive = 0;
		}
		
		if(Request::get('isFulltime') == 1){
			$ft = 1;
		}else{
			$ft = 0;
		}

        $employee->update([
			'employee_number'		=>		Request::get('employee_number'),
			'position'				=>		Request::get('position'),
			'firstname'				=>		Request::get('firstname'),
			'middlename'			=>		Request::get('middlename'),
			'lastname'				=>		Request::get('lastname'),
			'extname'				=>		Request::get('extname'),
			'address'				=>		Request::get('address'),
			'email'					=>		Request::get('email'),
			'birthdate'				=>		Request::get('birthdate'),
			'civilstatus'			=>		Request::get('civilstatus'),
			'gender'				=>		Request::get('gender'),
			'isActive'				=>		$isActive,
			'isFulltime'			=>		$ft,
			'img'					=>		$fileName,
		]);

		$employee->geninfo->update([
			'datehired'			=>		Request::get('datehired'),
			'campus_id'			=>		Request::get('campus_id'),
			'dateseparated'		=>		Request::get('dateseparated'),
			'term_id'			=>		Request::get('term_id'),
			'status_id'			=>		Request::get('status_id'),
		]);

		$converted_timein = date("H:i", strtotime(Request::get('time_in')));
		$converted_timeout = date("H:i", strtotime(Request::get('time_out')));

		$employee->schedule->update([
			'time_in'			=>		$converted_timein,
			'time_out'			=>		$converted_timeout,
		]);

		$employee->restdays()->delete();

		foreach(Request::get('restday_id') as $key => $value){
			\App\Employee_restday::create([
				'employee_id'		=>		$employee->id,
				'restday_id'		=>		$value,
			]);
		}

		$employee->salinfo->update([
			'salaryrate'		=>		Request::get('salaryrate'),
			'bank_id'			=>		Request::get('bank_id'),
			'accountnumber'		=>		Request::get('accountnumber'),
			'sss_number'		=>		Request::get('sss_number'),
			'hdmf_number'		=>		Request::get('hdmf_number'),
			'phic_number'		=>		Request::get('phic_number'),
			'tin_number'		=>		Request::get('tin_number'),
		]);
		
		toastr()->success('Employee Updated Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function leave_credits($id){
    	$employee = \App\Employee::find($id);
    	$leaves = \App\Leave::pluck('name','id');
    	return view('admin.employees.leave-credits',compact('employee','leaves'));
    }

    public function store_leave_credits($id){
    	$employee = \App\Employee::find($id);
    	$validator = Validator::make(Request::all(), [
		    'leave_id'					=>	'required|unique:employee_leave_credits',
		    'credit'					=>	'required|numeric',
		],
		[
		    'leave_id.required'     	=>	'Please Select Leave Type',
		    'credit.required'     		=>	'Credits Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		\App\Employee_leave_credit::create(Request::all());
		toastr()->success('Employee Leave Credit Created Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function update_leave_credits($id){
    	$credit = \App\Employee_leave_credit::find($id);
    	$validator = Validator::make(Request::all(), [
		    'leave_id'					=>	"required|unique:employee_leave_credits,leave_id,$credit->id,id",
		    'credit'					=>	'required|numeric',
		],
		[
		    'leave_id.required'     	=>	'Please Select Leave Type',
		    'credit.required'     		=>	'Credits Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		$credit->update(Request::all());
		toastr()->success('Employee Leave Credit Updated Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function delete_leave_credits($id){
    	$credit = \App\Employee_leave_credit::find($id);
    	$credit->delete();
    	toastr()->success('Employee Leave Credit Deleted Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function other_info($id){
    	$employee = \App\Employee::find($id);
    	$status = \App\Status::pluck('name','id');
    	return view('admin.employees.other-info',compact('employee','status'));
    }

    public function store_dependent($id){
    	$employee = \App\Employee::find($id);
    	$validator = Validator::make(Request::all(), [
		    'name'						=>	'required',
		    'relationship'				=>	'required',
		    'birthdate'					=>	'required|date_format:Y-m-d',
		],
		[
		    'name.required'     		=>	'Dependent Name Required',
		    'relationship.required'     =>	'Relationship Required',
		    'birthdate.required'     	=>	'Birthdate Required',
		    'birthdate.date_format'     =>	'Use the given format: yyyy-mm-dd',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

    	\App\Employee_dependent::create([
    		'employee_id'		=>		$employee->id,
    		'name'				=>		Request::get('name'),
    		'relationship'		=>		Request::get('relationship'),
    		'birthdate'			=>		Request::get('birthdate'),
    	]);

    	toastr()->success('Employee Dependent Added Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function update_dependent($id){
    	$dependent = \App\Employee_dependent::find($id);
    	$validator = Validator::make(Request::all(), [
		    'name'						=>	'required',
		    'relationship'				=>	'required',
		    'birthdate'					=>	'required|date_format:Y-m-d',
		],
		[
		    'name.required'     		=>	'Dependent Name Required',
		    'relationship.required'     =>	'Relationship Required',
		    'birthdate.required'     	=>	'Birthdate Required',
		    'birthdate.date_format'     =>	'Use the given format: yyyy-mm-dd',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		$dependent->update([
    		'name'				=>		Request::get('name'),
    		'relationship'		=>		Request::get('relationship'),
    		'birthdate'			=>		Request::get('birthdate'),
    	]);

    	toastr()->success('Employee Dependent Updated Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function delete_dependent($id){
    	$dependent = \App\Employee_dependent::find($id);
    	$dependent->delete();

    	toastr()->success('Employee Dependent Deleted Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    /*EDUCATION*/
    public function store_education($id){
    	$employee = \App\Employee::find($id);
    	$validator = Validator::make(Request::all(), [
		    'name'						=>	'required',
		    'level'						=>	'required',
		    'year'						=>	'required',
		],
		[
		    'name.required'     		=>	'School Name Required',
		    'level.required'     		=>	'Level Required',
		    'year.required'     		=>	'Year Graduated Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

    	\App\Employee_education::create([
    		'employee_id'		=>		$employee->id,
    		'level'				=>		Request::get('level'),
    		'name'				=>		Request::get('name'),
    		'course'			=>		Request::get('course'),
    		'year'				=>		Request::get('year'),
    	]);

    	toastr()->success('Employee Education Added Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function update_education($id){
    	$education = \App\Employee_education::find($id);
    	$validator = Validator::make(Request::all(), [
		    'name'						=>	'required',
		    'level'						=>	'required',
		    'year'						=>	'required',
		],
		[
		    'name.required'     		=>	'School Name Required',
		    'level.required'     		=>	'Level Required',
		    'year.required'     		=>	'Year Graduated Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		$education->update([
    		'level'				=>		Request::get('level'),
    		'name'				=>		Request::get('name'),
    		'course'			=>		Request::get('course'),
    		'year'				=>		Request::get('year'),
    	]);

    	toastr()->success('Employee Education Updated Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function delete_education($id){
    	$education = \App\Employee_education::find($id);
    	$education->delete();

    	toastr()->success('Employee Education Deleted Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    /*WORK*/
    public function store_work($id){
    	$employee = \App\Employee::find($id);
    	$validator = Validator::make(Request::all(), [
		    'position'					=>	'required',
		    'status_id'					=>	'required',
		    'name'						=>	'required',
		    'year'						=>	'required',
		],
		[
		    'position.required'			=>	'Position Required',
		    'status_id.required'     	=>	'Please Select Status',
		    'name.required'     		=>	'Company Name Required',
		    'year.required'     		=>	'Inclusive Year Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

    	\App\Employee_work::create([
    		'employee_id'		=>		$employee->id,
    		'position'			=>		Request::get('position'),
    		'name'				=>		Request::get('name'),
    		'status_id'			=>		Request::get('status_id'),
    		'year'				=>		Request::get('year'),
    	]);

    	toastr()->success('Employee Employment History Added Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function update_work($id){
    	$work = \App\Employee_work::find($id);
    	$validator = Validator::make(Request::all(), [
		    'position'					=>	'required',
		    'status_id'					=>	'required',
		    'name'						=>	'required',
		    'year'						=>	'required',
		],
		[
		    'position.required'			=>	'Position Required',
		    'status_id.required'     	=>	'Please Select Status',
		    'name.required'     		=>	'Company Name Required',
		    'year.required'     		=>	'Inclusive Year Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		$work->update([
    		'position'			=>		Request::get('position'),
    		'name'				=>		Request::get('name'),
    		'status_id'			=>		Request::get('status_id'),
    		'year'				=>		Request::get('year'),
    	]);

    	toastr()->success('Employee Employment History Updated Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function delete_work($id){
    	$work = \App\Employee_work::find($id);
    	$work->delete();

    	toastr()->success('Employee Employment History Deleted Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    /*SEMINAR*/
    public function store_seminar($id){
    	$employee = \App\Employee::find($id);
    	$validator = Validator::make(Request::all(), [
		    'date'						=>	'required|date_format:Y-m-d',
		    'name'						=>	'required',
		    'venue'						=>	'required',
		],
		[
		    'date.required'				=>	'Seminar Date Required',
		    'name.required'     		=>	'Seminar Title Required',
		    'venue.required'     		=>	'Venue Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

    	\App\Employee_seminar::create([
    		'employee_id'		=>		$employee->id,
    		'date'				=>		Request::get('date'),
    		'name'				=>		Request::get('name'),
    		'venue'				=>		Request::get('venue'),
    	]);

    	toastr()->success('Employee Seminar Added Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function update_seminar($id){
    	$seminar = \App\Employee_seminar::find($id);
    	$validator = Validator::make(Request::all(), [
		    'date'						=>	'required|date_format:Y-m-d',
		    'name'						=>	'required',
		    'venue'						=>	'required',
		],
		[
		    'date.required'				=>	'Seminar Date Required',
		    'name.required'     		=>	'Seminar Title Required',
		    'venue.required'     		=>	'Venue Required',
		]);

		if ($validator->fails()) {
            foreach($validator->errors()->all() as $error) {
	            toastr()->warning($error);
	        }
	        return redirect()->back()
	       	->withInput();
		}

		$seminar->update([
    		'date'				=>		Request::get('date'),
    		'name'				=>		Request::get('name'),
    		'venue'				=>		Request::get('venue'),
    	]);

    	toastr()->success('Employee Seminar Updated Successfully', config('global.system_name'));
    	return redirect()->back();
    }

    public function delete_seminar($id){
    	$seminar = \App\Employee_seminar::find($id);
    	$seminar->delete();

    	toastr()->success('Employee Seminar Deleted Successfully', config('global.system_name'));
    	return redirect()->back();
    }
}
