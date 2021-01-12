@extends('layouts.app')

@section('style')
<style>
    .box2 {
        background-color: #3A3B44;
        margin: 32px 20px;
        padding: 20px 20px;
        border-radius: 12px;
    }
    .display-pp{
        width: 64px;
        height: 64px;
        border-radius: 32px;
        margin: 6px 24px 6px 0px;
    }
    .title {
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }
    .page-item > a {
        background-color: #3A3B44;
        color: white;
    }
    .page-item.active > .page-link{
        cursor: auto;
    }
</style>
@endsection

@php
    if (isset($pagination)){
        $disablePrev = ($pagination['currentPage'] == 1);
        $disableNext = ($pagination['currentPage'] == $pagination['totalPage']);

        $prevPage = max($pagination['currentPage'] - 1, 1);
        $nextPage = min($pagination['totalPage'], $pagination['currentPage'] + 1);

        $prevUrl = route('search', ['q' => request()->query('q'), 'p' => $prevPage]);
        $nextUrl = route('search', ['q' => request()->query('q'), 'p' => $nextPage]);

        $showPrevEllipsis = ($pagination['currentPage'] - 1 > 3);
        $showNextEllipsis = ($pagination['totalPage'] - $pagination['currentPage'] > 3);

        $pageItems = [];
        for ($i = 1; $i < $pagination['currentPage']; $i++) { 
            if ($i != 1 && $i != ($pagination['currentPage'] - 1) && !$showPrevEllipsis)
                continue;
            array_push($pageItems, $i);
        }
        if ($showPrevEllipsis){
            $temp = count($pageItems);
            $pageItems[$temp - 1] = "...";
            array_push($pageItems, $pagination['currentPage'] - 1);
        }
        array_push($pageItems, $pagination['currentPage']);
        for ($i = $pagination['currentPage'] + 1; $i <= $pagination['totalPage']; $i++) { 
            if ($i != $pagination['totalPage'] && $i != ($pagination['currentPage'] + 1) && !$showNextEllipsis)
                continue;
            array_push($pageItems, $i);
        }
        if ($showNextEllipsis){
            $temp = array_search($pagination['currentPage'] + 1, $pageItems);
            $firstSlice = array_slice($pageItems, 0, $temp + 1);
            $secondSlice = array_slice($pageItems, $temp, count($pageItems) - count($firstSlice));
            array_push($firstSlice, "...", $secondSlice);
            $pageItems = $firstSlice;
        }
    }
@endphp

@section('content')
<div class="content text-white container">
    <p class="title">Search Result : {{ request()->query('q') }}</p>
    <div class="row">
        <div class="col-md-12">
            @forelse ($stories as $story)
                <div class="box2" style="display: flex;">
                    <img class="display-cover" src="{{ route('view-story-cover', ['name' => $story['cover'] ?? 'default']) }}" alt="">
                    <div style="margin: 5px 15px;">
                        <a href="{{ route('read-story', ['story_id' => $story['story_id']]) }}" class="display-title text-white">{{ $story['title'] }}</a>
                        <div>
                            <span>by {{ $story['author'] }}</span>
                            <img class="rate-view-icon display-content" src="{{ asset('assets/Star.svg.png') }}" alt="">
                            <span class="display-content">{{ sprintf("%.2f", $story['rating']) }}</span>
                            <img class="rate-view-icon display-content" src="{{ asset('assets/view.png') }}" alt="">
                            <span class="display-content">{{ $story['views'] }}</span>
                        </div>
                        <div class="text-justify">{{ $story['synopsis'] }}</div>
                    </div>
                </div>
            @empty
                <div class="box2" style="display: flex;">There is no story to show  </div>
            @endforelse

            @isset($pagination)
                <div class="d-flex align-items-end flex-column">
                    <p>
                        Showing {{ $pagination['startItem'] }} 
                        to {{ $pagination['endItem'] }} 
                        of {{ $pagination['totalItem'] }}
                    </p>
                    <nav aria-label="Search Story Pagination">
                        <ul class="pagination">
                            <li class="page-item @if ($disablePrev){{'disabled'}}@endif">
                                <a href="{{ $prevUrl }}" class="page-link" aria-label="Previous" @if ($disablePrev) tabindex="-1" aria-disabled="true" @endif>
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            @foreach ($pageItems as $page)
                                @if (intval($page) == $pagination['currentPage'])
                                    <li class="page-item active" aria-current="page">
                                        <span class="page-link">
                                            {{ $page }}
                                            <span class="sr-only">(current)</span>
                                        </span>
                                    </li>
                                @elseif ($page == "...")
                                    <li class="page-item">
                                        <spam class="page-link">...</spam>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a href="{{ route('search', ['q' => request()->query('q'), 'p' => $page]) }}" class="page-link">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach
                            <li class="page-item @if ($disableNext){{'disabled'}}@endif">
                                <a href="{{ $nextUrl }}" class="page-link" aria-label="Next" @if ($disableNext) tabindex="-1" aria-disabled="true" @endif>
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            @endisset
        </div>
        @if (count($users) > 0)
            <div class="col-md-12">
                <p class="display-title display-content">Profile</p>
                @foreach ($users as $user)
                    <div class="box2" style="display: flex;">
                        <img class="display-pp" src="{{ route('preview-image-profile', ['name' => $user['profile_picture']]) }}" alt="">
                        <div>
                            <a href="{{ route('show-profile', ['u' => $user['username']]) }}" class="text-white" style="font-size: 24px; margin: 5px 0px;">{{ $user['name'] }}</a>
                            <p style="font-size: 14px; margin: 5px 0px;">{{ $user['username'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection