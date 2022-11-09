@extends('master')
@section('content')
<h2 class="content-heading">Employee Loan Module</h2>
<div class="row">
    <div class="col-md-4">
        <div class="block block-themed">
            <div class="block-header bg-earth">
                <h3 class="block-title">Create Loan</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option">
                        <i class="si si-wrench"></i>
                    </button>
                </div>
            </div>
            <div class="block-content">
                {!! Form::open(['method'=>'POST','action'=>'EmployeeloanController@store']) !!}
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
                        {!! Form::label('Select Loan Type') !!}
                        {!! Form::select('loan_id',$loans,null,['class'=>'js-select2 form-control','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('Loan Amount') !!}
                        {!! Form::text('amount','0.00',['class'=>'form-control']) !!}
                    </div>                  
                    <div class="form-group">
                        {!! Form::label('Deduction Amount') !!}
                        {!! Form::text('deducted_amount','0.00',['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('Deduction Start Date') !!}
                        <input type="text" class="js-flatpickr form-control bg-white" id="example-flatpickr-default" name="date_started" placeholder="">
                    </div>
                    <div class="form-group">
                        {!! Form::label('Deduction End Date') !!}
                        <input type="text" class="js-flatpickr form-control bg-white" id="example-flatpickr-default" name="date_ended" placeholder="">
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
                <h3 class="block-title">Loan Application List</h3>
            </div>
            <div class="block-content block-content-full">
                <table class="table table-bordered table-striped table-vcenter data-table" style="font-size: 11px;">
                    <thead>
                        <tr>
                            <th class="text-center">Date</th>
                            <th class="text-center">Employee</th>
                            <th class="text-center">Loan Type</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" width="90">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('modal')
@foreach($employee_loans as $data)
{!! Form::open(['method'=>'PATCH','action'=>['EmployeeloanController@update',$data->id]]) !!}
<div class="modal fade" id="edit{{ $data->id }}">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="block block-themed">
                <div class="block-header bg-earth">
                    <h3 class="block-title">Edit Loan Application of {{ $data->employee->FullName }}</h3>
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
                    {!! Form::label('Select Loan Type') !!}
                    {!! Form::select('loan_id',$loans,$data->loan_id,['class'=>'js-select2 form-control','placeholder'=>'PLEASE SELECT','style'=>'width:100%;']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('Loan Amount') !!}
                    {!! Form::text('amount',$data->amount,['class'=>'form-control']) !!}
                </div>                  
                <div class="form-group">
                    {!! Form::label('Deduction Amount') !!}
                    {!! Form::text('deducted_amount',$data->deducted_amount,['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('Deduction Start Date') !!}
                    <input type="text" class="js-flatpickr form-control bg-white" id="example-flatpickr-default" name="date_started" value="{{ $data->date_started }}">
                </div>
                <div class="form-group">
                    {!! Form::label('Deduction End Date') !!}
                    <input type="text" class="js-flatpickr form-control bg-white" id="example-flatpickr-default" name="date_ended" value="{{ $data->date_ended }}">
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
                <p class="text-center">{{ $data->employee->FullName }} - {{ $data->loan->name }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal"><i class="si si-close"></i> Close</button>
                <a href="{{ action('EmployeeloanController@delete',$data->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</a>
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
        ajax: "{{ action('EmployeeloanController@index') }}",
        columns: [
            {data: 'date', name: 'date'},
            {data: 'employee_id', name: 'employee_id'},
            {data: 'loan_id', name: 'loan_id'},
            {data: 'approved', name: 'approved'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    
  });
</script>
@endsection