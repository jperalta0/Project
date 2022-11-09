@extends('master')

@section('content')
<h2 class="content-heading">Employee Module</h2>
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
<div class="row">
    <div class="col-md-12">
        <div class="block block-themed">
            <div class="block-header bg-earth">
                <h3 class="block-title">
                    Other Information Profile of <span style="text-transform: uppercase; font-weight: bolder;">{{ $employee->FullName }}</span>
                </h3>
                <div class="block-options">
                    <a href="{{ action('EmployeeController@index') }}" class="btn btn-sm btn-secondary"><i class="fa fa-home"></i> Back to Index</a>
                </div>
            </div>
            <div class="block-content">
                <ul class="nav nav-tabs nav-tabs-block" data-toggle="tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#btabs-static-home">Employee Dependents</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#btabs-static-profile">Educational Attainment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#btabs-static-settings">Employment History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#btabs-static-seminar">Seminars Attended</a>
                    </li>
                </ul>
                <div class="block-content tab-content">
                    <div class="tab-pane active" id="btabs-static-home" role="tabpanel">
                        <h4 class="font-w400">Employee Dependents</h4>
                        <a href="#dependent" data-toggle="modal" class="btn btn-primary"><i class="fa fa-plus"></i> Create Entry</a><hr>
                        <div class="modal fade" id="dependent" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-popout" role="document">
                                <div class="modal-content">
                                    {!! Form::open(['method'=>'POST','action'=>['EmployeeController@store_dependent',$employee->id]]) !!}
                                    <div class="block block-themed">
                                        <div class="block-header bg-earth">
                                            <h3 class="block-title">Add Entry</h3>
                                            <div class="block-options">
                                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                    <i class="si si-close"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="block-content">
                                            <div class="form-group">
                                                {!! Form::label('Dependent Name') !!}
                                                {!! Form::text('name',null,['class'=>'form-control']) !!}
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label('Relationship') !!}
                                                {!! Form::text('relationship',null,['class'=>'form-control']) !!}
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label('Birthdate') !!}
                                                {!! Form::text('birthdate',null,['class'=>'form-control','placeholder'=>'Use the format yyyy-mm-dd']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save"></i> Save
                                        </button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Name</th>
                                            <th class="text-center">Relationship</th>
                                            <th class="text-center">Birthdate</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($employee->dependents as $data)
                                        <tr>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->relationship }}</td>
                                            <td>{{ \Carbon\Carbon::parse($data->birthdate)->toFormattedDateString() }}</td>
                                            <td class="text-center">
                                                <a href="#edit{{ $data->id }}" data-toggle="modal" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                                                <a href="#delete{{ $data->id }}" data-toggle="modal" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="edit{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-popout" role="document">
                                                <div class="modal-content">
                                                    {!! Form::open(['method'=>'PATCH','action'=>['EmployeeController@update_dependent',$data->id]]) !!}
                                                    <div class="block block-themed">
                                                        <div class="block-header bg-earth">
                                                            <h3 class="block-title">Update Entry</h3>
                                                            <div class="block-options">
                                                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                                    <i class="si si-close"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="block-content">
                                                            <div class="form-group">
                                                                {!! Form::label('Dependent Name') !!}
                                                                {!! Form::text('name',$data->name,['class'=>'form-control']) !!}
                                                            </div>
                                                            <div class="form-group">
                                                                {!! Form::label('Relationship') !!}
                                                                {!! Form::text('relationship',$data->relationship,['class'=>'form-control']) !!}
                                                            </div>
                                                            <div class="form-group">
                                                                {!! Form::label('Birthdate') !!}
                                                                {!! Form::text('birthdate',$data->birthdate,['class'=>'form-control','placeholder'=>'Use the format yyyy-mm-dd']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fa fa-save"></i> Update
                                                        </button>
                                                    </div>
                                                    {!! Form::close() !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="delete{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-popout" role="document">
                                                <div class="modal-content">
                                                    <div class="block block-themed">
                                                        <div class="block-header bg-earth">
                                                            <h3 class="block-title">Delete Entry</h3>
                                                            <div class="block-options">
                                                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                                    <i class="si si-close"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="block-content">
                                                            <center>
                                                            <i class="fa fa-exclamation-circle" aria-hidden="true" style="font-size:50px;color:red;"></i>
                                                            </center>
                                                            <h3 class="text-center">Are you sure you want to delete this entry?</h3>
                                                            <p class="text-center">{{ $data->name }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a href="{{ action('EmployeeController@delete_dependent',$data->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="btabs-static-profile" role="tabpanel">
                        <h4 class="font-w400">Educational Attainment</h4>
                        <a href="#education" data-toggle="modal" class="btn btn-primary"><i class="fa fa-plus"></i> Create Entry</a><hr>
                        <div class="modal fade" id="education" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-popout" role="document">
                                <div class="modal-content">
                                    {!! Form::open(['method'=>'POST','action'=>['EmployeeController@store_education',$employee->id]]) !!}
                                    <div class="block block-themed">
                                        <div class="block-header bg-earth">
                                            <h3 class="block-title">Add Entry</h3>
                                            <div class="block-options">
                                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                    <i class="si si-close"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="block-content">
                                            <div class="form-group">
                                                {!! Form::label('Select Level') !!}
                                                {!! Form::select('level',['PRIMARY'=>'PRIMARY','SECONDARY'=>'SECONDARY','TERTIARY'=>'TERTIARY','VOCATIONAL'=>'VOCATIONAL','POST GRADUATE'=>'POST GRADUATE'],null,['class'=>'js-select2 form-control','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label('School Name') !!}
                                                {!! Form::text('name',null,['class'=>'form-control']) !!}
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label('Course') !!}
                                                {!! Form::text('course',null,['class'=>'form-control']) !!}
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label('Year Graduated') !!}
                                                {!! Form::text('year',null,['class'=>'form-control']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save"></i> Save
                                        </button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Level</th>
                                            <th class="text-center">School Name</th>
                                            <th class="text-center">Course</th>
                                            <th class="text-center">Year Graduated</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($employee->educations as $edata)
                                        <tr>
                                            <td>{{ $edata->level }}</td>
                                            <td>{{ $edata->name }}</td>
                                            <td>{{ $edata->course }}</td>
                                            <td>{{ $edata->year }}</td>
                                            <td class="text-center">
                                                <a href="#edit{{ $edata->id }}" data-toggle="modal" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                                                <a href="#delete{{ $edata->id }}" data-toggle="modal" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="edit{{ $edata->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-popout" role="document">
                                                <div class="modal-content">
                                                    {!! Form::open(['method'=>'PATCH','action'=>['EmployeeController@update_education',$edata->id]]) !!}
                                                    <div class="block block-themed">
                                                        <div class="block-header bg-earth">
                                                            <h3 class="block-title">Update Entry</h3>
                                                            <div class="block-options">
                                                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                                    <i class="si si-close"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="block-content">
                                                            <div class="form-group">
                                                                {!! Form::label('Select Level') !!}
                                                                {!! Form::select('level',['PRIMARY'=>'PRIMARY','SECONDARY'=>'SECONDARY','TERTIARY'=>'TERTIARY','VOCATIONAL'=>'VOCATIONAL','POST GRADUATE'=>'POST GRADUATE'],$edata->level,['class'=>'js-select2 form-control','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                                                            </div>
                                                            <div class="form-group">
                                                                {!! Form::label('School Name') !!}
                                                                {!! Form::text('name',$edata->name,['class'=>'form-control']) !!}
                                                            </div>
                                                            <div class="form-group">
                                                                {!! Form::label('Course') !!}
                                                                {!! Form::text('course',$edata->course,['class'=>'form-control']) !!}
                                                            </div>
                                                            <div class="form-group">
                                                                {!! Form::label('Year Graduated') !!}
                                                                {!! Form::text('year',$edata->year,['class'=>'form-control']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fa fa-save"></i> Update
                                                        </button>
                                                    </div>
                                                    {!! Form::close() !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="delete{{ $edata->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-popout" role="document">
                                                <div class="modal-content">
                                                    <div class="block block-themed">
                                                        <div class="block-header bg-earth">
                                                            <h3 class="block-title">Delete Entry</h3>
                                                            <div class="block-options">
                                                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                                    <i class="si si-close"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="block-content">
                                                            <center>
                                                            <i class="fa fa-exclamation-circle" aria-hidden="true" style="font-size:50px;color:red;"></i>
                                                            </center>
                                                            <h3 class="text-center">Are you sure you want to delete this entry?</h3>
                                                            <p class="text-center">{{ $edata->level }} - {{ $edata->name }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a href="{{ action('EmployeeController@delete_education',$edata->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="btabs-static-settings" role="tabpanel">
                        <h4 class="font-w400">Employment History</h4>
                        <a href="#work" data-toggle="modal" class="btn btn-primary"><i class="fa fa-plus"></i> Create Entry</a><hr>
                        <div class="modal fade" id="work" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-popout" role="document">
                                <div class="modal-content">
                                    {!! Form::open(['method'=>'POST','action'=>['EmployeeController@store_work',$employee->id]]) !!}
                                    <div class="block block-themed">
                                        <div class="block-header bg-earth">
                                            <h3 class="block-title">Add Entry</h3>
                                            <div class="block-options">
                                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                    <i class="si si-close"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="block-content">
                                            <div class="form-group">
                                                {!! Form::label('Position') !!}
                                                {!! Form::text('position',null,['class'=>'form-control']) !!}
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label('Company Name') !!}
                                                {!! Form::text('name',null,['class'=>'form-control']) !!}
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label('Select Status') !!}
                                                {!! Form::select('status_id',$status,null,['class'=>'js-select2 form-control','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label('Inclusive Years') !!}
                                                {!! Form::text('year',null,['class'=>'form-control']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save"></i> Save
                                        </button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Position</th>
                                            <th class="text-center">Company Name</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Inclusive Years</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($employee->works as $wdata)
                                        <tr>
                                            <td>{{ $wdata->position }}</td>
                                            <td>{{ $wdata->name }}</td>
                                            <td>{{ $wdata->status->name }}</td>
                                            <td>{{ $wdata->year }}</td>
                                            <td class="text-center">
                                                <a href="#edit{{ $wdata->id }}" data-toggle="modal" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                                                <a href="#delete{{ $wdata->id }}" data-toggle="modal" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="edit{{ $wdata->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-popout" role="document">
                                                <div class="modal-content">
                                                    {!! Form::open(['method'=>'PATCH','action'=>['EmployeeController@update_work',$wdata->id]]) !!}
                                                    <div class="block block-themed">
                                                        <div class="block-header bg-earth">
                                                            <h3 class="block-title">Update Entry</h3>
                                                            <div class="block-options">
                                                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                                    <i class="si si-close"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="block-content">
                                                            <div class="form-group">
                                                                {!! Form::label('Position') !!}
                                                                {!! Form::text('position',$wdata->position,['class'=>'form-control']) !!}
                                                            </div>
                                                            <div class="form-group">
                                                                {!! Form::label('Company Name') !!}
                                                                {!! Form::text('name',$wdata->name,['class'=>'form-control']) !!}
                                                            </div>
                                                            <div class="form-group">
                                                                {!! Form::label('Select Status') !!}
                                                                {!! Form::select('status_id',$status,$wdata->status_id,['class'=>'js-select2 form-control','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                                                            </div>
                                                            <div class="form-group">
                                                                {!! Form::label('Inclusive Years') !!}
                                                                {!! Form::text('year',$wdata->year,['class'=>'form-control']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fa fa-save"></i> Update
                                                        </button>
                                                    </div>
                                                    {!! Form::close() !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="delete{{ $wdata->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-popout" role="document">
                                                <div class="modal-content">
                                                    <div class="block block-themed">
                                                        <div class="block-header bg-earth">
                                                            <h3 class="block-title">Delete Entry</h3>
                                                            <div class="block-options">
                                                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                                    <i class="si si-close"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="block-content">
                                                            <center>
                                                            <i class="fa fa-exclamation-circle" aria-hidden="true" style="font-size:50px;color:red;"></i>
                                                            </center>
                                                            <h3 class="text-center">Are you sure you want to delete this entry?</h3>
                                                            <p class="text-center">{{ $wdata->name }} - {{ $wdata->position }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a href="{{ action('EmployeeController@delete_work',$wdata->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="btabs-static-seminar" role="tabpanel">
                        <h4 class="font-w400">Seminars Attended</h4>
                        <a href="#seminar" data-toggle="modal" class="btn btn-primary"><i class="fa fa-plus"></i> Create Entry</a><hr>
                        <div class="modal fade" id="seminar" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-popout" role="document">
                                <div class="modal-content">
                                    {!! Form::open(['method'=>'POST','action'=>['EmployeeController@store_seminar',$employee->id]]) !!}
                                    <div class="block block-themed">
                                        <div class="block-header bg-earth">
                                            <h3 class="block-title">Add Entry</h3>
                                            <div class="block-options">
                                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                    <i class="si si-close"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="block-content">
                                            <div class="form-group">
                                                {!! Form::label('Seminar Date') !!}
                                                {!! Form::text('date',null,['class'=>'form-control','placeholder'=>'Use the date format yyyy-mm-dd']) !!}
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label('Seminar Title') !!}
                                                {!! Form::text('name',null,['class'=>'form-control']) !!}
                                            </div>
                                            <div class="form-group">
                                                {!! Form::label('Venue') !!}
                                                {!! Form::text('venue',null,['class'=>'form-control']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save"></i> Save
                                        </button>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Seminar Title</th>
                                            <th class="text-center">Venue</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($employee->seminars as $sdata)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($sdata->date)->toFormattedDateString() }}</td>
                                            <td>{{ $sdata->name }}</td>
                                            <td>{{ $sdata->venue }}</td>
                                            <td class="text-center">
                                                <a href="#edit{{ $sdata->id }}" data-toggle="modal" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></a>
                                                <a href="#delete{{ $sdata->id }}" data-toggle="modal" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="edit{{ $sdata->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-popout" role="document">
                                                <div class="modal-content">
                                                    {!! Form::open(['method'=>'PATCH','action'=>['EmployeeController@update_seminar',$sdata->id]]) !!}
                                                    <div class="block block-themed">
                                                        <div class="block-header bg-earth">
                                                            <h3 class="block-title">Update Entry</h3>
                                                            <div class="block-options">
                                                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                                    <i class="si si-close"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="block-content">
                                                            <div class="form-group">
                                                                {!! Form::label('Seminar Date') !!}
                                                                {!! Form::text('date',$sdata->date,['class'=>'form-control']) !!}
                                                            </div>
                                                            <div class="form-group">
                                                                {!! Form::label('Seminar Title') !!}
                                                                {!! Form::text('name',$sdata->name,['class'=>'form-control']) !!}
                                                            </div>
                                                            <div class="form-group">
                                                                {!! Form::label('Venue') !!}
                                                                {!! Form::text('venue',$sdata->venue,['class'=>'form-control']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fa fa-save"></i> Update
                                                        </button>
                                                    </div>
                                                    {!! Form::close() !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="delete{{ $sdata->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-popout" role="document">
                                                <div class="modal-content">
                                                    <div class="block block-themed">
                                                        <div class="block-header bg-earth">
                                                            <h3 class="block-title">Delete Entry</h3>
                                                            <div class="block-options">
                                                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                                    <i class="si si-close"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="block-content">
                                                            <center>
                                                            <i class="fa fa-exclamation-circle" aria-hidden="true" style="font-size:50px;color:red;"></i>
                                                            </center>
                                                            <h3 class="text-center">Are you sure you want to delete this entry?</h3>
                                                            <p class="text-center">{{ $sdata->name }} - {{ $sdata->venue }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a href="{{ action('EmployeeController@delete_seminar',$sdata->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
