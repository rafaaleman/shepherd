@extends('layouts.app_simple')

@section('content')
@php
$bg = rand(1,3);
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 image" style="background-image: url('{{asset('/img/bg'.$bg.'.png')}}')"></div>

        <div class="col d-flex justify-content-center align-items-center text-center mt-4">
            
            <form method="POST" action="{{ route('verify.store') }}" id="two_factor_code_form">
                @csrf

                <img srcset="{{asset('/img/LogoShepherd.png')}},
                        {{asset('/img/LogoShepherd@2x.png')}} 2x,
                        {{asset('/img/LogoShepherd@3x.png')}} 3x"
                src="{{asset('/img/LogoShepherd.png')}}"
                alt="Main Shepherd logo"
                class="mb-5" >

                <h1>Type your verification code</h1>
                <p class="text-muted">
                    You have received an email which contains a six digit verification code.
                    If you haven't received it, click <a href="{{ route('verify.resend') }}" id="resend"><b>here</b> to resend</a>.
                </p>

                @if(session()->has('message'))
                <p class="alert alert-info">
                    {{ session()->get('message') }}
                </p>
                @endif

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fa fa-lock"></i>
                        </span>
                    </div>
                    <input name="two_factor_code" type="text" class="form-control{{ $errors->has('two_factor_code') ? ' is-invalid' : '' }}" autocomplete="off" required autofocus placeholder="Login code" maxlength="6">
                    @if($errors->has('two_factor_code'))
                    <div class="invalid-feedback">
                        {{ $errors->first('two_factor_code') }}
                    </div>
                    @endif
                </div>

                <div class="row">
                    <div class="col-12 text-right">
                        <a class="btn btn-danger loadingBtn btn-lg mb-4" href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                            {{ trans('Sign Out') }}
                        </a>
                    
                        <button type="submit" class="btn btn-primary loadingBtn btn-lg mb-4" id="verifyBtn">
                            Verify
                        </button>
                    </div>
                </div>
            </form>
            <!-- <form method="POST" action="{{ route('login') }}" id="login_form">
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

              

                <div class="form-group row mb-0">
                    <div class="col-md-7 offset-md-3">
                        <button type="submit" class="btn btn-primary btn-lg mt-3 mb-5" id="loginBtn">Login</button>
                        <br>
                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif

                    </div>
                </div>
            </form> -->
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .top-navigation {
        display: none;
    }

    .image {
        background-size: cover;
        background-position: top left;
        height: 60vh;
    }

    @media (min-width: 576px) {
        .image {
            background-size: cover;
            background-position: top left;
            height: 100vh;
        }
    }

    #login_form {
        width: 100%;
    }
</style>
@endpush

@push('scripts')
<script>
    $(function() {

        $('#two_factor_code_form').submit(function() {
            $('#verifyBtn').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Wait...').attr('disabled', true);
        });

        $('#resend').click(function() {
            $('#verifyBtn').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Wait...').attr('disabled', true);
        })
    });
</script>
@endpush


<form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>
