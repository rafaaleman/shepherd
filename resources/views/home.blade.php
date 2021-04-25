@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card dashboard">
                <div class="card-header">Your loveones</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @include('includes.home_carousel')
                </div>

                <div class="row p-5 d-flex justify-content-between">

                    @include('includes.home_careteam')
                    @include('includes.home_carehub')
                    @include('includes.home_lockbox')
                    @include('includes.home_medlist')

                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection
