@extends('layouts.app')

@section('title', $userData['user']->username.'\'s Profile')
@section('style')
<style>
    .content {
        color: #fff!important;
    }
    h3 {
        color: white;
        text-align: center;
        margin-top: 4%;
        margin-bottom: 4%;
    }
    h1 {
        color: white;
        font-size: 46px;
        text-align: center;
        margin-bottom: 1%;
    }
    h2 {
        color: white;
        text-align: center;
        font-size: medium;
        font-size : 30px;
    }
    h4 {
        color: white;
        text-align: left;
        font-size : 20px;
    }
    .content2
    {
       padding-top : 0%;
       padding-bottom : 8%;
       padding-left : 8%;
       padding-right : 8%;
       font-size: 16px;
    }
    .content3
    {
       padding-top : 0%;
       padding-bottom : 0%;
       padding-left : 8%;
       padding-right : 8%;
       font-size: 16px;
    }
    .btn-secondary
    {
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
    .profile-pic span {
        display: inline-block;
        padding-top: 4.5em;
        padding-bottom: 4.5em;
    }
    .view-by-genre2{
        background-color: #3A3B44;
        margin: 2% 1%;
        padding: 4% 3%;
        border-radius: 10px;
    }
</style>
@endsection
@section('content')
<div class="content">
    <center>
        <br><br>
        <div class="profile-pic" style="background-image: url({{ route('preview-image-profile', ['name' => $userData['user']->profile_picture]) }})"></div>
    </center>
    <h2>
        <br><label for="name">{{ $userData['user']->name }}</label><br>
        <label for="username">{{ $userData['user']->username }}</label><br>
    </h2>
    <div class="content3">
        <div class="view-by-genre2">
            <h4>
                Email : {{ $userData['user']->email }}<br>
                <br>
                Date of Birth : {{ $userData['user']->date_of_birth->toFormattedDateString() }}<br>
            </h4>
        </div>
        @if ($canEdit)
        <a class="btn btn-secondary btn-openread" href="{{ route('change-password') }}">Change Password</a>
        <a class="btn btn-secondary btn-openread" href="{{ route('show-edit-profile') }}">Edit Profile</a>
        @endif
    </div>
    <div class="content2">
        <div class="view-by-genre text-white">
            <div><p class="title2">Recent Stories</p></div>
            @forelse ($userData['stories'] as $story)
            <tr>
                <div class="display-story">
                    <img src="assets/homepage.png" alt="" class="display-cover">
                    <div>
                        <p class="display-title">{{ $story['title'] }}</p>
                        <div>
                            <img class="rate-view-icon display-content" src="assets/Star.svg.png" alt="">
                            <p3 class="display-content">{{ sprintf("%.2f", $story['rate']) }}</p3>
                            <img class="rate-view-icon display-content" src="assets/view.png" alt="">
                            <p3 class="display-content">{{ $story['views'] }}</p3>
                        </div>
                        <p4 class="display-content text-break">
                            {{ $story['synopsis'] }}
                        </p4>
                    </div>
                </div>
            </tr>
            @empty
                @if ($canEdit)
                    <p>You haven't created any stories</p>
                @else
                    <p>This user haven't created any stories</p>
                @endif
            @endforelse
        </div>
    </div>
</div>
@endsection