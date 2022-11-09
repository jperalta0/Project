@extends('master')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.standalone.min.css">
@section('content')
<h2 class="content-heading">Employee Leave Module</h2>
<div class="row">
    <div class="col-md-6">
        <div class="block block-themed">
            <div class="block-header bg-earth">
                <h3 class="block-title">Update Leave of {{ $employee_leave->employee->FullName }}</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option">
                        <i class="si si-wrench"></i>
                    </button>
                </div>
            </div>
            <div class="block-content">
                {!! Form::model($employee_leave,['method'=>'PATCH','action'=>['EmployeeleaveController@update',$employee_leave->id]]) !!}
                    <div class="form-group">
                        {!! Form::label('Date') !!}
                        <input type="text" class="js-flatpickr form-control bg-white" id="example-flatpickr-default" name="date" value="{{ $employee_leave->date }}">
                    </div>
                     @if(Auth::user()->usertype == 1)
                    <div class="form-group">
                        {!! Form::label('Select Employee') !!}
                        {!! Form::select('employee_id',$employees,null,['class'=>'js-select2 form-control','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                    </div>
                    @endif
                    <div class="form-group">
                        {!! Form::label('Select Leave') !!}
                        {!! Form::select('leave_id',$leaves,null,['class'=>'js-select2 form-control','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('Select Dates') !!}
                        {!! Form::text('inclusiveDates[]',null,['class'=>'form-control date']) !!}
                    </div>                  
                    <div class="form-group">
                        @if($employee_leave->withPay == 1)
                        <input type="checkbox" name="withPay" value="1" checked="checked">
                        @else
                        <input type="checkbox" name="withPay" value="1">
                        @endif
                        With Pay?
                    </div>
                    <div class="form-group">
                        {!! Form::label('Remarks') !!}
                        {!! Form::textarea('remarks',null,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
                        <a href="{{ action('EmployeeleaveController@index') }}" class="btn btn-success"><i class="fa fa-home"></i> Back to Index</a>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="block block-themed">
            <div class="block-header bg-earth">
                <h3 class="block-title">Leave Dates List</h3>
            </div>
            <div class="block-content block-content-full">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Date</th>
                            <th class="text-center" width="80">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employee_leave->leavedates as $data)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($data->leave_date)->toFormattedDateString() }}</td>
                            <td class="text-center">
                                <a href="#delete{{ $data->id }}" class="btn btn-sm btn-danger" data-toggle="modal"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        <div class="modal fade" id="delete{{ $data->id }}">
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
                                    </div>
                                    <div class="block-content">
                                        <center>
                                            <i class="fa fa-exclamation-triangle" style="font-size:48px;color:red"></i>
                                        </center>
                                        <h3 class="text-center">Are you sure you want to delete this entry?</h3>
                                        <p class="text-center">{{ \Carbon\Carbon::parse($data->leave_date)->toFormattedDateString() }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-alt-secondary" data-dismiss="modal"><i class="si si-close"></i> Close</button>
                                        <a href="{{ action('EmployeeleaveController@delete_leave_date',$data->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</a>
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
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>
    $('.date').datepicker({
        multidate: true,
            format: 'yyyy-mm-dd',
    });
</script>
@endsection