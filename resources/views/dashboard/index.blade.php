@extends('master')

@section('content')
<h2 class="content-heading">Dashboard of <span style="text-transform: uppercase;font-weight: bold;color: blue;">{{ $user->employee->FullName }}</span></h2>

@endsection