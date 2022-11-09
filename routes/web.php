<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/','LoginController@index')->name('login');
Route::post('/login','LoginController@store')->name('pasok');
Route::get('/logout','LoginController@logout')->name('gawas');

Route::get('/change-password/{id}','ChangepasswordController@change_password');
Route::post('/change-password/{id}','ChangepasswordController@post_change_password');


// EMPLOYEE DASHBOARD

Route::get('/dashboard','DashboardController@index');
Route::get('/dashboard/{id}','DashboardController@profile')->name('dashboard');
Route::get('/dashboard/dtr-entries/{id}','DashboardController@view_dtr');


Route::get('/adjustment/delete/{id}','AdjustmentController@delete');
Route::resource('adjustments','AdjustmentController');

Route::resource('allowances','AllowanceController');

Route::get('/employee/other-info/{id}','EmployeeController@other_info');
Route::get('/employee/leave-credits/{id}','EmployeeController@leave_credits');
Route::get('/employee/delete-leave-credits/{id}','EmployeeController@delete_leave_credits');
Route::get('/employee/delete-dependent/{id}','EmployeeController@delete_dependent');
Route::get('/employee/delete-education/{id}','EmployeeController@delete_education');
Route::get('/employee/delete-work/{id}','EmployeeController@delete_work');
Route::get('/employee/delete-seminar/{id}','EmployeeController@delete_seminar');

Route::post('/employee/add-dependent/{id}','EmployeeController@store_dependent');
Route::patch('/employee/update-dependent/{id}','EmployeeController@update_dependent');
Route::post('/employee/add-education/{id}','EmployeeController@store_education');
Route::patch('/employee/update-education/{id}','EmployeeController@update_education');
Route::post('/employee/add-work/{id}','EmployeeController@store_work');
Route::patch('/employee/update-work/{id}','EmployeeController@update_work');
Route::post('/employee/add-seminar/{id}','EmployeeController@store_seminar');
Route::patch('/employee/update-seminar/{id}','EmployeeController@update_seminar');
Route::post('/employee/store-leave-credits/{id}','EmployeeController@store_leave_credits');
Route::post('/employee/update-leave-credits/{id}','EmployeeController@update_leave_credits');
Route::resource('employees','EmployeeController');

Route::get('bank/delete/{id}','BankController@delete');
Route::resource('banks','BankController');
Route::get('campus/delete/{id}','CampusController@delete');
Route::resource('campuses','CampusController');
Route::get('deduction/delete/{id}','DeductionController@delete');
Route::resource('deductions','DeductionController');

Route::get('/employee-allowance/delete/{id}','EmployeeallowanceController@delete');
Route::resource('employee-allowances','EmployeeallowanceController');

Route::get('/employee-leave/delete/{id}','EmployeeleaveController@delete');
Route::get('/employee-leave/approve/{id}','EmployeeleaveController@approve');
Route::get('/employee-leave/delete-leave-date/{id}','EmployeeleaveController@delete_leave_date');
Route::resource('employee-leaves','EmployeeleaveController');

Route::get('/employee-leave-credit/delete/{id}','EmployeeleavecreditController@delete');
Route::resource('employee-leave-credits','EmployeeleavecreditController');

Route::get('/employee-loan/delete/{id}','EmployeeloanController@delete');
Route::get('/employee-loan/approve/{id}','EmployeeloanController@approve');
Route::resource('employee-loans','EmployeeloanController');

Route::get('/employee-offense/delete/{id}','EmployeeoffenseController@delete');
Route::resource('employee-offenses','EmployeeoffenseController');

Route::get('/employee-overtime/delete/{id}','EmployeeovertimeController@delete');
Route::get('/employee-overtime/approve/{id}','EmployeeovertimeController@approve');
Route::resource('employee-overtimes','EmployeeovertimeController');

Route::get('/holiday/delete/{id}','HolidayController@delete');
Route::resource('holidays','HolidayController');

Route::get('leave/delete/{id}','LeaveController@delete');
Route::resource('leaves','LeaveController');
Route::resource('loans','LoanController');

Route::resource('requirements','RequirementController');
Route::get('sanction/delete/{id}','SanctionController@delete');
Route::resource('sanctions','SanctionController');

Route::get('/schedule-deduction/delete/{id}','ScheduledeductionController@delete');
Route::resource('schedule-deductions','ScheduledeductionController');

Route::get('/setup/delete/{id}','SetupController@delete');
Route::resource('setups','SetupController');

Route::get('violation/delete/{id}','ViolationController@delete');
Route::resource('violations','ViolationController');

Route::resource('/biologs','EmployeebiologController');
Route::post('/biologs/import/file','EmployeebiologController@import');
