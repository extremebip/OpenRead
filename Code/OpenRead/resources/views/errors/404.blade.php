@extends('layouts.app')

@section('style')
<style>
    body {
        background-color: #1C1C1C!important;
    }

    .wrapper {
        background-color: #1C1C1C;
    }
</style>
@endsection

@section('content')
<center>
    <img src="{{ asset('assets/404.png') }}" alt="" style="width: 50vw; margin: 10vh 10vw">
</center>
@endsection