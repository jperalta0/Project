@extends('master')

@section('content')
<h2 class="content-heading">Change Password Module</h2>
<div class="row">
    <div class="col-md-6">
        <div class="block block-themed">
            <div class="block-header bg-earth">
                @if($user->respondent_id != NULL)
                <h3 class="block-title">User Account of <span style="text-transform: uppercase;">{{ $user->respondent->fullname }}</span></h3>
                @else
                <h3 class="block-title">User Account of <span style="text-transform: uppercase;">{{ $user->name }}</span></h3>
                @endif
                <div class="block-options">
                    <button type="button" class="btn-block-option">
                        <i class="si si-wrench"></i>
                    </button>
                </div>
            </div>
            <div class="block-content">
                {!! Form::open(['method'=>'POST','action'=>['ChangepasswordController@post_change_password',$user->id]]) !!}    
                    <div class="form-group">
                        {!! Form::label('New Password') !!}
                        {!! Form::password('newpassword',['class'=>'form-control']) !!}
                    </div>                
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
                        @if($user->role == 'STUDENT')
                        <a href="{{ action('StudentController@dashboard') }}" class="btn btn-success"><i class="fa fa-home"></i> Back to Dashboard</a>
                        @endif
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection