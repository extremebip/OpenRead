@extends('layouts.app')

@section('content')
<div class="content text-white margin-button">
    <p class="title">Write Story</p>
    <div>
        <a href="#" class="btn btn-secondary btn-block btn-write-story">Add chapter</a>
    </div>
    <div>
        <a href="{{ route('show-create-story') }}" class="btn btn-secondary btn-block btn-write-story">Add new story</a>
    </div>
    <div>
        <a href="#" class="btn btn-secondary btn-block btn-write-story">Edit existing story</a>
    </div>
</div>
@endsection