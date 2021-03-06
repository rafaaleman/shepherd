@extends('layouts.app_simple')

@section('content')
@php
    $bg = rand(1,3);
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 image d-none d-lg-block" style="background-image: url('{{asset('/img/bg'.$bg.'.png')}}')"></div>

        <div class="col d-flex justify-content-center align-items-center text-center mt-4">
            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="register_user">
                @csrf

                <img srcset="{{asset('/img/LogoShepherd.png')}},
                        {{asset('/img/LogoShepherd@2x.png')}} 2x,
                        {{asset('/img/LogoShepherd@3x.png')}} 3x"
                src="{{asset('/img/LogoShepherd.png')}}"
                alt="Main Shepherd logo"
                class="mt-4 mb-5" >

                <div class="form-group row">
                    <div class="col-md-4 col-form-label float-right">
                        <div class="img-fluid d-none" id="profile-photo"></div>
                    </div>
                    <div class="col-md-7 d-flex justify-content-center mb-3 ">
                        <div class="btn btn-primary btn-block photo-btn">
                            <i class="far fa-user mb-1"></i> Upload your Profile Photo
                        </div>
                        <input id="photo" type="file" class="d-none" name="photo" accept=".jpg, .png">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('First Name') }} <span class="text-danger">*</span></label>

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
                    <label for="lastname" class="col-md-4 col-form-label text-md-right">Last Name <span class="text-danger">*</span></label>

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
                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }} <span class="text-danger">*</span></label>

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
                    <label for="phone" class="col-md-4 col-form-label text-md-right">Mobile Number <span class="text-danger">*</span></label>

                    <div class="col-md-7">
                        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" autocomplete="phone" >

                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="dob" class="col-md-4 col-form-label text-md-right">Date of Birth <span class="text-danger">*</span></label>

                    <div class="col-md-7 birthdate">
                        <input required type="text" pattern="[0-9]+" maxlength="2" name="dob-month" id="dob-month" autocomplete="dob-month" placeholder="MM">
                        <input required type="text" pattern="[0-9]+" maxlength="2" name="dob-day" id="dob-day" autocomplete="dob-day" placeholder="DD">
                        <input required type="text" pattern="[0-9]+" maxlength="4" name="dob-year" id="dob-year" autocomplete="dob-year" placeholder="YYYY">

                        @error('dob')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }} <span class="text-danger">*</span></label>

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
                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }} <span class="text-danger">*</span></label>

                    <div class="col-md-7">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right"></label>

                    <div class="col-md-7">
                        *Asterisk indicates required field
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
                        <div class="text-black-50 mb-5 pb-5"> Already have an account? 
                            <a class="d-block btn btn-primary btn-lg mt-5 mb-5" href="{{ route('login') }}">Login</a></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>

    .birthdate {
        display: flex;
        padding: 0 15px;
        justify-content: space-between;
    }

    .birthdate input {
        padding: 0.375rem 0.75rem;
        border: 1px solid #ced4da;
    border-radius: 0.25rem;
    }

    .birthdate #dob-month,
    .birthdate #dob-day{
        width: 22%;
    }
    
    .birthdate #dob-year{
        width: 47%;
    }

    .form-group{
        margin-bottom: 10px;
    }
    
    .form-group:nth-last-child(2){
        margin-bottom: 2rem;
    }

    .top-navigation{
        display: none;
    }
    
    .image{
        background-size: cover;
        background-position: top left;
        height: 60vh;
    }

    #register_user{
        width: 80%;
    }

    #profile-photo {
            height: 150px;
            border-radius: 10%;
            width: 150px;
            margin: -2em auto 0 auto;
            background-size: cover;
        }

    

    @media (min-width: 576px) {
        .image{
            background-size: cover;
            background-position: top left;
            height: 100vh;
        }

        #profile-photo{
            height: 30px;
            border-radius: 50%;
            width: 30px;
            background-size: cover;
            float: right;
            margin: 0;
        }        
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script>
<script>


$(function(){
    new Cleave('#phone', {
        numericOnly: true,
        blocks: [0, 3, 0, 3, 4],
        delimiters: ["(", ")", " ", "-"]
    });

    $('.photo-btn').click(function(){
        $('#photo').click();
    });

    $('.create').click(function(e){
        e.preventDefault();
        $('#phone').val($('#phone').val().replace(/\D/g,''));
        if(isNaN($('#dob-month').val()) || $('#dob-month').val() > 12){
            swal('Error', 'The month value should be numeric and between 1 - 12', 'error')
            return;
        }
        if(isNaN($('#dob-day').val()) || $('#dob-day').val() > 31){
            swal('Error', 'The day value should be numeric and between 1 - 31.', 'error')
            return;
        }
        if(isNaN($('#dob-year').val()) || $('#dob-year').val() > {{ date('Y') }}){
            swal('Error', "The year value should be numeric and lower than {{ date('Y') }}.", 'error')
            return;
        }

        $('#register_user').submit();
        $('.create').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Wait...').attr('disabled', true);
    });

        //Image on change
    $('#photo').on('change', function(e) {
        e.preventDefault();

        var file = $(this);
        var size = file[0].files[0].size;
		var ext = $(this).val().split('.').pop().toLowerCase();
        //Check Size
        if ((size/1024) < 30720) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#profile-photo').removeClass('d-none').css('background-image', 'url('+e.target.result+')');
            }

            reader.readAsDataURL(file[0].files[0]);
        } else {
            //Show Error
            swal('Error', 'The maximun file size is 30MB.', 'error')
        }
        
        return false;
    });
});
</script>
@endpush