@extends('master')

@section('content')
<h2 class="content-heading">Employee Module</h2>
@if(isset($employee))
    {!! Form::model($employee, ['method'=>'PATCH','action'=>['EmployeeController@update',$employee->id],'novalidate' => 'novalidate','files' => 'true']) !!}
@else
{!! Form::open(['method'=>'POST','action'=>'EmployeeController@store','novalidate' => 'novalidate','files' => 'true']) !!}
@endif
@if(isset($employee))
<div class="bg-image bg-image-bottom" style="color:white;">
    <div class="bg-primary-dark-op py-30">
        <div class="content content-full text-center">
            <div class="mb-15">
                <a class="img-link" href="#">
                @if($employee->img != NULL)
                <img class="img-avatar img-avatar96 img-avatar-thumb" src="data:image/jpeg;base64,{{ $employee->img }}">
                @else
                <img class="img-avatar img-avatar96 img-avatar-thumb" src="<?php echo asset('public/images/noimage.png') ?>" style="height: 70px; width: 70px;">
                @endif
                </a>
            </div>
            <h1 class="h3 text-black font-w700 mb-10" style="text-transform: uppercase;">
                {{ $employee->FullName }}
            </h1>
            <p class="h5 text-black">
                {{ $employee->position }}<br>
                {{ $employee->email }}
            </p>
            <p class="h5 text-black-op" style="text-transform: uppercase;">
                {{ $employee->geninfo->campus->name }} Campus
            </p>
        </div>
    </div>
</div>
<div style="margin-bottom: 20px;"></div>
@endif
<div class="row">
    <div class="col-md-12">
        <div class="block block-themed">
            <div class="block-header bg-earth">
                <h3 class="block-title">
                    @if(isset($employee))
                    Update Profile of <span style="text-transform: uppercase; font-weight: bolder;">{{ $employee->FullName }}</span>
                    @else
                    Create New Employee
                    @endif
                </h3>
                <div class="block-options">
                    <a href="{{ action('EmployeeController@index') }}" class="btn btn-sm btn-secondary"><i class="fa fa-home"></i> Back to Index</a>
                </div>
            </div>
            <div class="block-content">
                <ul class="nav nav-tabs nav-tabs-block" data-toggle="tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#btabs-static-home">Personal Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#btabs-static-profile">Employee Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#btabs-static-settings">Salary Profile</a>
                    </li>
                </ul>
                <div class="block-content tab-content">
                    <div class="tab-pane active" id="btabs-static-home" role="tabpanel">
                        <h4 class="font-w400">Personal Profile</h4>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="input-file-now">Upload Image</label>
                                    <input type="file" id="input-file-now" class="" name="pic"/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('Employee Number') !!}
                                    {!! Form::text('employee_number',null,['class'=>'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('Position:') !!}
                                    {!! Form::text('position',null,['class'=>'form-control']) !!}
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('First Name') !!}
                                    {!! Form::text('firstname',null,['class'=>'form-control']) !!}
                                </div>
                            </div>  
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('Middle Name') !!}
                                    {!! Form::text('middlename',null,['class'=>'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('Last Name') !!}
                                    {!! Form::text('lastname',null,['class'=>'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    {!! Form::label('Ext') !!}
                                    {!! Form::text('extname',null,['class'=>'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('Address') !!}
                                    {!! Form::text('address',null,['class'=>'form-control']) !!}
                                </div>
                            </div>  
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('Email Address') !!}
                                    {!! Form::text('email',null,['class'=>'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('Birthdate') !!}
                                    @if(isset($employee))
                                    <input type="text" class="js-flatpickr form-control bg-white" id="example-flatpickr-default" name="birthdate" value="{{ $employee->birthdate }}">
                                    @else
                                    <input type="text" class="js-flatpickr form-control bg-white" id="example-flatpickr-default" name="birthdate" value="">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('Select Civil Status') !!}
                                    {!! Form::select('civilstatus',['SINGLE'=>'SINGLE','MARRIED'=>'MARRIED','WIDOWER'=>'WIDOWER','SEPARATED'=>'SEPARATED'],null,['class'=>'form-control js-select2','placeholder'=>'PLEASE SELECT']) !!}
                                </div>
                            </div>  
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('Select Gender') !!}
                                    {!! Form::select('gender',['1'=>'MALE','2'=>'FEMALE'],null,['class'=>'form-control js-select2','placeholder'=>'PLEASE SELECT']) !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('Select Employee Status') !!}
                                    {!! Form::select('isFulltime',['1'=>'FULL TIME','2'=>'PART TIME'],null,['class'=>'form-control js-select2','placeholder'=>'PLEASE SELECT']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div style="margin-top: 20px;">
                                    @if(isset($employee))
                                        @if($employee->isActive == 1)
                                        <input type="checkbox" id="basic_checkbox_2" class="filled-in" name="isActive" value="1" checked="checked" />
                                        @endif
                                    @else
                                    <input type="checkbox" id="basic_checkbox_2" class="filled-in" name="isActive" value="1" />
                                    @endif                                    
                                    <label for="basic_checkbox_2" style="margin-left: 10px;">Active Employee?</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="btabs-static-profile" role="tabpanel">
                        <h4 class="font-w400">Employee Profile</h4>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('Date Hired:') !!}
                                    @isset($employee)
                                    <input type="text" class="js-flatpickr form-control bg-white" id="example-flatpickr-default" name="datehired" value="{{ $employee->geninfo->datehired }}">
                                    @else
                                    <input type="text" class="js-flatpickr form-control bg-white" id="example-flatpickr-default" name="datehired" placeholder="">
                                    @endif
                                </div>
                            </div>  
                            <div class="col-md-8">
                                <div class="form-group">
                                    {!! Form::label('Select Department') !!}
                                    @isset($employee)
                                    {!! Form::select('campus_id',$campuses,$employee->geninfo->campus_id,['class'=>'form-control js-select2','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                                    @else
                                    {!! Form::select('campus_id',$campuses,null,['class'=>'form-control js-select2','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('Date Separated:') !!}
                                    @isset($employee)
                                    <input type="text" class="js-flatpickr form-control bg-white" id="example-flatpickr-default" name="dateseparated" value="{{ $employee->geninfo->dateseparated }}">
                                    @else
                                    <input type="text" class="js-flatpickr form-control bg-white" id="example-flatpickr-default" name="dateseparated" placeholder="">
                                    @endif
                                </div>
                            </div>  
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('Select Payroll Terms') !!}
                                    @isset($employee)
                                    {!! Form::select('term_id',$terms,$employee->geninfo->term_id,['class'=>'form-control js-select2','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                                    @else
                                    {!! Form::select('term_id',$terms,null,['class'=>'form-control js-select2','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('Select Employee Status') !!}
                                    @isset($employee)
                                    {!! Form::select('status_id',$status,$employee->geninfo->status_id,['class'=>'form-control js-select2','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                                    @else
                                    {!! Form::select('status_id',$status,null,['class'=>'form-control js-select2','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('Time In:') !!}
                                    @if(isset($employee))
                                    {!! Form::text('time_in',date("g:i a", strtotime($employee->schedule->time_in)),['class'=>'form-control tp']) !!}
                                    @else
                                    {!! Form::text('time_in',null,['class'=>'form-control tp']) !!}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('Time Out:') !!}
                                    @if(isset($employee))
                                    {!! Form::text('time_out',date("g:i a", strtotime($employee->schedule->time_out)),['class'=>'form-control tp']) !!}
                                    @else
                                    {!! Form::text('time_out',null,['class'=>'form-control tp']) !!}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('Select Rest Day') !!}
                                    @if(isset($employee))
                                    <?php $days = $employee->restdays()->pluck('restday_id','restday_id'); ?>
                                    {!! Form::select('restday_id[]',$weekdays,$days,['class'=>'form-control js-select2','multiple','style'=>'width:100%;']) !!}
                                    @else
                                    {!! Form::select('restday_id[]',$weekdays,null,['class'=>'form-control js-select2','multiple','style'=>'width:100%;']) !!}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="btabs-static-settings" role="tabpanel">
                        <h4 class="font-w400">Salary Profile</h4>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('Salary Rate:') !!}
                                    @if(isset($employee))
                                    {!! Form::text('salaryrate',$employee->salinfo->salaryrate,['class'=>'form-control']) !!}
                                    @else
                                    {!! Form::text('salaryrate','0.00',['class'=>'form-control']) !!}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('Select Bank:') !!}
                                    @if(isset($employee))
                                    {!! Form::select('bank_id',$banks,$employee->salinfo->bank_id,['class'=>'form-control js-select2','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                                    @else
                                    {!! Form::select('bank_id',$banks,null,['class'=>'form-control js-select2','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('Account #:') !!}
                                    @if(isset($employee))
                                    {!! Form::text('accountnumber',$employee->salinfo->accountnumber,['class'=>'form-control']) !!}
                                    @else
                                    {!! Form::text('accountnumber',null,['class'=>'form-control']) !!}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('SSS #:') !!}
                                    @if(isset($employee))
                                    {!! Form::text('sss_number',$employee->salinfo->sss_number,['class'=>'form-control']) !!}
                                    @else
                                    {!! Form::text('sss_number',null,['class'=>'form-control']) !!}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('HDMF #:') !!}
                                    @if(isset($employee))
                                    {!! Form::text('hdmf_number',$employee->salinfo->hdmf_number,['class'=>'form-control']) !!}
                                    @else
                                    {!! Form::text('hdmf_number',null,['class'=>'form-control']) !!}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('PHIC #:') !!}
                                    @if(isset($employee))
                                    {!! Form::text('phic_number',$employee->salinfo->phic_number,['class'=>'form-control']) !!}
                                    @else
                                    {!! Form::text('phic_number',null,['class'=>'form-control']) !!}
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('TIN #:') !!}
                                    @if(isset($employee))
                                    {!! Form::text('tin_number',$employee->salinfo->tin_number,['class'=>'form-control']) !!}
                                    @else
                                    {!! Form::text('tin_number',null,['class'=>'form-control']) !!}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            @if(isset($employee))
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
            @else
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
            @endif
        </div>
    </div>
</div>
{!! Form::close() !!}
@endsection
