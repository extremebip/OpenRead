@extends('layouts.app')

@section('title', 'Change Password')
@section('style')
<style>
    .content {
        padding: initial;
        padding-bottom: 8%;
    }
    h1 {
        color: white;
        font-size: 46px;
        text-align: center;
        margin-top: 4%;
        margin-bottom: 4%;
    }
    h2 {
        color: white;
        margin-left: auto;
        margin-right: auto;
        font-size: medium;
    }
    form {
        padding-left: 15%;
        padding-right: 15%;
    }
    .signup{
        display: block;
        width: 100%;
        height: calc(1.5em + 0.75rem + 2px);
        padding: 1em;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: white;
        background-clip: padding-box;
        border-radius: 4px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        background-color: #6D6E7D;
    }
</style>
@endsection
@section('content')
<div class="content">
    <br>
    <h1>Change Password</h1>
    {{ Form::open(['route' => 'change-password']) }}
    <h2>
        {{ Form::label('old_password', 'Old Password') }}
        {{ Form::password('old_password', ['class' => 'signup'.($errors->has('old_password') ? ' is-invalid': '')]) }}
        @error('old_password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <br>

        {{ Form::label('password', 'New Password') }}
        {{ Form::password('password', ['class' => 'signup'.($errors->has('password') ? ' is-invalid': '')]) }}
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <br>

        {{ Form::label('password_confirmation', 'Confirm Password') }}
        {{ Form::password('password_confirmation', ['class' => 'signup']) }}
    </h2>
    <br>
    <center>
        <button type="submit" class="btn btn-secondary btn-openread" style="background-color: #6D6E7D; width:100px;">Save</button>
        <a class="btn btn-secondary btn-openread" style="background-color: #6D6E7D; width:100px;" href="{{ route('show-profile', ['u' => Auth::user()->username]) }}">Cancel</a>
    </center>
    {{ Form::close() }}
</div>
@endsection