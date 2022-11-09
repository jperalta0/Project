@extends('master')

@section('content')
@if(Auth::user()->usertype == 1)
<h2 class="content-heading">DTR Entries</span></h2>
@else
<h2 class="content-heading">DTR Entries of <span style="text-transform: uppercase;font-weight: bold;color: blue;">{{ $user->employee->FullName }}</span></h2>
@endif
<div class="row">
    <div class="col-md-12">
        <div class="block block-themed">
            <div class="block-header bg-earth">
                <h3 class="block-title">DTR Entries List</h3>
            </div>
            <div class="block-content block-content-full">
                <table class="table table-bordered" id="table1">
                    <thead>
                        @if(Auth::user()->usertype == 1)
                        <tr>
                            <th class="text-center">Date</th>
                            <th class="text-center">Employee Name</th>
                            <th class="text-center">Weekday</th>
                            <th class="text-center">Time IN</th>
                            <th class="text-center">Time OUT</th>
                        </tr>
                        @else
                        <tr>
                            <th class="text-center">Date</th>
                            <th class="text-center">Weekday</th>
                            <th class="text-center">Time IN</th>
                            <th class="text-center">Time OUT</th>
                        </tr>
                        @endif
                    </thead>
                    <tbody>
                        @foreach($dtr as $d)
                            @if(Auth::user()->usertype == 1)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($d->date)->toFormattedDateString() }}</td>
                                    <td>{{ $d->employee->FullName }}</td>
                                    <td>{{ date("l", strtotime($d->date)) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($d->login)->toTimeString()}}</td>
                                    <td>
                                        @if($d->logout == NULL)
                                        
                                        @else
                                        {{ \Carbon\Carbon::parse($d->logout)->toTimeString()}}
                                        @endif
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($d->date)->toFormattedDateString() }}</td>
                                    <td>{{ date("l", strtotime($d->date)) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($d->login)->toTimeString()}}</td>
                                    <td>
                                        @if($d->logout == NULL)
                                        
                                        @else
                                        {{ \Carbon\Carbon::parse($d->logout)->toTimeString()}}
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection