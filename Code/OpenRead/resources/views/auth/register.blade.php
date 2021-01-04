@extends('layouts.app')

@section('title', 'Sign Up')
@section('style')
<style>
    h1 {
        color: white;
        font-size: 46px;
        text-align: center;
        margin-top: 4%;
        margin-bottom: 4%;
    }
    h2 {
        color: white;
        margin: 0% 15%;
        font-size: medium;
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
<h1>Sign Up</h1>
{{ Form::open(['route' => 'register']) }}
    <h2>
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', @old('name'), ['class' => 'signup'.($errors->has('name') ? ' is-invalid': ''), 'autofocus']) }}
        @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <br>

        {{ Form::label('username', 'Username') }}
        {{ Form::text('username', @old('username'), ['class' => 'signup'.($errors->has('username') ? ' is-invalid': '')]) }}
        @error('username')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <br>

        {{ Form::label('email', 'Email') }}
        {{ Form::email('email', @old('email'), ['class' => 'signup'.($errors->has('email') ? ' is-invalid': '')]) }}
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <br>

        {{ Form::label('dob', 'Date of Birth') }}
        {{ Form::date('dob', @old('dob'), ['class' => 'signup'.($errors->has('dob') ? ' is-invalid': '')]) }}
        @error('dob')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <br>

        {{ Form::label('gender', 'Gender') }} <br>
        {{ Form::radio('gender', 'M', false, ['class' => ($errors->has('gender') ? 'is-invalid': ''), 'id' => 'male']) }}
        {{ Form::label('male', 'Male') }}
        {{ Form::radio('gender', 'F', false, ['class' => ($errors->has('gender') ? 'is-invalid': ''), 'id' => 'female']) }}
        {{ Form::label('female', 'Female') }}
        @error('gender')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <br> <br>

        {{ Form::label('password', 'Password') }}
        {{ Form::password('password', ['class' => 'signup'.($errors->has('password') ? ' is-invalid': '')]) }}
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <br>

        {{ Form::label('password_confirmation', 'Confirm Password') }}
        {{ Form::password('password_confirmation', ['class' => 'signup']) }}
        <br>
    </h2>
    <center>
        <button type="submit" class="btn btn-secondary btn-openread" style="background-color: #6D6E7D;">
            Sign Up
        </button>
    </center>
{{ Form::close() }}
@endsection