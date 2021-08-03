@extends('layouts.app_simple')

@section('content')
@php
    $bg = rand(1,3);
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 image" style="background-image: url('{{asset('/img/bg'.$bg.'.png')}}')"></div>

        <div class="col d-flex justify-content-center align-items-center text-center">
            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="register_user">
                @csrf

                <img srcset="{{asset('/img/LogoShepherd.png')}},
                        {{asset('/img/LogoShepherd@2x.png')}} 2x,
                        {{asset('/img/LogoShepherd@3x.png')}} 3x"
                src="{{asset('/img/LogoShepherd.png')}}"
                alt="Main Shepherd logo"
                class="mb-5" >

                <div class="form-group row">
                    <div class="col-md-4 col-form-label float-right">
                        <div class="img-fluid" id="profile-photo"></div>
                    </div>
                    <div class="col-md-7 d-flex justify-content-center mb-3 ">
                        <div class="btn btn-primary btn-block photo-btn">
                            <i class="far fa-user mb-1"></i> Upload Photo
                        </div>
                        <input id="photo" type="file" class="d-none" name="photo" accept=".jpg, .png">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                    <div class="col-md-7">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="lastname" class="col-md-4 col-form-label text-md-right">Last Name</label>

                    <div class="col-md-7">
                        <input id="lastname" type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname" value="{{ old('lastname') }}" required autocomplete="lastname">

                        @error('lastname')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                    <div class="col-md-7">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', (isset($email)) ? $email : '' ) }}" required autocomplete="email" {{isset($email) ? 'readonly' : ''}}>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="phone" class="col-md-4 col-form-label text-md-right">Mobile Number</label>

                    <div class="col-md-7">
                        <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="phone" minlength="10" maxlength="10">

                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="dob" class="col-md-4 col-form-label text-md-right">Date of Birth</label>

                    <div class="col-md-7">
                        <input id="dob" type="date" class="form-control @error('dob') is-invalid @enderror" name="dob" value="{{ old('dob') }}" autocomplete="dob" >

                        @error('dob')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                    <div class="col-md-7">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                    <div class="col-md-7">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary btn-lg create mb-5">
                            Create Account
                        </button>
                        <input type="hidden" name="token" value="{{isset($token) ? $token : ''}}">
                        <input type="hidden" name="company" value="{{isset($company) ? $company : ''}}">
                        <br>
                        <div class="text-black-50"> Have an account? <a href="{{ route('login') }}">Login</a></div>
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

    #register_user{
        width: 80%;
    }

    #profile-photo{
        height: 30px;
        border-radius: 50%;
        width: 30px;
        background-size: cover;
        float: right;
    }
</style>
@endpush

@push('scripts')
<script>
$(function(){
    $('.photo-btn').click(function(){
        $('#photo').click();
    });

    $('#register_user').submit(function(){

        $('.create').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Wait...').attr('disabled', true);
    });

        //Image on change
    $('#photo').on('change', function(e) {
        e.preventDefault();

        var file = $(this);
        var size = file[0].files[0].size;
		var ext = $(this).val().split('.').pop().toLowerCase();
        //Check Size
        if ((size/1024) < 1024) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#profile-photo').css('background-image', 'url('+e.target.result+')');
            }

            reader.readAsDataURL(file[0].files[0]);
        } else {
            //Show Error
            swal('Error', 'The maximun file size is 1MB.', 'error')
        }
        
        return false;
    });
});
</script>
@endpush