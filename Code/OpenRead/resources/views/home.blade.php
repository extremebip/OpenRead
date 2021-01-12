@extends('layouts.app')

@section('style')
<style>
    .box2 {
        background-color: #3A3B44;
        margin: 32px 20px;
        padding: 20px 20px;
        border-radius: 12px;
    }
</style>
@endsection

@section('content')
<div>
    <img class="homepage" src="{{ asset('assets/homepage.png') }}">
    <div class="visitor-homepage">
        <p class="text-white openread">OpenRead</p>
        <p class="text-white slogan">Easy way to share your stories</p>
        @guest
            <a class="btn btn-secondary btn-openread" href="{{ route('register') }}">Sign Up</a>
            <a class="btn btn-secondary btn-openread" href="{{ route('login') }}">Login</a>
        @endguest
    </div>
</div>

<div class="content text-white">
    <p class="title">Top Picks</p>
    <div class="container">
        @forelse ($topPicks->chunk(2) as $storyChunk)
            <div class="row">
                @foreach ($storyChunk as $story)
                    <div class="col box2 container" style="display: inline-flex;">
                        <div class="row">
                            <div class="col col-md-auto">
                                <img class="display-cover" src="{{ route('view-story-cover', ['name' => $story['cover'] ?? 'default']) }}" alt="">
                            </div>
                            <div class="col" style="min-width:220px">
                                <a class="display-title text-white" href="{{ route('read-story', ['story_id' => $story['story_id']]) }}">{{ $story['title'] }}</a>
                                <div>
                                    <span>by {{ $story['author'] }}</span>
                                    <img class="rate-view-icon display-content" src="{{ asset('assets/Star.svg.png') }}" alt="">
                                    <span class="display-content">{{ sprintf("%.2f", $story['rating']) }}</span>
                                    <img class="rate-view-icon display-content" src="{{ asset('assets/view.png') }}" alt="">
                                    <span class="display-content">{{ $story['views'] }}</span>
                                </div>
                                <div class="text-justify" >{{ $story['synopsis'] }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @empty
            <p class="display-title display-content text-center" style="width: 100%;">There are no stories to show</p>
        @endforelse
    </div>
</div>
@endsection
