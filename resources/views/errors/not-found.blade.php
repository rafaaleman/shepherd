@extends('layouts.app_simple')

@section('content')
@php
    $bg = rand(1,3);
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 image" style="background-image: url('{{asset('/img/bg'.$bg.'.png')}}')"></div>

        <div class="col d-flex justify-content-center align-items-center text-center">
            <form method="POST" action="{{ route('login') }}" id="login_form">
                @csrf

                <img srcset="{{asset('/img/LogoShepherd.png')}},
                        {{asset('/img/LogoShepherd@2x.png')}} 2x,
                        {{asset('/img/LogoShepherd@3x.png')}} 3x"
                src="{{asset('/img/LogoShepherd.png')}}"
                alt="Main Shepherd logo"
                class="mb-5" >

                <div class="title mb-3">Page not found :(</div>

                <div class="text-black-50">
                    We couldn´t find the page with the provided info. Please Verify it.
                </div>

                <a class="btn btn-primary btn-lg mt-5 mb-5" href="{{ route('home') }}">Return to dashboard</a>
                <br>
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
});
</script>
@endpush
