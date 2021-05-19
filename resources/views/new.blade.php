@extends('layouts.app_simple')

@section('content')
<div class="container" id="home_new">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <h4 class="mb-3">Hi, {{Auth()->user()->name}}</h4>
            <div class="d-flex justify-content-center flex-wrap">
                    
                <a href="{{route('loveone')}}" class="mr-md-3 mb-3 link">
                    <div class="bigBtn bg-primary px-5">
                        <i class="far fa-heart mb-2"></i> <br>
                        Add Loved One
                    </div>
                </a>

                <a href="{{route('careteam.joinTeam')}}" class="link mb-3">
                    <div class="bigBtn bg-primary">
                        <i class="fas fa-users mb-2"></i> <br>
                        Join an existing CareTeam
                    </div>
                </a>

            </div>
        </div>
        
    </div>
</div>
@endsection

@push('styles')
<style>
    .top-bar{
        display: none !important;
    }

    .link .bigBtn *{
        text-decoration: none;
    }
</style>

@endpush

@push('scripts')
<script>

</script>
@endpush
