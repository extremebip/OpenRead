@extends('layouts.app')

@section('title', 'Edit Profile')
@section('style')
<style>
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
    .profile-pic {
        border-radius: 50%;
        height: 150px;
        width: 150px;
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
    .profile-pic:hover {
        background-color: rgba(0, 0, 0, .5);
        z-index: 10000;
        color: #fff;
        transition: all .3s ease;
        text-decoration: none;
    }
    .profile-pic span {
        display: inline-block;
        padding-top: 4.5em;
        padding-bottom: 4.5em;
    }
    form input[type="file"] {
        display: none;
        cursor: pointer;
    }
    .btn-openread{
        width: 100px;
    }
    @media only screen and (max-width: 575.98px){
        .btn-openread{
            width: 75px;
        }
    }
</style>
@endsection
@section('content')
<div class="content text-white">
    <p class="title">Change Profile</p>
    <div style="margin: 0% 12%;">
        <div>
            @php
                $imageUrlParam = ['name' => $user->profile_picture];
                if (!is_null(old('new_image')) && !$errors->has('new_image')){
                    $imageUrlParam['name'] = old('new_image');
                    $imageUrlParam['temp'] = 'true';
                }
                $imageUrl = route('preview-image-profile', $imageUrlParam);
            @endphp
            <center style="margin: 52px 0px 36px 0px;">
                {{ Form::open(['route' => 'save-edit-profile', 'files' => true, 'id' => 'image-form']) }}
                    {{ Form::hidden('username', Auth::id(), ['id' => 'image-username']) }}
                    <label for="image-file">
                        <div class="profile-pic" 
                            style="background-image: url({{ $imageUrl }});">
                            <span>Change Image</span>
                        </div>
                    </label>
                    {{ Form::file('image-file', ['id' => 'image-file', 'class' => 'form-control is-invalid']) }}
                    <span class="invalid-feedback" role="alert" id="validation-message-wrapper">
                        <strong id="validation-message">
                            @error('new_image') {{ $message }} @enderror
                        </strong>
                    </span>
                {{ Form::close() }}
            </center>
        </div>
        {{ Form::open(['route' => 'save-edit-profile']) }}
            <div>
                {{ Form::hidden('new_image', null, ['id' => 'new-image']) }}
                <h5>
                    <div style="margin: 40px 0px;">
                        {{ Form::label('name', 'Name') }}
                        {{ Form::text('name', old('name') ?? $user->name, ['class' => 'signup'.($errors->has('name') ? ' is-invalid': ''), 'autofocus']) }}
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div style="margin: 40px 0px;">
                        {{ Form::label('email', 'Email') }}
                        {{ Form::email('email', old('email') ?? $user->email, ['class' => 'signup'.($errors->has('email') ? ' is-invalid': '')]) }}
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </h5>
            </div>
            <center>
                <button type="submit" class="btn btn-secondary btn-openread" style="background-color: #6D6E7D;">Save</button>
                <a class="btn btn-secondary btn-openread" style="background-color: #6D6E7D;" href="{{ route('show-profile', ['u' => $user->username]) }}">Cancel</a>
            </center>
        {{ Form::close() }}
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function () {
        $('#image-file').change(function () {
            $('#image-form').submit();
        });

        $('#image-form').on('submit', function (e) {
            e.preventDefault();
            var fd = new FormData();
            var files = $('#image-file')[0].files;

            if (files.length > 0){
                fd.append('username', $('#image-username').val());
                fd.append('profile_picture', files[0]);

                $.ajax({
                    url: "{{route('upload-image-profile')}}",
                    type: "POST",
                    data: fd,
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend: function () {
                        ShowImageValidationMessage(true, 'Uploading...');
                    },
                    success: function (data) {
                        ShowImageValidationMessage(true, 'Image successfully uploaded');
                        $('#new-image').val(data.name);
                        $('.profile-pic').css('background-image', `url(${data.url})`);
                    },
                    error: function (e) {
                        if (e.status == 422){
                            ShowImageValidationMessage(false, e.responseJSON.errors.profile_picture[0]);
                        } else {
                            alert('An error has happened!');
                        }
                    },
                });
            }
        });
    });

    function ShowImageValidationMessage(success, message) {
        if (success){
            $('#image-file').removeClass('is-invalid');
            $('#image-file').addClass('is-valid');
            $('#validation-message-wrapper').removeClass('invalid-feedback');
            $('#validation-message-wrapper').addClass('valid-feedback');
        }
        else {
            $('#image-file').removeClass('is-valid');
            $('#image-file').addClass('is-invalid');
            $('#validation-message-wrapper').removeClass('valid-feedback');
            $('#validation-message-wrapper').addClass('invalid-feedback');
        }
        $('#validation-message').text(message);
    }
</script>
@endsection