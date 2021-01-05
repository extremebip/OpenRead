@extends('layouts.app')

@php
    if ($create){
        $story = [
            'cover' => null,
            'story_title' => '',
            'genres' => null,
            'sinopsis' => ''
        ];
    }
    $backUrl = route('write-menu');
    if (!$create){
        $backUrl = route('choose-story', ['for' => 'edit']);
    }
@endphp

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
@if (!$create)
<style>
    .box2 {
        background-color: #3A3B44;
        margin: 2% 1%;
        padding: 3% 4.5%;
        border-radius: 10px;
    }

    .fixed-plugin {
        background-color: transparent;
        border-color: transparent;
        position: fixed;
        width: 50px;
        right: 32px;
        z-index: 1031;
        bottom: 40px;
        cursor: pointer;
    }
</style>
@endif

@endsection

@section('content')
<div class="content text-white">
    {{-- <a href="{{ $backUrl }}" class="btn btn-secondary btn-openread" style="background-color: #6D6E7D; width: 150px; margin-top: 50px;">Go Back</a> --}}
    {{ Form::open(['route' => 'save-story', 'files' => true, 'style' => 'margin: 0px 2% 20px 2%;']) }}
        @if (!$create)
            {{ Form::hidden('story_id', $story['story_id']) }}
        @endif
        <center class="form-group">
            <label for="cover-image">
                <div class="cover-story" style="background-image: url('{{ route('view-story-cover', ['name' => $story['cover'] ?? 'default']) }}')" id="cover-preview">
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
            {{ Form::text('story_title', old('story_title') ?? $story['story_title'], [
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
            {{ Form::select('genres[]', $genre_selects, $story['genres'], [
                'multiple' => '',
                'class' => 'form-control',
                'id' => 'genres'
            ]) }}
            @error('genres')
                <input type="text" class="form-control is-invalid" style="display:none;">
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group" style="margin: 20px 0px;">
            {{ Form::label('sinopsis', 'Synopsis') }}
            {{ Form::textarea('sinopsis', old('sinopsis') ?? $story['sinopsis'], [
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
    @if (!$create)
    <div style="margin: 5% 0;">
        <h1 style="margin : 0% 2%">Chapter List</h1>
        <div class="box2 list-group list-group-flush" style="font-size: 21px; line-height: 2;">
            @forelse ($chapters as $chapter)
                <a href="{{ route('edit-chapter', ['chapter_id' => $chapter['chapter_id']]) }}" class="list-group-item list-group-item-action text-white" style="background-color:#3A3B44;">Chapter {{ $chapter['index'].' : '.$chapter['title'] }}</a>
            @empty
                <div class="list-group-item list-group-item-action text-white" style="background-color:#3A3B44;">There is no chapter to show</div>
            @endforelse
        </div>
    </div>

    <button data-target="#deleteModal" type="button" class="fixed-plugin" data-toggle="modal">
        <img style="width: 32px;" src="{{ asset('assets/trash.png') }}" alt="">
    </button>

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true" style="height: 80vh; margin-top: 10vh;">
        <div class="modal-dialog" role="document">
            <div class="modal-content text-white" style="background-color: #6D6E7D;">
                <div class="modal-header" style="background-color: #3A3B44;">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Story</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <p class="display-content text-break text-justify">Are you sure you want to delete this story?</p>
                    </form>
                </div>
                <div class="modal-footer" style="background-color: #3A3B44;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    {{ Form::open(['route' => 'delete-story']) }}
                        {{ Form::hidden('story_id', $story['story_id']) }}
                        <button type="submit" class="btn btn-secondary">Yes</button>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    @endif
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