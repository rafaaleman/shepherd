@extends('layouts.app_simple')

@section('content')
@php
    $bg = rand(1,3);
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 image" style="background-image: url('{{asset('/img/bg'.$bg.'.png')}}')"></div>

        <div class="col d-flex justify-content-center align-items-center text-center mt-4">
            <form method="POST" action="{{ route('login') }}" id="login_form">
                @csrf

                <img srcset="{{asset('/img/LogoShepherd.png')}},
                        {{asset('/img/LogoShepherd@2x.png')}} 2x,
                        {{asset('/img/LogoShepherd@3x.png')}} 3x"
                src="{{asset('/img/LogoShepherd.png')}}"
                alt="Main Shepherd logo"
                class="mb-5" >

                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                {{-- <div class="form-group row">
                    <div class="col-md-6 offset-md-4 text-left">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label text-black-50" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                    </div>
                </div> --}}

                <div class="form-group row mb-0">
                    <div class="col-md-7 offset-md-3">
                        <button type="submit" class="btn btn-primary btn-lg mt-3 mb-5" id="loginBtn">Login</button>
                        <br>
                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif

                        <div class="text-black-50 ">New to Shepherd? <a href="{{ route('register') }}" class="">Create an account</a></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>

    .top-navigation{
        display: none;
    }
    
    .image{
        background-size: cover;
        background-position: top left;
        height: 50vh;
    }

    @media (min-width: 576px) {
        .image{
            background-size: cover;
            background-position: top left;
            height: 100vh;
        }
    }

    #login_form{
        width: 100%;
    }
</style>
@endpush

@push('scripts')
<script>
$(function(){
    localStorage.removeItem('loveone');
    $('#login_form').submit(function(){
        console.log('login');
        $('#loginBtn').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Wait...').attr('disabled', true);
    })
});
</script>
@endpush
