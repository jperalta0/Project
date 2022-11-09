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


class DashboardController extends Controller
{
    public function __construct(){
	    $this->middleware('auth');
	}

	public function index(){
		$empcount = \App\Employee::count();
		$dtrcount = \App\Employee_login::where('date',Carbon::now()->toDateString())->count();
		$absentcount = $empcount - $dtrcount;
		$vacationcount = \App\Employee_leave::where('approved',1)->whereMonth('date',Carbon::now()->month())->count();
		return view('dashboard',compact('empcount','dtrcount','absentcount','vacationcount'));
	}

	public function profile($id){
		$user = \App\User::find($id);
		return view('dashboard.index',compact('user'));
	}

	public function view_dtr($id){
		if(Auth::user()->usertype == 1){
			$dtr = \App\Employee_login::orderBy('date','DESC')
				->get();
			return view('dashboard.dtr',compact('dtr'));
		}else{
			$user = \App\User::find($id);
			$dtr = \App\Employee_login::where('employee_id',$user->employee_id)
				->orderBy('date','DESC')
				->get();
			return view('dashboard.dtr',compact('user','dtr'));
		}
		

		
	}
}
