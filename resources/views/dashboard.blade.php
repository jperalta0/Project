@extends('master')

@section('content')
<h1>Dashboard</h1>
<div class="row">
    <div class="col-6 col-xl-3">
        <a class="block block-link-shadow text-right" href="javascript:void(0)">
            <div class="block-content block-content-full clearfix">
                <div class="float-left mt-10 d-none d-sm-block">
                    <i class="si si-users fa-3x text-body-bg-dark"></i>
                </div>
                <div class="font-size-h3 font-w600" data-toggle="countTo" data-speed="1000" data-to="{{ $empcount }}">{{ $empcount }}</div>
                <div class="font-size-sm font-w600 text-uppercase text-muted">Total Employees</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-xl-3">
        <a class="block block-link-shadow text-right" href="javascript:void(0)">
            <div class="block-content block-content-full clearfix">
                <div class="float-left mt-10 d-none d-sm-block">
                    <i class="si si-clock fa-3x text-body-bg-dark"></i>
                </div>
                <div class="font-size-h3 font-w600" data-toggle="countTo" data-speed="1000" data-to="{{ $dtrcount }}">{{ $dtrcount }}</div>
                <div class="font-size-sm font-w600 text-uppercase text-muted">Attendance Today</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-xl-3">
        <a class="block block-link-shadow text-right" href="javascript:void(0)">
            <div class="block-content block-content-full clearfix">
                <div class="float-left mt-10 d-none d-sm-block">
                    <i class="si si-users fa-3x text-body-bg-dark"></i>
                </div>
                <div class="font-size-h3 font-w600" data-toggle="countTo" data-speed="1000" data-to="{{ $absentcount }}">{{ $absentcount }}</div>
                <div class="font-size-sm font-w600 text-uppercase text-muted">Absent Today</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-xl-3">
        <a class="block block-link-shadow text-right" href="javascript:void(0)">
            <div class="block-content block-content-full clearfix">
                <div class="float-left mt-10 d-none d-sm-block">
                    <i class="si si-plane fa-3x text-body-bg-dark"></i>
                </div>
                <div class="font-size-h3 font-w600" data-toggle="countTo" data-speed="1000" data-to="{{ $vacationcount }}">{{ $vacationcount }}</div>
                <div class="font-size-sm font-w600 text-uppercase text-muted">Scheduled Vacation</div>
            </div>
        </a>
    </div>
</div>
@endsection