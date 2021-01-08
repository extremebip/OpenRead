@extends('layouts.app')

@section('style')
<style>
    .btn-spacing {
        margin: 8px 5px;
    }

    .box2 {
        background-color: #3A3B44;
        margin: 2% 1%;
        padding: 3% 4.5%;
        border-radius: 10px;
    }
</style>
@endsection

@php
    $rate = 0;
    if (isset($userRating)){
        $rate = $userRating['rate'];
    }
@endphp

@if($rate == 1) {{ 'active' }} @endif

@section('content')
<div class="content text-white container">
    <div class="row">
        <div class="col-lg-auto col-sm-12">
            <img class="display-cover-bg" src="{{ route('view-story-cover', ['name' => $story['cover'] ?? 'default']) }}" alt="">
        </div>
        <div class="col-lg-8 col-sm-12">
            <p class="display-title display-content title3">{{ $story['title'] }}</p>
            <div style="display: flex;">
                <p class="display-content font px-2">by {{ $story['author'] }}</p>
                <p class="display-content font px-2">Status : {{ $story['status'] }}</p>
            </div>

            @auth
            <div type="px-2"style="display: flex;">
                <span class="display-content font px-2">Rate : </span>
                <div class="btn-group btn-spacing px-2" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-secondary" id="rate-1" onclick="submitRating(1);">1</button>
                    <button type="button" class="btn btn-secondary" id="rate-2" onclick="submitRating(2);">2</button>
                    <button type="button" class="btn btn-secondary" id="rate-3" onclick="submitRating(3);">3</button>
                    <button type="button" class="btn btn-secondary" id="rate-4" onclick="submitRating(4);">4</button>
                    <button type="button" class="btn btn-secondary" id="rate-5" onclick="submitRating(5);">5</button>
                </div>
            </div>
            @endauth

            <div>
                <img class="display-content" style="width: 30px;" src="{{ asset('assets/Star.svg.png') }}" alt="">
                <span class="display-content px-2" style="font-size: 22px;" id="rating">{{ sprintf("%.2f", $story['rate']) }}</span>
                <img class="display-content" style="width: 30px;" src="{{ asset('assets/view.png') }}" alt="">
                <span class="display-content px-2" style="font-size: 22px;">{{ $story['views'] }}</span>
            </div>

            <div>
                @foreach ($story['genres'] as $genre)
                    <a href="{{ route('genre', ['genre_id' => $genre['genre_id']]) }}" class="btn btn-secondary btn-spacing">
                        {{ $genre['genre_type'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    <div style="margin: 5% 0;">
        <h1 style="margin : 0% 2%">Synopsis</h1>
        <p class="text-white text-justify box2" style="font-size: 1.25rem; line-height: 2;">{{ $story['sinopsis'] }}</p>
    </div>
    <div style="margin: 5% 0;">
        <h1 style="margin : 0% 2%">Chapter List</h1>
        <div class="box2 list-group list-group-flush" style="font-size: 1.35rem; line-height: 2;">
            @forelse ($chapters as $chapter)
                <a href="{{ route('read-chapter', ['chapter_id' => $chapter['chapter_id']]) }}" class="list-group-item list-group-item-action text-white" style="background-color:#3A3B44;">Chapter {{ $chapter['index'].' : '.$chapter['title'] }}</a>
            @empty
                <div class="list-group-item list-group-item-action text-white" style="background-color:#3A3B44;">There is no chapter to show</div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function () {
        setActiveRateBtn({{ $rate }});
    });

    function submitRating(rate) {
        $.ajax({
            url: "{{ route('rate-story', ['story_id' => $story['story_id'] ]) }}",
            type: "POST",
            data: { rate : rate },
            success: function (data) {
                console.log(data);
                var result = data.result;
                if (result.success){
                    $('#rating').text(result.new_rate);
                    setActiveRateBtn(result.rating.rate);
                }
            },
            error: function (e) {
                if (e.status == 429){
                    alert('You have spammed too much requests! Please wait to make another one!');
                }
                else {
                    alert('An error has happened! Please refresh the page!');
                }
            }
        });
    }

    function setActiveRateBtn(rate) {
        $('#rate-1').removeClass('active');
        $('#rate-2').removeClass('active');
        $('#rate-3').removeClass('active');
        $('#rate-4').removeClass('active');
        $('#rate-5').removeClass('active');

        $(`#rate-${rate}`).addClass('active');
    }
</script>
@endsection