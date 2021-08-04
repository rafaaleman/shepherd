@extends('layouts.app_simple')
@php
    $bg = rand(1,3);
@endphp
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6 image" style="background-image: url('{{asset('/img/bg'.$bg.'.png')}}')"></div>

        <div class="col-md-6 d-flex justify-content-center align-items-center text-center">

            <form method="POST" action="{{ route('password.email') }}" style="width: 100%" id="reset-form">

                <img src="{{asset('/img/LogoShepherd.png')}}"  alt="{{ config('app.name', 'Shepherd') }}" class="mb-5">
                
                <h5 class="mb-4">{{ __('Reset Password') }}</h5>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @csrf

                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                    <div class="col-md-6 mb-3">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary resetBtn">
                            {{ __('Send Password Reset Link') }}
                        </button>

                        <div class="text-black-50 mt-5"> Already have an account? <a href="{{ route('login') }}">Login</a></div>
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
        background-position: center;
        height: 100vh;
    }

    #login_form{
        width: 100%;
    }
</style>
@endpush

@push('scripts')
<script>
$(function(){

    $('#reset-form').submit(function(){
        $('.resetBtn').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Wait...').attr('disabled', true);
    })
});
</script>
@endpush