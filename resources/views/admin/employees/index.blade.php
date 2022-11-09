@extends('master')

@section('content')
<h2 class="content-heading">Employee Module</h2>
<div class="row">
    <div class="col-md-12">
        <div class="block block-themed">
            <div class="block-header bg-earth">
                <h3 class="block-title">Employee List</h3>
                <div class="block-options">
                    <a href="{{ action('EmployeeController@create') }}" class="btn btn-sm btn-secondary"><i class="fa fa-plus"></i> Create Entry</a>
                </div>
            </div>
            <div class="block-content block-content-full">
                <table class="table table-bordered table-striped table-vcenter data-table" style="text-transform: uppercase;">
                    <thead>
                        <tr>
                            <th class="text-center">Employee Name</th>
                            <th class="text-center">Department</th>
                            <th class="text-center" width="150">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script type="text/javascript">
  $(function () {
    
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ action('EmployeeController@index') }}",
        columns: [
            {data: 'name', name: 'name'},
            {data: 'campus', name: 'campus'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    
  });
</script>
@endsection