<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm mb-4">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{asset('/img/logo-shepherd.png')}}"  alt="{{ config('app.name', 'Laravel') }}" class="mr-5">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    @guest
                    @else
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item mr-4">
                                <a href="{{route('home')}}" class="nav-link"><i class="fas fa-home"></i> Home</a>
                            </li>
                            <li class="nav-item mr-4">
                                <a href="{{route('loveone')}}" class="nav-link"><i class="far fa-heart"></i> Add Loved One</a>
                            </li>
                            {{-- <li class="nav-item mr-4 dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-puzzle-piece"></i> Sections
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item text-black-50" href="{{route('careteam')}}"><i class=" mr-2 fas fa-user-friends"></i> CareTeam</a>
                                    <a class="dropdown-item text-black-50" href="#"><i class=" mr-2 far fa-calendar-plus"></i> CareHub</a>
                                    <a class="dropdown-item text-black-50" href="#"><i class=" mr-2 fas fa-file-medical"></i> LockBox</a>
                                    <a class="dropdown-item text-black-50" href="#"><i class=" mr-2 fas fa-prescription-bottle-alt"></i> MyMedList</a>
                                    <a class="dropdown-item text-black-50" href="#"><i class=" mr-2 fas fa-globe"></i> Resources</a>
                                </div>
                            </li> --}}
                            <li class="nav-item mr-4">
                                <a href="#" class="nav-link"><i class="far fa-bell"></i> Notifications</a>
                            </li>
                        </ul>
                    @endguest

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item text-black-50" href="{{ route('user.profile') }}">
                                        <i class="fas fa-user-cog mr-2"></i> Profile
                                    </a>
                                
                                    <a class="dropdown-item text-black-50" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt mr-2 text-danger"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>

                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/general.js') }}"></script>
    @stack('scripts')
</body>
</html>
