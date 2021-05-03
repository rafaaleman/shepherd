@extends('layouts.app')

@section('content')
<div class="container" id="home_new">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="d-flex justify-content-center">
                    
                <a href="{{route('loveone')}}" class="mr-3">
                    <div class="bigBtn">
                        <i class="far fa-heart mb-2"></i> <br>
                        Add Loved One
                    </div>
                </a>

                <a href="">
                    <div class="bigBtn">
                        <i class="fas fa-users mb-2"></i> <br>
                        Join an existing CareTeam
                    </div>
                </a>

            </div>
        </div>
        
    </div>
</div>
@endsection

@push('scripts')
<script>

</script>
@endpush
