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

                <div class="title mb-3">Page expired :(</div>

                <div class="text-black-50">
                    Ooops! We found a problem... ;(
                </div>

                <div class="text-black-50 ">Already have an account?  
                    <a class="d-block btn btn-primary btn-lg mt-5 mb-5" href="{{ route('login') }}" class="">Login</a></div>
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
        height: 60vh;
    }

    @media (min-width: 576px) {
        .image{
            background-size: cover;
            background-position: top left;
            height: 100vh;
        }
    }

    .title{
        color: #454f63;
        font-weight: bold;
        font-size: 30px;
    }

    #login_form{
        width: 80%;
    }
</style>
@endpush

@push('scripts')
<script>
$(function(){
    $('.btn').click(function(){
        $(this).html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Wait...').attr('disabled', true);
    })
});
</script>
@endpush
