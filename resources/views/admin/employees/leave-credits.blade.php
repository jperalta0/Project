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
{!! Form::open(['method'=>'POST','action'=>['EmployeeController@store_leave_credits',$employee->id]]) !!}
<div class="row">
    <div class="col-md-12">
        <div class="block block-themed">
            <div class="block-header bg-earth">
                <h3 class="block-title">
                    Leave Credits <span style="text-transform: uppercase; font-weight: bolder;">{{ $employee->FullName }}</span>
                </h3>
                <div class="block-options">
                    <a href="{{ action('EmployeeController@index') }}" class="btn btn-sm btn-secondary"><i class="fa fa-home"></i> Back to Index</a>
                </div>
            </div>
            <div class="block-content">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            {!! Form::label('Select Leave Type') !!}
                            {!! Form::select('leave_id',$leaves,null,['class'=>'form-control js-select2','placeholder'=>'PLEASE SELECT']) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('Credit') !!}
                            {!! Form::text('credit',null,['class'=>'form-control']) !!}
                            {!! Form::hidden('employee_id',$employee->id,['class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">Leave Type</th>
                                    <th class="text-center">Credits</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employee->leavecredits as $el)
                                <tr>
                                    <td>{{ $el->leave->name }}</td>
                                    <td>{{ $el->credit }}</td>
                                    <td class="text-center">
                                        <a href="#edit{{ $el->id}}" data-toggle="modal" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                                        <a href="{{ action('EmployeeController@delete_leave_credits',$el->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
@endsection
@section('modal')
@foreach($employee->leavecredits as $el)
<div class="modal fade" id="edit{{ $el->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            {!! Form::open(['method'=>'POST','action'=>['EmployeeController@update_leave_credits',$el->id]]) !!}
            <div class="block block-themed">
                <div class="block-header bg-earth">
                    <h3 class="block-title">Edit {{ $el->leave->name }}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('Select Leave Type') !!}
                            {!! Form::select('leave_id',$leaves,$el->leave_id,['class'=>'form-control js-select2','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('Credit') !!}
                            {!! Form::text('credit',$el->credit,['class'=>'form-control']) !!}
                        </div>
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
@endforeach
@endsection