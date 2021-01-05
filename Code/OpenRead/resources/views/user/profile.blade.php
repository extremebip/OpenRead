@extends('layouts.app')

@section('title', $userData['user']->username.'\'s Profile')
@section('style')
<style>
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
        font-size: 30px;
    }

    h4 {
        color: white;
        text-align: left;
        font-size: 20px;
    }

    .content2 {
        padding-top: 0%;
        padding-bottom: 8%;
        padding-left: 8%;
        padding-right: 8%;
    }

    .content3 {
        padding-top: 0%;
        padding-bottom: 0%;
        padding-left: 8%;
        padding-right: 8%;
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

    .view-by-genre {
        margin-bottom: 1%;
    }

    @media only screen and (max-width: 575.98px){
        .display-cover {
            width: 75px;
            height: 75px;
        }

        .display-title {
            font-size: 20px;
        }

        .view-by-genre {
            margin-bottom: 7%;
        }

        .btn-wrapper {
            text-align: center;
        }

        .btn-openread {
            margin-bottom: 2%;
        }
    }
</style>
@endsection
@section('content')
<div class="content text-white">
    <center>
        <br><br>
        <div class="profile-pic" style="background-image: url({{ route('preview-image-profile', ['name' => $userData['user']->profile_picture]) }})"></div>
    </center>
    <h2>
        <br><label for="name">{{ $userData['user']->name }}</label><br>
        <label for="username">{{ $userData['user']->username }}</label><br>
    </h2>
    <div class="content3">
        <div class="view-by-genre">
            <h4>
                Email : {{ $userData['user']->email }}<br>
                <br>
                Date of Birth : {{ $userData['user']->date_of_birth->toFormattedDateString() }}<br>
            </h4>
        </div>
        @if ($canEdit)
        <div class="btn-wrapper">
            <a class="btn btn-secondary btn-openread" href="{{ route('change-password') }}">Change Password</a>
            <a class="btn btn-secondary btn-openread" href="{{ route('show-edit-profile') }}">Edit Profile</a>
        </div>
        @endif
    </div>
    <div class="content2">
        <div class="view-by-genre text-white">
            <div><p class="title2">Recent Stories</p></div>
            <div class="container">
                @forelse ($userData['stories'] as $story)
                    <div class="row">
                        <div class="col-md-12" style="display: inline-flex;">
                            <img class="display-cover" src="{{ route('view-story-cover', ['name' => $story['cover'] ?? 'default']) }}" alt="">
                            <div>
                                <p class="display-title display-content">{{ $story['title'] }}</p>
                                <div>
                                    <img class="rate-view-icon display-content" src="assets/Star.svg.png" alt="">
                                    <p3 class="display-content">{{ sprintf("%.2f", $story['rate']) }}</p3>
                                    <img class="rate-view-icon display-content" src="assets/view.png" alt="">
                                    <p3 class="display-content">{{ $story['views'] }}</p3>
                                </div>
                                <p4 class="text-break">{{ $story['synopsis'] }}</p4>
                            </div>
                        </div>
                    </div>
                @empty
                    @if ($canEdit)
                        <p>You haven't created any stories</p>
                    @else
                        <p>This user hasn't created any stories</p>
                    @endif
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection