@extends('layouts.app')

@section('content')
<div class="content">
    <div class="text-white">
        <p class="title">{{ $genre['genre_type'] }} Stories</p>
        <div class="view-by-genre">
            <div>
                <p class="title2">Recent Stories</p>
            </div>
            @forelse ($recents as $story)
                <div style="display: flex;">
                    <img class="display-cover" src="{{ route('view-story-cover', ['name' => $story['cover'] ?? 'default']) }}" alt="">
                    <div style="margin: 5px 12px;">
                        <a class="display-title display-content text-white" href="{{ route('read-story', ['story_id' => $story['story_id']]) }}">{{ $story['title'] }}</a>
                        <div>
                            <span class="display-content">by {{ $story['author'] }}</span>
                            <img class="rate-view-icon display-content" src="{{ asset('assets/Star.svg.png') }}" alt="">
                            <span class="display-content">{{ sprintf("%.2f", $story['rating']) }}</span>
                            <img class="rate-view-icon display-content" src="{{ asset('assets/view.png') }}" alt="">
                            <span class="display-content">{{ $story['views'] }}</span>
                        </div>
                        <div class="display-content text-justify">{{ $story['synopsis'] }}</div>
                    </div>
                </div>
            @empty
                <div style="display: flex;">
                    <p class="display-title display-content text-center" style="width: 100%;">There are no stories to show</p>
                </div>
            @endforelse
        </div>

        <div class="view-by-genre">
            <div>
                <p class="title2">Most Viewed Stories</p>
            </div>
            @forelse ($most_viewed as $story)
                <div style="display: flex;">
                    <img class="display-cover" src="{{ route('view-story-cover', ['name' => $story['cover'] ?? 'default']) }}" alt="">
                    <div style="margin: 5px 12px;">
                        <a class="display-title display-content text-white" href="{{ route('read-story', ['story_id' => $story['story_id']]) }}">{{ $story['title'] }}</a>
                        <div>
                            <span class="display-content">by {{ $story['author'] }}</span>
                            <img class="rate-view-icon display-content" src="{{ asset('assets/Star.svg.png') }}" alt="">
                            <span class="display-content">{{ sprintf("%.2f", $story['rating']) }}</span>
                            <img class="rate-view-icon display-content" src="{{ asset('assets/view.png') }}" alt="">
                            <span class="display-content">{{ $story['views'] }}</span>
                        </div>
                        <div class="display-content text-justify">{{ $story['synopsis'] }}</div>
                    </div>
                </div>
            @empty
                <div style="display: flex;">
                    <p class="display-title display-content text-center" style="width: 100%;">There are no stories to show</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection