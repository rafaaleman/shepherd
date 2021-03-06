@extends('layouts.app_simple')

@section('content')
@php
$bg = rand(1,3);
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 image" style="background-image: url('{{asset('/img/bg'.$bg.'.png')}}')"></div>

        <div class="col d-flex justify-content-center align-items-center text-center mt-4">
            
            <form method="POST" action="{{ route('verify.lockbox_store') }}" id="two_factor_code_form">
                @csrf

                <img srcset="{{asset('/img/LogoShepherd.png')}},
                        {{asset('/img/LogoShepherd@2x.png')}} 2x,
                        {{asset('/img/LogoShepherd@3x.png')}} 3x"
                src="{{asset('/img/LogoShepherd.png')}}"
                alt="Main Shepherd logo"
                class="mb-5" >

                <h4 class="mb-3">Type your verification code Lockbox</h4>
                <p class="text-muted">
                    To protect personal health information, we require two-step verification when you log-in to your account. 
                </p>
                <p class="text-muted">
                    You have been emailed a verification code to confirm your identity. Please enter this code to proceed. If you have not received an email containing your code, please check your spam or junk folder or click <a href="{{ route('verify.resend') }}" id="resend"><b>here</b> to resend</a>. 
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
                    <div class="col-12 text-center">
                        <a class="btn btn-danger loadingBtn btn-lg mb-4" href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                           Back
                        </a>
                    <input type="hidden" name="lockbox" value="1">
                        <button type="submit" class="btn btn-primary loadingBtn btn-lg mb-4" id="verifyBtn">
                            Verify
                        </button>
                    </div>
                </div>
            </form>
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

<form id="logoutform" action="{{ route('verify.lockbox_cancel') }}" method="POST" style="display: none;">
    {{ csrf_field() }}
</form>