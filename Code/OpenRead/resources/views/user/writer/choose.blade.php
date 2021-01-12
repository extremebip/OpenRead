@extends('layouts.app')

@php
    $title = ($for === 'chapter') ? 'Add Chapter' : 'Edit Story';
    $desc = ($for === 'chapter') ? 'add chapter' : 'edit';
    $route = 'write-menu';
    $param = 's';
    if ($for === 'chapter'){
        $route = 'create-chapter';
    }
    if ($for === 'edit'){
        $route = 'edit-story';
        $param = 'story_id';
    }
@endphp

@section('title', $title)

@section('style')
<style>
    .button-to-edit{
        background-color: #6D6E7D;
        color: white;
        border-radius: 28px;
        margin: 12px;
    }

    table {
        table-layout: fixed;
    }
</style>
@endsection

@section('content')
<div class="content text-white">
    <p class="title">{{ $title }}</p>
    <p style="font-size: 36px; text-align: center;">Please select the story you want to {{ $desc }}</p>
    <table class="container" style="margin: 80px 0px;">
        @forelse ($stories->chunk(2) as $storyChunk)
        <div class="row">
            @foreach ($storyChunk as $story)
            <div class="col container">
                <button class="row button-to-edit" style="margin: 20px 10px;" onclick="redirect('{{ route($route, [$param => $story['story_id']]) }}');" style="width:95%;">
                    <div class="col col-md-auto"> 
                        <img class="display-cover" src="{{ route('view-story-cover', ['name' => $story['cover'] ?? 'default']) }}" alt="">
                    </div>
                    <div class="col" style="min-width:220px">
                        <p class="display-title">{{ $story['title'] }}</p>
                        <div>
                            <img class="rate-view-icon" src="{{ asset('assets/Star.svg.png') }}" alt="">
                            <span class="display-content">{{ sprintf("%.2f", $story['rate']) }}</span>
                            <img class="rate-view-icon display-content" src="{{ asset('assets/view.png') }}" alt="">
                            <span class="display-content">{{ $story['views'] }}</span>
                        </div>
                        <div class="justify-text">{{ $story['synopsis'] }}</div>
                    </div>
                </button>
            </div>
            @endforeach
        </div>
        @empty
            <p style="font-size: 36px; text-align: center;">You haven't written any story yet</p>
        @endforelse
    </table>
</div>
@endsection

@section('script')
<script>
    function redirect(url) {
        window.location.href = url;
    }
</script>
@endsection