@extends('layouts.app')

@php
    if ($create){
        $chapter = [
            'chapter_title' => '',
            'content' => ''
        ];
    }
@endphp

@if (!$create)
@section('style')
<style>
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
@endsection
@endif

@section('content')
<div class="content text-white">
    <p class="title">{{ $story['story_title'] }}</p>
    {{ Form::open(['route' => 'save-chapter', 'style' => 'margin: 0px 2% 20px 2%;']) }}
        @if (!$create)
            {{ Form::hidden('chapter_id', $chapter['chapter_id']) }}
        @endif
        {{ Form::hidden('story_id', $story['story_id']) }}
        <div class="form-group">
            {{ Form::label('chapter_title', 'Chapter Title') }}
            {{ Form::text('chapter_title', old('chapter_title') ?? $chapter['chapter_title'], [
                'class' => 'form-control'.($errors->has('chapter_title') ? ' is-invalid' : ''),
                'style' => 'background-color: #6D6E7D; color: #fff;'
            ]) }}
            @error('chapter_title')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <div class="form-check">
                {{ Form::checkbox('last_chapter', "Yes", old('last_chapter') ?? false, [
                    'class' => 'form-check-input'.($errors->has('last_chapter') ? ' is-invalid' : '')
                ]) }}
                {{ Form::label('last_chapter', 'Set as last chapter') }}
                @error('last_chapter')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('content', 'Write your story') }}
            {{ Form::textarea('content', old('content') ?? $chapter['content'], [
                'class' => 'form-control'.($errors->has('content') ? ' is-invalid' : ''),
                'style' => 'background-color: #6D6E7D; color: #fff;',
                'rows' => '24'
            ]) }}
            @error('content')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <center>
                <button type="submit" class="btn btn-secondary btn-openread" style="background-color: #6D6E7D; width: 150px; margin-top: 50px;">
                    Post
                </button>
            </center>
        </div>
    {{ Form::close() }}

    @if (!$create)
        <button data-target="#deleteModal" type="button" class="fixed-plugin" data-toggle="modal">
            <img style="width: 32px;" src="{{ asset('assets/trash.png') }}" alt="">
        </button>

        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
            aria-hidden="true" style="height: 80vh; margin-top: 10vh;">
            <div class="modal-dialog" role="document">
                <div class="modal-content text-white" style="background-color: #6D6E7D;">
                    <div class="modal-header" style="background-color: #3A3B44;">
                        <h5 class="modal-title" id="deleteModalLabel">Delete Chapter</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <p class="display-content text-break text-justify">Are you sure you want to delete this chapter?</p>
                        </form>
                    </div>
                    <div class="modal-footer" style="background-color: #3A3B44;">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        {{ Form::open(['route' => 'delete-chapter']) }}
                            {{ Form::hidden('chapter_id', $chapter['chapter_id']) }}
                            <button type="submit" class="btn btn-secondary">Yes</button>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection