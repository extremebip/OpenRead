@extends('layouts.app')

@section('style')
<style>
    .box2 {
        background-color: #62636b;
        margin: 2% 1%;
        padding: 3% 4.5%;
        border-radius: 10px;
    }

    .fixed-plugin {
        background-color: transparent;
        border-color: transparent;
        position: fixed;
        width: 100px;
        right: 18px;
        z-index: 1031;
        bottom: 40px;
    }

    .display-pp {
        width: 100px;
        height: 100px;
        border-radius: 10px;
        margin: 14px;
    }

    .comment-box {
        resize: vertical;
        height: 45vh;
    }

    .overflow-auto {
        overflow: auto!important;
    }
</style>
@endsection

@php
    $currentUsername = Auth::id();
@endphp

@section('content')
<div class="content text-white container">
    <div class="row justify-content-between" style="display: flex;">
        <div class="col-12 col-sm-10">
            <p class="display-title display-content" style="font-size: 56px;">
                {{ $result['story']['title'] }}
            </p>
        </div>
        <div class="col-12 col-sm-auto">
            <a href="{{ route('read-story', ['story_id' => $result['story']['story_id']]) }}" class="btn btn-secondary align-middle" style="height: fit-content;">Story Page</a>
        </div>
    </div>
    <div class="row">
        <p class="display-title display-content" style="margin-left: 24px; font-size: 32px;">
            Chapter {{ $result['chapter']['index'].' : '.$result['chapter']['title'] }}
        </p>
    </div>
    <div class="row">
        <p class="text-white box2 text-justify" style="font-size: 20px; line-height: 2;">
            {{ $result['chapter']['content'] }}
        </p>
    </div>
</div>

@auth
<button data-target="#commentModal" type="button" class="fixed-plugin" data-toggle="modal">
    <img style="width: 80px;" src="{{ asset('assets/comment.png') }}" alt="">
</button>

<div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true" style="height: 80vh; margin-top: 10vh;">
    <div class="modal-dialog" role="document">
        <div class="modal-content text-white" style="background-color: #6D6E7D;">
            <div class="modal-header" style="background-color: #3A3B44;">
                <h5 class="modal-title" id="commentModalLabel">Comment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="overflow-auto comment-box" style="width: 100%;">
                    @forelse ($comments as $comment)
                    <div class="display-story" style="display: inline-flex; margin:2% 4% 2% 2%; width: 90%;">
                        <img class="display-pp" src="{{ route('preview-image-profile', ['name' => $comment['profile_picture']]) }}" alt="">
                        <div style="width: 100%;">
                            @if ($currentUsername == $comment['username'])
                            <div class="justify-content-between" style="display: flex;">
                                <p class="display-title display-content">{{ $comment['username'] }}</p>
                                <button style="width:fit-content; height:fit-content" onclick="toggleEdit($(this));">
                                    <img src="{{ asset('assets/edit_icon.png') }}" alt="" style="width: 16px; height: 16px;">
                                </button>
                            </div>
                            @else
                            <p class="display-title display-content">{{ $comment['username'] }}</p>
                            @endif
                            
                            <p class="display-content text-break text-justify content-text">{{ $comment['content'] }}</p>
                            @if ($currentUsername == $comment['username'])
                            {{ Form::open(['route' => 'save-comment', 'style' => 'display: none;']) }}
                                {{ Form::hidden('comment_id', $comment['comment_id']) }}
                                {{ Form::hidden('chapter_id', $result['chapter']['chapter_id']) }}
                                <textarea class="form-control content-edit" rows="3" cols="50" style="background-color:#474852; color: #fff; width: 100%;" name="content">{{ $comment['content'] }}</textarea>
                                <span class="invalid-feedback edit-comment-err-wrapper" role="alert">
                                    <strong class="edit-comment-err-text"></strong>
                                </span>
                                <center>
                                    <button type="button" class="btn btn-secondary" style="width: 82px; margin: 6px; padding: 0px; background-color: #474852;" onclick="toggleEdit($(this), true);">Cancel</button>
                                    <button type="button" class="btn btn-secondary" style="width: 82px; margin: 6px; padding: 0px; background-color: #474852;" onclick="updateComment(this);">Save</button>
                                </center>
                            {{ Form::close() }}
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="display-story" style="display: inline-flex; margin:12px 20px 12px 10px" id="no-comment">
                        <p class="display-title display-content">There are no comments to show</p>
                    </div>
                    @endforelse
                    @if ($has_more)
                    <center id="show-more-wrapper">
                        <button type="button" class="btn btn-secondary btn-sm" style="width: 128px; background-color: #3A3B44; padding-left: 20px;" id="btn-show-more" onclick="getMoreComments();">
                            Show More 
                            <img src="{{ asset('assets/expand_more-white.svg') }}" alt="">
                        </button>
                        <p class="display-content" id="show-more-loading-text" style="display: none;">Getting comments...</p>
                    </center>
                    @endif
                </div>
                {{ Form::open(['route' => 'save-comment', 'id' => 'create-comment-form']) }}
                    {{ Form::hidden('chapter_id', $result['chapter']['chapter_id']) }}
                    <div class="form-group">
                        {{ Form::label('content', 'Message :', ['class' => 'col-form-label']) }}
                        {{ Form::textarea('content', old('content'), [
                            'class' => 'form-control',
                            'rows' => 2,
                            'id' => 'create-comment-textarea'
                        ]) }}
                        <span class="invalid-feedback" role="alert" id="create-comment-err-wrapper">
                            <strong id="create-comment-err-text"></strong>
                        </span>
                    </div>
                {{ Form::close() }}
            </div>
            <div class="modal-footer" style="background-color: #3A3B44;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-secondary" id="create-comment-button"
                    onclick="storeComment('create-comment-form');">
                        Send message
                </button>
            </div>
        </div>
    </div>
</div>

<div class="display-story" style="display: none; margin:2% 4% 2% 2%; width: 90%;" id="comment-template">
    <img class="display-pp" id="comment-pp-template" src="{{ asset('assets/default.png') }}" alt="">
    <div style="width: 100%;">
        <div class="justify-content-between" style="display: flex;">
            <p class="display-title display-content" id="comment-author-template"></p>
            <button style="width:fit-content; height:fit-content" onclick="toggleEdit($(this));">
                <img src="{{ asset('assets/edit_icon.png') }}" alt="" style="width: 16px; height: 16px;">
            </button>
        </div>
        <p class="display-content text-break text-justify content-text" id="comment-content-template"></p>
        {{ Form::open(['route' => 'save-comment', 'style' => 'display: none;']) }}
            {{ Form::hidden('comment_id', null, ['id' => 'comment-id-template']) }}
            {{ Form::hidden('chapter_id', $result['chapter']['chapter_id']) }}
            <textarea class="form-control content-edit" id="comment-textarea-template" rows="3" cols="50" style="background-color:#474852; color: #fff; width: 100%;" name="content"></textarea>
            <span class="invalid-feedback edit-comment-err-wrapper" role="alert">
                <strong class="edit-comment-err-text"></strong>
            </span>
            <center>
                <button type="button" class="btn btn-secondary" style="width: 82px; margin: 6px; padding: 0px; background-color: #474852;" onclick="toggleEdit($(this), true);">Cancel</button>
                <button type="button" class="btn btn-secondary" style="width: 82px; margin: 6px; padding: 0px; background-color: #474852;" onclick="updateComment(this);">Save</button>
            </center>
        {{ Form::close() }}
    </div>
</div>

<div class="display-story" style="display: none; margin:2% 4% 2% 2%; width: 90%;" id="diff-comment-template">
    <img class="display-pp" id="diff-comment-pp-template" src="{{ asset('assets/default.png') }}" alt="">
    <div style="width: 100%;">
        <p class="display-title display-content" id="diff-comment-author-template"></p>
        <p class="display-content text-break text-justify content-text" id="diff-comment-content-template"></p>
    </div>
</div>
@endauth

@endsection

