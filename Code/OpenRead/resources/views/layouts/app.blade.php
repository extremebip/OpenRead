@php
    if (!isset($canEdit))
        $canEdit = false;
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'OpenRead')</title>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
    crossorigin="anonymous"></script>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    @yield('style')
</head>
<body style="background-color: #232327;">
    <div class="wrapper">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="background-color: #3A3B44;">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse bg-dark justify-content-between" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    @if (Route::currentRouteName() == "home")
                    <li class="nav-item px-2 active" aria-current="page">
                    @else
                    <li class="nav-item px-2">
                    @endif
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    @auth
                        @if (Route::currentRouteName() == "write-menu")
                        <li class="nav-item px-2 active" aria-current="page">
                        @else
                        <li class="nav-item px-2">
                        @endif
                            <a class="nav-link" href="{{ route('write-menu') }}">Write story</a>
                        </li>
                    @endauth
                    
                    <li class="nav-item dropdown px-2">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                          aria-expanded="false">Genre</a>
                        <ul class="dropdown-menu" style="background-color: #6D6E7D" aria-labelledby="navbarDropdown">
                          @foreach ($genres as $genre)
                            <li>
                                <a href="{{ route('genre', ['genre_id' => $genre['genre_id']]) }}" class="dropdown-item text-white">
                                    {{ $genre['name'] }}
                                </a>
                            </li>
                          @endforeach
                        </ul>
                    </li>
                    <li class="nav-item px-2">
                        {{ Form::open(['route' => 'search', 'method' => 'GET', 'class' => 'd-flex']) }}
                            <input type="search" class="form-control me-2" placeholder="Search" aria-label="Search" name="q">
                            <button class="btn btn-outline-success search-btn" type="submit">
                                <img src="{{ asset('assets/search_btn.png') }}" style="width: 16px;" class="img-fluid" alt="Responsive image">
                            </button>
                        {{ Form::close() }}
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    @guest
                        @if (Route::currentRouteName() == "register")
                        <li class="nav-item nav-reg px-2 active" aria-current="page">
                        @else
                        <li class="nav-item nav-reg px-2">
                        @endif
                            <a class="nav-link" href="{{ route('register') }}">Sign Up</a>
                        </li>

                        @if (Route::currentRouteName() == "login")
                        <li class="nav-item nav-signIn px-2 active" aria-current="page">
                        @else
                        <li class="nav-item nav-signIn px-2">
                        @endif
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                    @else
                        @php
                            $selfProfileURL = route('show-profile', ['u' => Auth::user()->username]);
                            $active = false;
                            if (in_array(Route::currentRouteName(), $currentProfileNavbar) && $canEdit){
                                $active = true;
                            }
                        @endphp
                        @if ($active)
                        <li class="nav-item px-2 active" aria-current="page">
                        @else
                        <li class="nav-item px-2">
                        @endif
                            <a class="nav-link" href="{{ $selfProfileURL }}">
                                Hello, {{ Auth::user()->username }}
                            </a>
                        </li>
                        <li class="nav-item px-2">
                            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
                            <form action="{{ route('logout') }}" method="POST" class="d-none" id="logout-form">
                                @csrf
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </nav>
        @yield('content')
    </div>
    <script>
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });
    </script>
    @yield('script')
</body>
</html>
