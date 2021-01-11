@extends('layouts.app')

@section('title', 'Login')
@section('style')
<style>
    h2 {
        color: white;
        margin: 0% 15%;
        font-size: medium;
    }

    .signup {
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
    <p class="title text-white">Log in</p>
    {{ Form::open(['route' => 'login']) }}
        <h2>
            {{ Form::label('username', 'Username / E-mail') }}
            {{ Form::text('username', @old('username'), ['class' => 'signup form-control'.($errors->has('username') ? ' is-invalid': ''), 'autofocus']) }}
            @error('username')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <br>
            {{ Form::label('password', 'Password') }}
            {{ Form::password('password', ['class' => 'signup form-control'.($errors->has('password') ? ' is-invalid': '')]) }} 
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <br>
        </h2>
        <center>
            <br><br>
            <button class="btn btn-secondary btn-openread" type="submit" style="background-color: #6D6E7D;">
                Log in
            </button>
        </center>
    {{ Form::close() }}
</div>
@endsection