@section('script')
<script>
    var currentPage = 2;
    $(document).ready(function () {

    });

    function storeComment(formId) {
        var form = document.getElementById(formId);
        var fd = new FormData(form);
        
        $.ajax({
            url: "{{route('save-comment')}}",
            type: "POST",
            data: fd,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function(){
                ShowCreateCommentMessage(true, 'Posting Comment..');
                $('#create-comment-button').prop('disabled', true);
            },
            success: function(data){
                ShowCreateCommentMessage(true, 'Posting success. Your new comment will be shown on top of the list.');
                $('#create-comment-textarea').val('');
                $('#no-comment').remove();
                prependNewComment(data.comment);
                setTimeout(function () {
                    $('#create-comment-err-text').text('');
                    $('#create-comment-textarea').removeClass('is-valid');
                    $('#create-comment-button').prop('disabled', false);
                }, 4000);
            },
            error: function(e){
                if (e.status == 422){
                    var errObject = e.responseJSON.errors;
                    var firstProperty = errObject[Object.keys(errObject)[0]];
                    ShowCreateCommentMessage(false, firstProperty[0]);
                    $('#create-comment-button').prop('disabled', false);
                } else {
                    alert('An error has happened!');
                }
            }
        });
    }

    function prependNewComment(data) {
        var template = fillSameCommentTemplate(data);
        template.prependTo('.comment-box');
        template.css('display', 'inline-flex');
    }

    function fillSameCommentTemplate(data) {
        var template = $('#comment-template').clone();
        var profilePic = template.find('#comment-pp-template');
        var author = template.find('#comment-author-template');
        var content = template.find('#comment-content-template');
        var commentId = template.find('#comment-id-template');
        var textarea = template.find('#comment-textarea-template');

        profilePic.prop('src', data.photo_url);
        author.text(data.username);
        content.text(data.content);
        commentId.val(data.comment_id);
        textarea.val(data.content);

        template.removeAttr('id');
        profilePic.removeAttr('id');
        author.removeAttr('id');
        content.removeAttr('id');
        commentId.removeAttr('id');
        textarea.removeAttr('id');

        return template;
    }

    function fillDiffCommentTemplate(data) {
        var template = $('#diff-comment-template').clone();
        var profilePic = template.find('#diff-comment-pp-template');
        var author = template.find('#diff-comment-author-template');
        var content = template.find('#diff-comment-content-template');

        profilePic.prop('src', data.photo_url);
        author.text(data.username);
        content.text(data.content);

        template.removeAttr('id');
        profilePic.removeAttr('id');
        author.removeAttr('id');
        content.removeAttr('id');

        return template;
    }

    function appendNewComment(data, sameUsername) {
        var template;
        if (sameUsername){
            template = fillSameCommentTemplate(data);
        }
        else {
            template = fillDiffCommentTemplate(data);
        }
        template.appendTo('.comment-box');
        template.css('display', 'inline-flex');
    }

    function toggleEdit(btn, cancel = false) {
        var storyWrapper = btn.parents('.display-story');
        var formObj = storyWrapper.find('form');
        if (cancel){
            storyWrapper.find('.content-text').show();
            formObj.hide();
        }
        else {
            storyWrapper.find('.content-text').toggle();
            formObj.toggle();
        }

        ResetEditCommentValidation(formObj);
    }

    function updateComment(btn) {
        var fd = new FormData(btn.form);
        var formObj = $(btn.form);
        $.ajax({
            url: "{{ route('save-comment') }}",
            type: "POST",
            data: fd,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function(){
                ShowEditCommentMessage(formObj, true, 'Updating Comment');
                $(btn).prop('disabled', true);
            },
            success: function(data){
                var storyWrapper = $(btn).parents('.display-story');
                storyWrapper.find('.content-text').text(data.comment.content);
                storyWrapper.find('.content-edit').text(data.comment.content);
                toggleEdit($(btn));
                $(btn).prop('disabled', false);
            },
            error: function(e){
                if (e.status == 422){
                    var errObject = e.responseJSON.errors;
                    var firstProperty = errObject[Object.keys(errObject)[0]];
                    ShowEditCommentMessage(formObj, false, firstProperty[0]);
                    $(btn).prop('disabled', false);
                } else {
                    alert('An error has happened!');
                }
            }
        });
    }

    function ShowCreateCommentMessage(success, message) {
        if (success){
            $('#create-comment-textarea').removeClass('is-invalid');
            $('#create-comment-textarea').addClass('is-valid');
            $('#create-comment-err-wrapper').removeClass('invalid-feedback');
            $('#create-comment-err-wrapper').addClass('valid-feedback');
        }
        else {
            $('#create-comment-textarea').removeClass('is-valid');
            $('#create-comment-textarea').addClass('is-invalid');
            $('#create-comment-err-wrapper').removeClass('valid-feedback');
            $('#create-comment-err-wrapper').addClass('invalid-feedback');
        }
        $('#create-comment-err-text').text(message);
    }

    function ShowEditCommentMessage(form, success, message) {
        if (success){
            form.find('.content-edit').removeClass('is-invalid');
            form.find('.content-edit').addClass('is-valid');
            form.find('.edit-comment-err-wrapper').removeClass('invalid-feedback');
            form.find('.edit-comment-err-wrapper').addClass('valid-feedback');
        }
        else {
            form.find('.content-edit').removeClass('is-valid');
            form.find('.content-edit').addClass('is-invalid');
            form.find('.edit-comment-err-wrapper').removeClass('valid-feedback');
            form.find('.edit-comment-err-wrapper').addClass('invalid-feedback');
        }
        form.find('.edit-comment-err-text').text(message);
    }

    function ResetEditCommentValidation(form) {
        form.find('.content-edit').removeClass('is-invalid');
        form.find('.content-edit').removeClass('is-valid');
        form.find('.edit-comment-err-text').text('');
    }

    function getMoreComments() {
        $.ajax({
            url: "{{ route('get-comment') }}",
            type: "get",
            data: { p : currentPage, c : '{{ $result['chapter']['chapter_id'] }}' },
            cache: false,
            beforeSend: function () {
                $('#btn-show-more').hide();
                $('#show-more-loading-text').show();
            },
            success: function (data) {
                var result = data.result;
                if (result.success){
                    result.comments.forEach(cmt => {
                        appendNewComment(cmt, "{{ Auth::id() }}" === cmt.username);
                    });
                    if (result.has_more){
                        $('#btn-show-more').show();
                        currentPage++;
                    }
                }
            },
            error: function (e) {
                alert('An error has happened!');
            },
            complete: function () {
                $('#show-more-wrapper').appendTo('.comment-box');
                $('#show-more-loading-text').hide();
            }
        })
    }
</script>
@endsection