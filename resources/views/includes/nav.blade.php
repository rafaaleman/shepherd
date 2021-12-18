
<nav class="navbar navbar-expand-md navbar-light bg-white">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img srcset="{{asset('/img/LogoShepherd.png')}},
                        {{asset('/img/LogoShepherd@2x.png')}} 2x,
                        {{asset('/img/LogoShepherd@3x.png')}} 3x"
                src="{{asset('/img/LogoShepherd.png')}}"
                alt="Shepherd logo"
                class=""
                width="120">
        </a>
        <a class="nav-link text-danger font-weight-bold  align-items-center d-flex d-sm-none" href="tel: 911">
            <i class="fas fa-phone-square fa-2x mr-1"></i> <span class="">911</span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>
        

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            @guest
                <!-- Left Side Of Navbar -->
            @else
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item mr-1 d-none d-sm-block">
                        <a href="{{route('home')}}" class="nav-link"><i class="fas fa-home"></i> CareHub</a>
                    </li>

                    <li class="nav-item dropdown mr-4 d-block d-sm-none">
                        <a class="nav-link dropdown-toggle" href="{{route('home')}}" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fas fa-home"></i> Sections</a>

                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            @include('includes.common_menu')
                        </div>
                    </li>

                    @if (Session::get('total_loveones') < 3)
                        <li class="nav-item mr-1">
                            <a href="{{route('loveone')}}" class="nav-link"><i class="far fa-heart"></i> Add Loved One</a>
                        </li>
                    @endif
                    <li class="nav-item mr-1 notificationsLnk">
                        <a href="{{ route('notifications', '**SLUG**')}}" class="menu-link nav-link {{ (Session::get('notifications') > 0) ? 'text-danger font-weight-bold' : ''}}">
                            <i class="{{ (Session::get('notifications') > 0) ? 'fas' : 'far'}} fa-bell"></i> Notifications
                        </a>
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
                    <li class="nav-item">
                        <a class="nav-link text-danger font-weight-bold mr-3 d-none d-md-flex align-items-center" href="tel: 911">
                            <i class="fas fa-phone-square fa-2x mr-1"></i> <span class="">911</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item text-black-50" href="{{ route('user.profile') }}">
                                <i class="fas fa-user-cog mr-2"></i> Profile
                            </a>
                        
                            <a class="dropdown-item text-black-50" href="#" id="logoutLnk">
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

@push('scripts')
<script>
$(function(){
    $('#logoutLnk').click(function(event){
        event.preventDefault(); 
        localStorage.removeItem('loveone');
        document.getElementById('logout-form').submit();
    });
});
</script>
@endpush