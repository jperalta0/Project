@extends('master')
@section('content')
<h2 class="content-heading">Employee Adjustment Module</h2>
<div class="row">
    <div class="col-md-4">
        <div class="block block-themed">
            <div class="block-header bg-earth">
                <h3 class="block-title">Create Adjustment</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option">
                        <i class="si si-wrench"></i>
                    </button>
                </div>
            </div>
            <div class="block-content">
                {!! Form::open(['method'=>'POST','action'=>'AdjustmentController@store']) !!}
                    <div class="form-group">
                        {!! Form::label('Date') !!}
                        <input type="text" class="js-flatpickr form-control bg-white" id="example-flatpickr-default" name="date" placeholder="">
                    </div>
                    <div class="form-group">
                        {!! Form::label('Select Employee') !!}
                        {!! Form::select('employee_id',$employees,null,['class'=>'js-select2 form-control','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('Select Payment Type') !!}
                        {!! Form::select('payment_type',['1'=>'INCOME','2'=>'DEDUCTION'],null,['class'=>'js-select2 form-control','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('Description') !!}
                        {!! Form::text('description',null,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('Amount') !!}
                        {!! Form::text('amount','0.00',['class'=>'form-control']) !!}
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
                <h3 class="block-title">Employee Adjustment List</h3>
            </div>
            <div class="block-content block-content-full">
                <table class="table table-bordered table-striped table-vcenter data-table" style="font-size: 10px;">
                    <thead>
                        <tr>
                            <th class="text-center">Date</th>
                            <th class="text-center">Employee</th>
                            <th class="text-center">Payment Type</th>
                            <th class="text-center">Description</th>
                            <th class="text-center">Amount</th>
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
@foreach($adjustments as $data)
{!! Form::open(['method'=>'PATCH','action'=>['AdjustmentController@update',$data->id]]) !!}
<div class="modal fade" id="edit{{ $data->id }}">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="block block-themed">
                <div class="block-header bg-earth">
                    <h3 class="block-title">Edit Adjustment Entry of {{ $data->employee->FullName }}</h3>
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
                <div class="form-group">
                    {!! Form::label('Select Employee') !!}
                    {!! Form::select('employee_id',$employees,$data->employee_id,['class'=>'js-select2 form-control','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('Select Payment Type') !!}
                    {!! Form::select('payment_type',['1'=>'INCOME','2'=>'DEDUCTION'],$data->payment_type,['class'=>'js-select2 form-control','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('Description') !!}
                    {!! Form::text('description',$data->description,['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('Amount') !!}
                    {!! Form::text('amount',$data->amount,['class'=>'form-control']) !!}
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
                <p class="text-center">{{ $data->employee->FullName }} - {{ $data->amount }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal"><i class="si si-close"></i> Close</button>
                <a href="{{ action('AdjustmentController@delete',$data->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</a>
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
        ajax: "{{ action('AdjustmentController@index') }}",
        columns: [
            {data: 'date', name: 'date'},
            {data: 'employee_id', name: 'employee_id'},
            {data: 'payment_type', name: 'payment_type'},
            {data: 'description', name: 'description'},
            {data: 'amount', name: 'amount'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    
  });
</script>
@endsection