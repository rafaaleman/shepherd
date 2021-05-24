<div class="shadow-sm mb-5 top-navigation">
    <nav class="navbar navbar-expand-md navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{asset('/img/logo-shepherd.png')}}"  alt="{{ config('app.name', 'Laravel') }}" class="mr-5">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                @guest
                    <!-- Left Side Of Navbar -->
                @else
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item mr-4 d-none d-sm-block">
                            <a href="{{route('home')}}" class="nav-link"><i class="fas fa-home"></i> Dashboard</a>
                        </li>

                        <li class="nav-item dropdown mr-4 d-block d-sm-none">
                            <a class="nav-link dropdown-toggle" href="{{route('home')}}" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fas fa-home"></i> Sections</a>

                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                @include('includes.common_menu')
                            </div>
                        </li>

                        <li class="nav-item mr-4">
                            <a href="{{route('loveone')}}" class="nav-link"><i class="far fa-heart"></i> Add Loved One</a>
                        </li>
                        <li class="nav-item mr-4">
                            <a href="{{ route('notifications', '**SLUG**')}}" class="menu-link nav-link"><i class="far fa-bell"></i> Notifications</a>
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
    
        
    <div class="col-md-12 p-3 d-flex justify-content-end top-bar">
        <div>
            <div class="name">Loveone Name</div>
            Loveone
        </div>
        <div class="photo" style="" alt=""></div>

    </div>
</div>

@push('scripts')
<script>
$(function(){

    loveone = localStorage.getItem('loveone');
    if(loveone != null){
        loveone = JSON.parse(loveone);
        // console.log(loveone.firstname);
        $('.top-bar .name').text(loveone.firstname + ' ' + loveone.lastname);
        $('.top-bar .photo').css('background-image', 'url('+loveone.photo+')');
        $('.top-bar').css('background-color', loveone.color);
    }

    current_loveone = localStorage.getItem('loveone');
    if(current_loveone != null){
        current_loveone = JSON.parse(current_loveone);
        $('.menu-link').each( function () { 
            newurl = $(this).attr('href');
            console.log(newurl);
            newurl = newurl.replace('**SLUG**', current_loveone.slug);
            $(this).attr('href', newurl)
        });
    }
});
</script>
@endpush