@extends('master')
@section('content')
<h2 class="content-heading">Employee Overtime Module</h2>
<div class="row">
    <div class="col-md-4">
        <div class="block block-themed">
            <div class="block-header bg-earth">
                <h3 class="block-title">Create Overtime</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option">
                        <i class="si si-wrench"></i>
                    </button>
                </div>
            </div>
            <div class="block-content">
                {!! Form::open(['method'=>'POST','action'=>'EmployeeovertimeController@store']) !!}
                    <div class="form-group">
                        {!! Form::label('Date') !!}
                        <input type="text" class="js-flatpickr form-control bg-white" id="example-flatpickr-default" name="date" placeholder="">
                    </div>
                    @if(Auth::user()->usertype == 1)
                    <div class="form-group">
                        {!! Form::label('Select Employee') !!}
                        {!! Form::select('employee_id',$employees,null,['class'=>'js-select2 form-control','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                    </div>
                    @endif
                    <div class="form-group">
                        {!! Form::label('Overtime Date') !!}
                        <input type="text" class="js-flatpickr form-control bg-white" id="example-flatpickr-default" name="ot_date" placeholder="">
                    </div>
                    <div class="form-group">
                        {!! Form::label('Time Start') !!}
                        {!! Form::text('ot_time_start',null,['class'=>'form-control tp']) !!}
                    </div>                  
                    <div class="form-group">
                        {!! Form::label('Time End') !!}
                        {!! Form::text('ot_time_end',null,['class'=>'form-control tp']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('Hours Equivalent') !!}
                        {!! Form::text('ot_hours',null,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('Minutes Equivalent') !!}
                        {!! Form::text('ot_minutes',null,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('Remarks') !!}
                        {!! Form::textarea('remarks',null,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="block block-themed">
            <div class="block-header bg-earth">
                <h3 class="block-title">Overtime Application List</h3>
            </div>
            <div class="block-content block-content-full">
                <table class="table table-bordered table-striped table-vcenter data-table" style="font-size: 11px;">
                    <thead>
                        <tr>
                            <th class="text-center">Date</th>
                            <th class="text-center">Employee</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" width="50">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('modal')
@foreach($employee_ot as $data)
{!! Form::open(['method'=>'PATCH','action'=>['EmployeeovertimeController@update',$data->id]]) !!}
<div class="modal fade" id="edit{{ $data->id }}">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="block block-themed">
                <div class="block-header bg-earth">
                    <h3 class="block-title">Edit Overtime Application of {{ $data->employee->FullName }}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="block-content">
                <div class="form-group">
                    {!! Form::label('Date') !!}
                    <input type="text" class="js-flatpickr form-control bg-white" id="example-flatpickr-default" name="date" value="{{ $data->date }}">
                </div>
                @if(Auth::user()->usertype == 1)
                <div class="form-group">
                    {!! Form::label('Select Employee') !!}
                    {!! Form::select('employee_id',$employees,$data->employee_id,['class'=>'js-select2 form-control','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                </div>
                @endif
                <div class="form-group">
                    {!! Form::label('Overtime Date') !!}
                    <input type="text" class="js-flatpickr form-control bg-white" id="example-flatpickr-default" name="ot_date" value="{{ $data->ot_date }}">
                </div>
                <div class="form-group">
                    {!! Form::label('Time Start') !!}
                    {!! Form::text('ot_time_start',$data->ot_time_start,['class'=>'form-control tp']) !!}
                </div>                  
                <div class="form-group">
                    {!! Form::label('Time End') !!}
                    {!! Form::text('ot_time_end',$data->ot_time_end,['class'=>'form-control tp']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('Hours Equivalent') !!}
                    {!! Form::text('ot_hours',$data->ot_hours,['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('Minutes Equivalent') !!}
                    {!! Form::text('ot_minutes',$data->ot_minutes,['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('Remarks') !!}
                    {!! Form::textarea('remarks',$data->remarks,['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
                </div>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
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
                <p class="text-center">{{ \Carbon\Carbon::parse($data->date)->toFormattedDateString() }} - {{ $data->employee->FullName }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal"><i class="si si-close"></i> Close</button>
                <a href="{{ action('EmployeeovertimeController@delete',$data->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</a>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
@section('js')
<script type="text/javascript">
  $(function () {
    
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ action('EmployeeovertimeController@index') }}",
        columns: [
            {data: 'date', name: 'date'},
            {data: 'employee_id', name: 'employee_id'},
            {data: 'approved', name: 'approved'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    
  });
</script>
@endsection