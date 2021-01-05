@extends('layouts.app')

@section('style')
<link rel="stylesheet" href="{{ asset('css/bootstrap-multiselect.min.css') }}">
<style>
    .cover-story {
        margin: 24px;
        border-radius: 20px;
        height: 200px;
        width: 200px;
        background-size: cover;
        background-position: center;
        background-blend-mode: multiply;
        vertical-align: middle;
        text-align: center;
        color: transparent;
        transition: all .3s ease;
        text-decoration: none;
        cursor: pointer;
    }

    .cover-story:hover {
        background-color: rgba(0, 0, 0, .5);
        color: #fff;
        transition: all .3s ease;
        text-decoration: none;
    }

    .cover-story span {
        display: inline-block;
        padding-top: 150px;
        font-size: 20px;
    }

    form input[type="file"] {
        display: none;
        cursor: pointer;
    }

    .form {
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

    button.multiselect {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
        font-weight: 400;
    }

    button.dropdown-item {
        color: #fff;
        background-color: #6c757d;
    }

    .dropdown-menu {
        height: auto;
        max-height: 200px;
        overflow-x: hidden;
    }
</style>
@endsection

@section('content')
<div class="content text-white">
    {{ Form::open(['route' => 'save-story', 'files' => true, 'style' => 'margin: 0px 2% 20px 2%;']) }}
        <center class="form-group">
            <label for="cover-image">
                <div class="cover-story" style="background-image: url('{{ asset('assets/homepage.png') }}')" id="cover-preview">
                    <span class="align-bottom">Select Cover</span>
                </div>
            </label>
            {{ Form::file('cover', ['id' => 'cover-image', 'class' => ($errors->has('cover') ? 'is-invalid' : '')]) }}
            @error('cover')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </center>
        <div class="form-group" style="margin: 25px 0px;">
            {{ Form::label('story_title', 'Story Title') }}
            {{ Form::text('story_title', old('story_title'), [
                'class' => 'form-control'.($errors->has('story_title') ? ' is-invalid' : ''),
                'style' => 'background-color: #6D6E7D; color: #fff;'
            ]) }}
            @error('story_title')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            {{ Form::label('genres', 'Select Genre:') }} <br>
            {{ Form::select('genres[]', $genre_selects, null, [
                'multiple' => '',
                'class' => 'form-control',
                'id' => 'genres'
            ]) }}
            @error('genres')
                <input type="hidden" class="is-invalid" style="display:none;">
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group" style="margin: 20px 0px;">
            {{ Form::label('sinopsis', 'Synopsis') }}
            {{ Form::textarea('sinopsis', old('sinopsis'), [
                'class' => 'form-control'.($errors->has('sinopsis') ? ' is-invalid' : ''),
                'rows' => 4,
                'style' => 'background-color: #6D6E7D; color: #fff;'
            ]) }}
            @error('sinopsis')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <center>
            {{ Form::submit('Post', [
                'class' => 'btn btn-secondary btn-openread',
                'style' => 'background-color: #6D6E7D; width: 150px; margin-top: 50px;'
            ])}}
        </center>
    {{ Form::close() }}
</div>
@endsection

@section('script')
<script src="{{ asset('js/bootstrap-multiselect.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $("#cover-image").change(function() {
            readURL(this);
        });

        $('#genres').multiselect({
            includeSelectAllOption: true
        });
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                $('#cover-preview').css('background-image', `url(${e.target.result}`);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection