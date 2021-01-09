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
                    <div class="col-md-6 col-xs-12 box2" style="display: flex;">
                        <img class="display-cover" src="{{ route('view-story-cover', ['name' => $story['cover'] ?? 'default']) }}" alt="">
                        <div>
                            <p class="display-title display-content">{{ $story['title'] }}</p>
                            <div class="display-content">
                                <span class="display-content">by {{ $story['author'] }}</span>
                                <img class="rate-view-icon display-content" src="{{ asset('assets/Star.svg.png') }}" alt="">
                                <span class="display-content">{{ sprintf("%.2f", $story['rating']) }}</span>
                                <img class="rate-view-icon display-content" src="{{ asset('assets/view.png') }}" alt="">
                                <span class="display-content">{{ $story['views'] }}</span>
                            </div>
                            <span class="text-break text">{{ $story['synopsis'] }}</span>
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
